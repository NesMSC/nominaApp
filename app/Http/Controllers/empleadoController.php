<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Persona;
use App\Empleado;
use App\Salario;

class empleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        if (!$request->busqueda && $request->criterio) {
          //Busqueda con solo el criterio
          $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->select('personas.id as id', 'nombres', 'apellidos', 'grado', 'nivel', 'departamento')
                              ->where('empleados.estado', $request->criterio)
                              ->where('empleados.tipoPersonal', $request->tipo)
                              ->orderBy('personas.id', 'desc')
                              ->paginate(5);

          
        }elseif ($request->busqueda && !$request->criterio) {
          //Busqueda sin criterio
          $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->select('personas.id as id', 'nombres', 'apellidos', 'grado', 'nivel', 'departamento')
                              ->where('empleados.tipoPersonal', $request->tipo)
                              ->where('nombres', 'like', "%$request->busqueda%")
                              ->orWhere('apellidos', 'like', "%$request->busqueda%")
                              ->orWhere('departamento', 'like', "%$request->busqueda%")
                              ->orderBy('personas.id', 'desc')
                              ->paginate(5);
          
        }elseif ($request->busqueda && $request->criterio) {
          //Busqueda con dato buscado y criterio
          $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->select('personas.id as id', 'nombres', 'apellidos', 'grado', 'nivel', 'departamento')
                              ->where('empleados.tipoPersonal', $request->tipo)
                              ->where('empleados.estado', $request->criterio)
                              ->where(function($query){
                                global $request;
                                $query->where('nombres', 'like', "%$request->busqueda%")
                                      ->orWhere('apellidos', 'like', "%$request->busqueda%")
                                      ->orWhere('departamento', 'like', "%$request->busqueda%")
                                      ->orderBy('personas.id', 'desc');
                              })
                              ->paginate(5);
        }else{
          //Todos los datos
          $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->select('personas.id as id', 'nombres', 'apellidos', 'grado', 'nivel', 'departamento')
                              ->where('empleados.tipoPersonal', $request->tipo)
                              ->orderBy('personas.id', 'desc')
                              ->paginate(5);

        };



        

        return [
          "pagination" => [
                "total" => $empleados->total(),
                "current_page" => $empleados->currentPage(),
                "per_page" => $empleados->perPage(),
                "last_page" => $empleados->lastPage(),
                "from" => $empleados->firstItem(),
                "to" => $empleados->lastPage()
            ],
          "empleados" => $empleados
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $cedula = $request->cedula;
        $busquedaRegistro = DB::table('personas')->where('cedula', '=', "$cedula")->first();

        if ($busquedaRegistro) {
            return ["respuesta"=>true];
        };
        
        try{

            DB::beginTransaction();

            $persona = new Persona;
            $persona->nombres = $request->nombres;
            $persona->apellidos = $request->apellidos;
            $persona->cedula = $request->cedula;
            $persona->correo = $request->correo;
            $persona->telefono = $request->telefono;
            $persona->nacimiento = $request->nacimiento;
            $persona->sexo = $request->sexo;
            $persona->save();

            $empleado = new Empleado;
            $empleado->persona_id = $persona->id;
            $empleado->salario_id = 1;
            $empleado->grado = $request->grado;
            $empleado->nivel = $request->nivel;
            $empleado->fechaIngreso = $request->fecha_ingreso;
            $empleado->departamento = $request->departamento;
            $empleado->instruccion = $request->grado_instruccion;
            $empleado->estado = $request->estado;
            $empleado->tipoPersonal = $request->tipo;
            $empleado->save();

            //agregar beneficios
            $beneficios = $request->beneficios;
            for ($i=0; $i < count($beneficios); $i++) { 
                $empleado->beneficio()->attach($beneficios[$i]);
            };

            //Agregar descuentos
            $descuentos = $request->descuentos;
            for ($i=0; $i < count($descuentos); $i++) { 
                $empleado->descuento()->attach($descuentos[$i]);
            };

            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
            return $e;
        }

        return ["respuesta"=>false];
        
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $empleado = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                            ->join('salarios', 'empleados.salario_id', '=', 'salarios.id')
                            ->select('personas.id as id_persona', 'empleados.id as id_empleado', 'nombres', 'apellidos', 'cedula', 'correo', 'telefono', 'nacimiento', 'grado', 'nivel', 'fechaIngreso', 'departamento', 'instruccion', 'estado', 'sexo')
                            ->where('personas.id', '=', $id)
                            ->get();
        
        

        return ["empleado"=>$empleado];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function editarSalarios($id)
    {
        $empleado_bene = Empleado::find($id)->beneficio;

        $empleado_deduc = DB::table('descuentos')
                          ->join('descuento_empleado', 'descuentos.id', 'descuento_empleado.descuento_id')
                          ->select('descuentos.id as id','descuentos.concepto', 'descuentos.porcentaje', 'descuentos.tipo')
                          ->where('descuentos.tipo', '!=' ,'Descuento')
                          ->where('descuento_empleado.empleado_id', $id)->get();

        $empleado_desc = DB::table('descuentos')
                          ->join('descuento_empleado', 'descuentos.id', 'descuento_empleado.descuento_id')
                          ->select('descuentos.id as id','descuentos.concepto', 'descuentos.porcentaje', 'descuentos.tipo')
                          ->where('descuentos.tipo', 'Descuento')
                          ->where('descuento_empleado.empleado_id', $id)->get();


        return ["beneficios" => $empleado_bene, "descuentos" => $empleado_desc, "deducciones" => $empleado_deduc];
    }

    public function update(Request $request)
    {
      DB::table('personas')
        ->where('id', $request->id_persona)
        ->update(['nombres' => $request->nombres, 'apellidos' => $request->apellidos, 'cedula' => $request->cedula, 'correo' => $request->correo, 'telefono' => $request->telefono, 'nacimiento'=>$request->nacimiento, 'sexo' => $request->sexo]);

      DB::table('empleados')
        ->where('id', $request->id_empleado)
        ->update(['grado' => $request->grado, 'nivel' => $request->nivel, 'fechaIngreso' => $request->fecha_ingreso, 'departamento' => $request->departamento, 'instruccion' => $request->grado_instruccion, 'estado' => $request->estado]);

      
      DB::table('beneficio_empleado')->where('empleado_id', '=', $request->id_empleado)->delete();
      DB::table('descuento_empleado')->where('empleado_id', '=', $request->id_empleado)->delete();

      $empleado = Empleado::find($request->id_empleado);

      //Actualiza beneficios
      $beneficios = $request->beneficios;
        for ($i=0; $i < count($beneficios); $i++) { 
          $empleado->beneficio()->attach($beneficios[$i]);
        };

      //Actualiza descuentos
      $descuentos = $request->descuentos;
        for ($i=0; $i < count($descuentos); $i++) { 
          $empleado->descuento()->attach($descuentos[$i]);
        };

      return true; 
    }

    public function salarioTabla(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $id_salario = $request->id_salario;

        $salario = Salario::join("ind_economicos", "salarios.indicador_id", "=", "ind_economicos.id")->select('tabulador', 'UnTributaria')
                          ->where('salarios.id', '=', $id_salario)
                          ->get();

        return ["tabulador"=>$salario[0]->tabulador, "UT"=>$salario[0]->UnTributaria];
    }

    public function beneficios(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $beneficios = DB::table('beneficios')->get();

        return ["beneficios" => $beneficios];
    }

    public function primaProfesional($instruccion, $salario)
    {

        $json = file_get_contents('json/primaProfesional.json');
        $porcentajes = json_decode($json, true);

        if (!isset($porcentajes[$instruccion])) {
            return 0;
        }
        $prima = $porcentajes[$instruccion];

        return ($prima*$salario)/100;
    }

    public function primaAntiguedad(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $prima = DB::table('beneficios')
                      ->select('id')
                      ->where('concepto', '=', 'Prima de Antiguedad')
                      ->get();

        return ["primaAntiguedad" => $prima];
    }

    public function descuentos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $descuentos = DB::table('descuentos')
                      ->where('tipo', 'Descuento')
                      ->get();

        return ["descuentos" => $descuentos];
    }

    public function deducciones(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $deducciones = DB::table('descuentos')
                      ->where('tipo', '!=','Descuento')
                      ->get();

        return ["deducciones" => $deducciones];
    }

    public function contar(Request $request){

      if (!$request->ajax()) return redirect('/');

      $admin = DB::table('empleados')
                ->where('tipoPersonal', 'Administrativo')
                ->count();

      $docente = DB::table('empleados')
                ->where('tipoPersonal', 'Docente')
                ->count();

      $obrero = DB::table('empleados')
                ->where('tipoPersonal', 'Obrero')
                ->count();

      return ["admin" => $admin, "docente" => $docente, "obrero" => $obrero];
    }
}
