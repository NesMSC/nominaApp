<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Persona;
use App\Empleado;
use App\Salario;
use App\Hijo;
use App\Departamento;

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
          if($request->criterio == 'trashed'){
            $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                                ->onlyTrashed()
                                ->where('empleados.tipoPersonal', $request->tipo)
                                ->paginate(10);
          }else{
            $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->join('departamentos', 'empleados.departamento_id', 'departamentos.id')                                
                              ->select('personas.id as id', 'nombres', 'apellidos', 'grado', 'nivel', 'departamentos.nombre as departamento')
                              ->where('empleados.estado', $request->criterio)
                              ->where('empleados.tipoPersonal', $request->tipo)
                              ->orderBy('personas.id', 'desc')
                              ->paginate(10);
          }
          
        }elseif ($request->busqueda && !$request->criterio) {
          //Busqueda sin criterio
          $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->join('departamentos', 'empleados.departamento_id', 'departamentos.id')    
                              ->select('personas.id as id', 'nombres', 'apellidos', 'grado', 'nivel', 'departamentos.nombre as departamento')
                              ->where('empleados.tipoPersonal', $request->tipo)
                              ->where('nombres', 'like', "%$request->busqueda%")
                              ->orWhere('apellidos', 'like', "%$request->busqueda%")
                              ->orWhere('departamentos.nombre', 'like', "%$request->busqueda%")
                              ->orderBy('personas.id', 'desc')
                              ->paginate(10);
          
        }elseif ($request->busqueda && $request->criterio) {
          //Busqueda con dato buscado y criterio
          if($request->criterio == 'trashed'){
            $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                                ->join('departamentos', 'empleados.departamento_id', 'departamentos.id') 
                                ->onlyTrashed()
                                ->where('empleados.tipoPersonal', $request->tipo)
                                ->where(function($query){
                                  global $request;
                                  $query->where('nombres', 'like', "%$request->busqueda%")
                                        ->orWhere('apellidos', 'like', "%$request->busqueda%")
                                        ->orWhere('departamentos.nombre', 'like', "%$request->busqueda%")
                                        ->orderBy('personas.id', 'desc');
                                })
                                ->paginate(10);
          }else{
            $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->join('departamentos', 'empleados.departamento_id', 'departamentos.id')
                              ->select('personas.id as id', 'nombres', 'apellidos', 'grado', 'nivel', 'departamentos.nombre as departamento')
                              ->where('empleados.tipoPersonal', $request->tipo)
                              ->where('empleados.estado', $request->criterio)
                              ->where(function($query){
                                global $request;
                                $query->where('nombres', 'like', "%$request->busqueda%")
                                      ->orWhere('apellidos', 'like', "%$request->busqueda%")
                                      ->orWhere('departamentos.nombre', 'like', "%$request->busqueda%")
                                      ->orderBy('personas.id', 'desc');
                              })
                              ->paginate(10);
          }
          
        }else{
          //Todos los datos
          $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->join('departamentos', 'empleados.departamento_id', 'departamentos.id')
                              ->select('personas.id as id', 'nombres', 'apellidos', 'grado', 'nivel', 'departamentos.nombre as departamento')
                              ->where('empleados.tipoPersonal', $request->tipo)
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

    //Obreros
    public function indexObreros(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        if (!$request->busqueda && $request->criterio) {
          //Busqueda con solo el criterio
          if($request->criterio == 'trashed'){
            $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                                ->onlyTrashed()
                                ->where('empleados.tipoPersonal', $request->tipo)
                                ->paginate(10);
          }else{
            $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')                               
                              ->select('personas.id as id', 'nombres', 'apellidos', 'grado', 'nivel')
                              ->where('empleados.estado', $request->criterio)
                              ->where('empleados.tipoPersonal', $request->tipo)
                              ->orderBy('personas.id', 'desc')
                              ->paginate(10);
          }
          
        }elseif ($request->busqueda && !$request->criterio) {
          //Busqueda sin criterio
          $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')  
                              ->select('personas.id as id', 'nombres', 'apellidos', 'grado', 'nivel', 'tipoPersonal')                             
                              ->where('nombres', 'like', "%$request->busqueda%")
                              ->orWhere('apellidos', 'like', "%$request->busqueda%")
                              ->where('empleados.tipoPersonal', 'Obrero')
                              ->orderBy('personas.id', 'desc')
                              ->paginate(10);
          
        }elseif ($request->busqueda && $request->criterio) {
          //Busqueda con dato buscado y criterio
          if($request->criterio == 'trashed'){
            $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                                ->onlyTrashed()
                                ->where('empleados.tipoPersonal', 'Obrero')
                                ->where(function($query){
                                  global $request;
                                  $query->where('nombres', 'like', "%$request->busqueda%")
                                        ->orWhere('apellidos', 'like', "%$request->busqueda%")
                                        ->orderBy('personas.id', 'desc');
                                })
                                ->paginate(10);
          }else{
            $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->select('personas.id as id', 'nombres', 'apellidos', 'grado', 'nivel', 'empleados.tipoPersonal')
                              ->where('empleados.tipoPersonal', 'Obrero')
                              ->where('empleados.estado', $request->criterio)
                              ->where(function($query){
                                global $request;
                                $query->where('nombres', 'like', "%$request->busqueda%")
                                      ->orWhere('apellidos', 'like', "%$request->busqueda%")
                                      ->orderBy('personas.id', 'desc');
                              })
                              ->paginate(10);
          }
          
        }else{
          //Todos los datos
          $empleados = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                              ->select('personas.id as id', 'nombres', 'apellidos', 'grado', 'nivel')
                              ->where('empleados.tipoPersonal', 'Obrero')
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
        $correo = $request->correo;

        $busquedaRegistro = DB::table('personas')->where('cedula', "$cedula")->first();
        $busquedaCorreo = DB::table('personas')->where('correo', "$correo")->first();

        if ($busquedaRegistro || $busquedaCorreo) {
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
            $empleado->salario_id = $request->id_salario;
            $empleado->grado = $request->grado;
            $empleado->nivel = $request->nivel;
            $empleado->fechaIngreso = $request->fecha_ingreso;
            $empleado->instruccion = $request->grado_instruccion;
            $empleado->estado = $request->estado;
            $empleado->tipoPersonal = $request->tipo;
            if ($request->dep_id == null) {
              $empleado->departamento_id = ($request->newDep == null)?null:$this->newDepartamento($request->newDep);
            }else{
              $empleado->departamento_id = $request->dep_id;
            }
            
            $empleado->save();

            
            
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

        return ["respuesta"=>false]; 
        
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



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $empleado = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                            ->join('departamentos', 'empleados.departamento_id', 'departamentos.id')
                            ->join('salarios', 'empleados.salario_id', '=', 'salarios.id')
                            ->select('personas.id as id_persona', 'empleados.id as id_empleado', 'nombres', 
                            'apellidos', 'cedula', 'correo', 'telefono', 'nacimiento', 'grado', 'nivel', 
                            'fechaIngreso', 'departamentos.nombre as departamento', 'departamentos.id as dep_id', 'instruccion', 'estado', 'sexo')
                            ->where('personas.id', '=', $id)
                            ->get();
        $empleado[0]->fechaIngreso = Carbon::parse($empleado[0]->fechaIngreso)->isoFormat('DD-MM-YYYY');
        $empleado[0]->nacimiento = Carbon::parse($empleado[0]->fechaIngreso)->isoFormat('DD-MM-YYYY');
        $banco = Persona::find($id)->cuentaBancaria;

        $hijos = Hijo::join('personas', 'hijos.persona_id', 'personas.id')
                      ->join('empleado_hijo', 'hijos.id', 'empleado_hijo.hijo_id')
                      ->select('personas.nombres as nombre', 'personas.apellidos as apellido', 
                      'personas.nacimiento', 'personas.id as persona_id', 'hijos.id as hijo_id', 
                      'hijos.nivel', 'hijos.discapacidad')
                      ->where('empleado_hijo.empleado_id', '=', $empleado[0]->id_empleado)
                      ->get();
                      

        return ["empleado"=>$empleado, "banco" => $banco, "hijos" => $hijos];

    }

    //Obreros
    public function editObreros($id)
    {

        $empleado = Persona::join('empleados', 'personas.id' ,'=', 'empleados.persona_id')
                            ->join('salarios', 'empleados.salario_id', '=', 'salarios.id')
                            ->select('personas.id as id_persona', 'empleados.id as id_empleado', 'nombres', 
                            'apellidos', 'cedula', 'correo', 'telefono', 'nacimiento', 'grado', 'nivel', 
                            'fechaIngreso', 'instruccion', 'estado', 'sexo')
                            ->where('personas.id', '=', $id)
                            ->get();
        
        $banco = Persona::find($id)->cuentaBancaria;

        $hijos = Hijo::join('personas', 'hijos.persona_id', 'personas.id')
                      ->join('empleado_hijo', 'hijos.id', 'empleado_hijo.hijo_id')
                      ->select('personas.nombres as nombre', 'personas.apellidos as apellido', 
                      'personas.nacimiento', 'personas.id as persona_id', 'hijos.id as hijo_id', 
                      'hijos.nivel', 'hijos.discapacidad')
                      ->where('empleado_hijo.empleado_id', '=', $empleado[0]->id_empleado)
                      ->get();
        
        $empleado[0]->fechaIngreso = Carbon::parse($empleado[0]->fechaIngreso)->isoFormat('DD-MM-YYYY');
        $empleado[0]->nacimiento = Carbon::parse($empleado[0]->fechaIngreso)->isoFormat('DD-MM-YYYY');

        return ["empleado"=>$empleado, "banco" => $banco, "hijos" => $hijos];

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

      $dep_id = 0;

      if ($request->dep_id == null && $request->newDep != null) {
        $dep_id = $this->newDepartamento($request->newDep);
      }else{
        $dep_id = $request->dep_id;
      }
      
      DB::table('personas')
        ->where('id', $request->id_persona)
        ->update(['nombres' => $request->nombres, 'apellidos' => $request->apellidos, 'cedula' => $request->cedula, 'correo' => $request->correo, 'telefono' => $request->telefono, 'nacimiento'=>$request->nacimiento, 'sexo' => $request->sexo]);
      
      $this->updateOrInsertBanco($request->id_persona, $request->banco);
      
      DB::table('empleados')
        ->where('id', $request->id_empleado)
        ->update([
          'grado' => $request->grado, 
          'nivel' => $request->nivel, 
          'fechaIngreso' => $request->fecha_ingreso, 
          'departamento_id' => ($dep_id), 
          'instruccion' => $request->grado_instruccion, 
          'estado' => $request->estado
        ]);

      
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

    public function destroy($id, Request $request)
    {
      if (!$request->ajax()) return redirect('/');

      $persona = Persona::find($id);
      $empleado_id = $persona->empleado->id;

      $persona->delete();
      Empleado::find($empleado_id)->delete();
    }

    public function restore($id, Request $request)
    {
      if (!$request->ajax()) return redirect('/');

      $empleado = Empleado::onlyTrashed()
                        ->where('id', $id)
                        ->first();   
      $persona = Persona::onlyTrashed()
                        ->where('id', $empleado->persona_id)
                        ->restore();
      $empleado->restore();
    }

    public function salarioTabla(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $id_salario = $request->id_salario;

        $salario = Salario::join("ind_economicos", "salarios.indicador_id", "=", "ind_economicos.id")
                          ->select('tabulador', 'UnTributaria', 'salarioMin', 'cestaTicket')
                          ->where('salarios.id', '=', $id_salario)
                          ->get();

        return [
              "tabulador"=>$salario[0]->tabulador, 
              "UT"=>$salario[0]->UnTributaria,
              "salarioMin"=>$salario[0]->salarioMin,
              "cestaTicket"=>$salario[0]->cestaTicket,
            ];
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

    public function contar(Request $request)
    {

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

    public function cuentaBancaria($id, Request $request)
    {
      if (!$request->ajax()) return redirect('/');

      $cuenta = Persona::find($id)->cuentaBancaria;
      
      return ["cuenta" => $cuenta, "banco" => $cuenta->banco];
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

    public function departamentos(Request $request)
    {
      if (!$request->ajax()) return redirect('/');

      $departamentos = DB::table('departamentos')->get();

      return [
        "dep" => $departamentos,
      ];
    }

    public function newDepartamento($new){
      $dep = new Departamento;

      $dep->nombre = $new;
      $dep->save();

      return $dep->id;
    }
}
