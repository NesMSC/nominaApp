<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;

class rolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        
        if ($request->busqueda) {
            $roles = Role::select('nombre', 'descripcion', 'estado')
                    ->where('nombre', 'like', "%$request->busqueda%")
                    ->orWhere('descripcion', 'like', "%$request->busqueda%")
                    ->orderBy('id', 'desc')
                    ->paginate(10);
        }else{
            $roles = Role::select('nombre', 'descripcion', 'estado')
                    ->orderBy('id', 'desc')
                    ->paginate(10);
        }
        
        return [
          "pagination" => [
                "total" => $roles->total(),
                "current_page" => $roles->currentPage(),
                "per_page" => $roles->perPage(),
                "last_page" => $roles->lastPage(),
                "from" => $roles->firstItem(),
                "to" => $roles->lastPage()
            ],
            "roles"=>$roles
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
