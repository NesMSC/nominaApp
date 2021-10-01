<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Persona;
use App\Empleado;
use App\Salario;
use App\Hijo;

class docenteController extends Controller
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
          if($request->criterio == 'trashed'){
            $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                                ->join('docente_cat', 'empleados.id', '=', 'docente_cat.empleado_id')
                                ->onlyTrashed()->paginate(10);
          }else{
            $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->join('docente_cat', 'empleados.id', '=', 'docente_cat.empleado_id')
                              ->select('personas.id as id', 'empleados.id as id_empleado', 'nombres', 'apellidos', 'empleados.tipoPersonal', 'pnf', 'categoria', 'dedicacion')
                              ->where('empleados.estado', $request->criterio)
                              ->orderBy('personas.id', 'desc')
                              ->paginate(10);
          }

          

          
        }elseif ($request->busqueda && !$request->criterio) {
          //Busqueda sin criterio
          $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->join('docente_cat', 'empleados.id', '=', 'docente_cat.empleado_id')
                              ->select('personas.id as id', 'empleados.id as id_empleado', 'nombres', 'apellidos', 'empleados.tipoPersonal', 'pnf', 'categoria', 'dedicacion')
                              ->where('nombres', 'like', "%$request->busqueda%")
                              ->orWhere('apellidos', 'like', "%$request->busqueda%")
                              ->orWhere('pnf', 'like', "%$request->busqueda%")
                              ->orWhere('categoria', 'like', "%$request->busqueda%")
                              ->orWhere('dedicacion', 'like', "%$request->busqueda%")
                              ->orderBy('personas.id', 'desc')
                              ->paginate(10);
          
        }elseif ($request->busqueda && $request->criterio) {
          //Busqueda con dato buscado y criterio
          if($request->criterio == 'trashed'){
            $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                                ->join('docente_cat', 'empleados.id', '=', 'docente_cat.empleado_id')
                                ->onlyTrashed()
                                ->where(function($query){
                                  global $request;
                                  $query->where('nombres', 'like', "%$request->busqueda%")
                                      ->orWhere('apellidos', 'like', "%$request->busqueda%")
                                      ->orWhere('pnf', 'like', "%$request->busqueda%")
                                      ->orWhere('categoria', 'like', "%$request->busqueda%")
                                      ->orWhere('dedicacion', 'like', "%$request->busqueda%")
                                      ->orderBy('personas.id', 'desc');
                                })
                                ->paginate(10);
          }
          $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->join('docente_cat', 'empleados.id', '=', 'docente_cat.empleado_id')
                              ->select('personas.id as id', 'nombres', 'empleados.id as id_empleado', 'apellidos', 'empleados.tipoPersonal', 'pnf', 'categoria', 'dedicacion')
                              ->where('empleados.estado', $request->criterio)
                              ->where(function($query){
                                global $request;
                                $query->where('nombres', 'like', "%$request->busqueda%")
                                      ->orWhere('apellidos', 'like', "%$request->busqueda%")
                                      ->orWhere('pnf', 'like', "%$request->busqueda%")
                                      ->orWhere('categoria', 'like', "%$request->busqueda%")
                                      ->orWhere('dedicacion', 'like', "%$request->busqueda%")
                                      ->orderBy('personas.id', 'desc');
                              })
                              ->paginate(10);
        }else{
          //Todos los datos
          $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->join('docente_cat', 'empleados.id', '=', 'docente_cat.empleado_id')
                              ->select('personas.id as id', 'empleados.id as id_empleado', 'nombres', 'apellidos', 'empleados.tipoPersonal', 'pnf', 'categoria', 'dedicacion')
                              ->orderBy('personas.id', 'desc')
                              ->paginate(10);

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

            $this->updateOrInsertBanco($persona->id, $request->banco);

            $empleado = new Empleado;
            $empleado->persona_id = $persona->id;
            $empleado->salario_id = 3;
            $empleado->fechaIngreso = $request->fecha_ingreso;
            $empleado->instruccion = $request->instruccion;
            $empleado->estado = $request->estado;
            $empleado->tipoPersonal = $request->tipo;
            $empleado->save();

            $docente = DB::table('docente_cat')->insert([
                                'empleado_id'=>$empleado->id, 
                                'categoria'=>$request->categoria,
                                'dedicacion'=>$request->dedicacion,
                                'pnf'=>$request->docente_pnf
                            ]);

            if(!empty($request->hijos)){
              //Registrar hijos
              $this->registrarHijos($request->hijos, $empleado->id);
            }

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

        return ["respuesta"=>false, "empleado"=>$empleado];       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
      public function show(Request $request, $id)
    {

        if (!$request->ajax()) return redirect('/');

        $empleado = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                            ->join('salarios', 'empleados.salario_id', '=', 'salarios.id')
                            ->join('docente_cat', 'empleados.id', '=', 'docente_cat.empleado_id')
                            ->select('personas.id as id_persona', 'empleados.id as id_empleado', 'nombres', 'apellidos', 'cedula', 'correo', 'telefono', 'nacimiento', 'categoria', 'dedicacion', 'fechaIngreso', 'pnf', 'instruccion', 'estado', 'sexo')
                            ->where('personas.id', '=', $id)
                            ->get();
        
        $empleado_id = Persona::find($id)->empleado->id;
        $banco = Persona::find($id)->cuentaBancaria;

        $hijos = Hijo::join('personas', 'hijos.persona_id', 'personas.id')
                      ->join('empleado_hijo', 'hijos.id', 'empleado_hijo.hijo_id')
                      ->select('personas.nombres as nombre', 'personas.apellidos as apellido', 
                      'personas.nacimiento', 'personas.id as persona_id', 'hijos.id as hijo_id', 
                      'hijos.nivel', 'hijos.discapacidad')
                      ->where('empleado_hijo.empleado_id', '=', $empleado_id)
                      ->get();

        return [
          "empleado"=>$empleado,
          "banco" => $banco,
          "hijos" => $hijos
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      
      DB::table('personas')
        ->where('id', $request->id_persona)
        ->update(['nombres' => $request->nombres, 'apellidos' => $request->apellidos, 'cedula' => $request->cedula, 'correo' => $request->correo, 'telefono' => $request->telefono, 'nacimiento'=>$request->nacimiento, 'sexo' => $request->sexo]);

      DB::table('empleados')
        ->where('id', $request->id_empleado)
        ->update(['fechaIngreso' => $request->fecha_ingreso, 'instruccion' => $request->grado_instruccion, 'estado' => $request->estado]);

      DB::table('docente_cat')
        ->where('empleado_id', $request->id_empleado)
        ->update(['categoria'=>$request->categoria, 'dedicacion'=>$request->dedicacion, 'pnf'=>$request->docente_pnf]);
      
      $this->updateOrInsertBanco($request->id_persona, $request->banco);
      
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

      //Actualizar hijos
      $this->actualizarHijos($request->hijos, $request->id_empleado);

      return true; 
    }

    private function updateOrInsertBanco($id, $banco)
    {
      
      $datosBancarios = $banco;
      $banco = DB::table('bancos')
              ->select('id')
              ->where('codigo', $banco['banco'])
              ->first();

      DB::table('cuentas_bancarias')->updateOrinsert(
        ['persona_id' => $id],
        [
          'tipo_cuenta'=>$datosBancarios['tipo_cuenta'], 
          'numero_cuenta'=>$datosBancarios['numero_cuenta'],
          'banco_id'=>$banco->id,
          'persona_id' => $id
        ]
      );

    }

    public function buscarPadmin(Request $request)
    {
      $Padmin = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                          ->select('personas.id as id','empleados.id as empleado_id', 'nombres', 'apellidos', 'cedula')
                          ->where('empleados.tipoPersonal', 'Administrativo')
                          ->where(function($query){
                            global $request;
                            $query->where('nombres', 'like', "%$request->filtro%")
                            ->orWhere('apellidos', 'like', "%$request->filtro%")
                            ->orWhere('cedula', 'like', "%$request->filtro%");
                              })
                          ->get();

      return ["Padmin"=>$Padmin];
    }

    public function registrarAdmin(Request $request)
    {
      if(!$request->ajax()) return redirect('/');

      $id_empleado = $request->id_empleado;
      $busquedaRegistro = DB::table('docente_cat')->where('empleado_id', '=', "$id_empleado")->first();

      if ($busquedaRegistro) {
          return ["respuesta"=>false];
      };

      $docente = DB::table('docente_cat')->insert([
                                'empleado_id'=>$request->id_empleado, 
                                'categoria'=>$request->categoria,
                                'dedicacion'=>$request->dedicacion,
                                'pnf'=>$request->pnf
                            ]);

      return ['respuesta' => true];
    }

    public function actualizarDocenteAdmin(Request $request)
    {
      if(!$request->ajax()) return redirect('/');

      DB::table('docente_cat')
        ->where('empleado_id', $request->id_empleado)
        ->update(['categoria'=>$request->categoria, 'dedicacion'=>$request->dedicacion, 'pnf'=>$request->docente_pnf]);

        return true;
    }

    public function retirarDocenteAdmin(Request $request){
      if(!$request->ajax()) return redirect('/');

      DB::table('docente_cat')
        ->where('empleado_id', $request->id)
        ->delete();

        return true;
    }

    public function registrarHijos($data, $empleado_id)
    {
      $hijos = $data;

      for ($i=0; $i < count($hijos); $i++) { 
        $hijo = new Persona;
        $hijo->nombres = $hijos[$i]["nombre"];
        $hijo->apellidos = $hijos[$i]["apellido"];
        $hijo->nacimiento = $hijos[$i]["nacimiento"];
        $hijo->save();

        $datos_hijos = new Hijo;
        $datos_hijos->persona_id = $hijo->id;
        $datos_hijos->nivel = $hijos[$i]["nivel"];
        $datos_hijos->discapacidad = $hijos[$i]["discapacidad"];
        $datos_hijos->save();

        DB::table('empleado_hijo')->insert([
          "empleado_id" => $empleado_id,
          "hijo_id" => $datos_hijos->id,
        ]);
      };
    }

    public function actualizarHijos($hijos, $empleado)
    {

      $empleado_hijos = Empleado::find($empleado)->hijo;


      for ($i=0; $i < count($empleado_hijos) ; $i++) { 
        DB::table('hijos')->where('id', $empleado_hijos[$i]->id)->delete();
        DB::table('personas')->where('id', $empleado_hijos[$i]->persona_id)->delete();
      };

      $this->registrarHijos($hijos, $empleado);

    }
}

