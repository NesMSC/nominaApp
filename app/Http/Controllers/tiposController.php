<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Tipo;

class tiposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        if($request->busqueda){
            $tipos = Tipo::where('name', 'like', "%$request->busqueda%")
                ->orWhere('description', 'like', "%$request->busqueda%")
                ->paginate(10);
        }else{
            $tipos = Tipo::paginate(10);
        }

        return [
            "pagination" => [
                  "total" => $tipos->total(),
                  "current_page" => $tipos->currentPage(),
                  "per_page" => $tipos->perPage(),
                  "last_page" => $tipos->lastPage(),
                  "from" => $tipos->firstItem(),
                  "to" => $tipos->lastPage()
              ],
            "tipos" => $tipos
          ];
    }

    public function getAll()
    {
        return Tipo::all();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        try {
            $tipo = new Tipo;
            $tipo->name = $request->name;
            $tipo->description = $request->description;
            $tipo->save();
    
            for ($i=0; $i < count($request->beneficiosId); $i++) { 
                DB::table('beneficio_tipo')->insert([
                    "beneficio_id" => $request->beneficiosId[$i], 
                    "tipo_id" => $tipo->id
                ]);
            }
    
            for ($i=0; $i < count($request->descuentosId); $i++) { 
                DB::table('descuento_tipo')->insert([
                    "descuento_id" => $request->descuentosId[$i], 
                    "tipo_id" => $tipo->id
                ]);
            }

        } catch (\Throwable $th) {
            throw $th;
        }
        

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
        DB::table('tipos')->delete($id);
    }
}
