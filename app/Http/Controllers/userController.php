<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Persona;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        if ($request->busqueda && !$request->criterio) {
            //Con busqueda
            $usuarios = User::join('roles', 'users.role_id', 'roles.id')
            					->select('users.id', 'name', 'condicion', 'roles.nombre as rol')
            					->where('name', 'like', "%$request->busqueda%")
                                ->orderBy('id', 'desc')
                                ->paginate(10);

        }elseif (!$request->busqueda && $request->criterio) {
            //Solo con criterio
            $usuarios = User::join('roles', 'users.role_id', 'roles.id')
            					->select('users.id', 'name', 'condicion', 'roles.nombre as rol')
                                ->where('roles.nombre', $request->criterio)
                                ->orWhere('users.condicion', ($request->criterio=='hab')?'1':'0')
                                ->paginate(10);

        }elseif ($request->busqueda && $request->criterio) {
            //Con busqueda y criterio
            $usuarios = User::join('roles', 'users.role_id', 'roles.id')
            					->select('users.id', 'name', 'condicion', 'roles.nombre as rol')
                                ->where('roles.nombre', $request->criterio)
                                ->orWhere('users.condicion', $request->criterio)
                                ->where(function($query){
                                    global $request;
                                    $query->where('name', 'like', "%$request->busqueda%")
                                          ->orderBy('users.id', 'desc');
                                })
                                ->paginate(10);
        }else{
            //Todos los datos
            $usuarios = User::join('roles', 'users.role_id', 'roles.id')
            					->select('users.id', 'name', 'condicion', 'roles.nombre as rol')
                                ->orderBy('id', 'desc')
                                ->paginate(10);
        };

        return [
          "pagination" => [
                "total" => $usuarios->total(),
                "current_page" => $usuarios->currentPage(),
                "per_page" => $usuarios->perPage(),
                "last_page" => $usuarios->lastPage(),
                "from" => $usuarios->firstItem(),
                "to" => $usuarios->lastPage()
            ],
          "usuarios" => $usuarios
        ];
    }

    public function buscarPerson(Request $request)
    {
    	if (!$request->ajax()) return redirect('/');
      	$personas = Persona::select('id','nombres', 'apellidos', 'cedula')
                        ->where('personas.user_id', '=', null)
                        ->where(function($query){
	                        global $request;
	                        $query->where('nombres', 'like', "%$request->filtro%")
	                        ->orWhere('apellidos', 'like', "%$request->filtro%")
	                        ->orWhere('cedula', 'like', "%$request->filtro%");
	                    })->get();

      return ["personas"=>$personas]; 
    }

    public function store(Request $request)
    {
    	if (!$request->ajax()) return redirect('/');

    	try{
    		DB::beginTransaction();
			$usuario = new User;
	    	$usuario->name = $request->usuario;
	    	$usuario->password = Hash::make($request->password);
	    	$usuario->role_id = $request->rol;
	    	$usuario->save();

	    	$persona = DB::table('personas')
	    				->where('id', $request->id_persona)
	    				->update(['user_id'=>$usuario->id]);
    		DB::commit();
    	} catch(Exception $e){
            DB::rollBack();
            return $e;
        }	
    }

    public function edit(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');

        $usuario = User::join('roles', 'users.role_id', 'roles.id')
        				->join('personas', 'users.id', 'personas.user_id')
    					->select('users.id', 'name', 'roles.id as rol', 'personas.nombres', 'personas.apellidos', 'personas.cedula')
                        ->where('users.id', $request->id)
                        ->first();

        return ["usuario" => $usuario];
    }


    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        DB::table('users')->where('id', $request->user_id)
        	->update(["name"=>$request->usuario, "role_id"=>$request->rol, "password"=>Hash::make($request->password)]);

    }


    public function cambiarCondicionUser(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        DB::table('users')->where('id', $request->user_id)
        	->update(["condicion"=>$request->condicion]);

    }

}
