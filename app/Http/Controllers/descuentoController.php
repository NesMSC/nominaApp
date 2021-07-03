<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Descuento;

class descuentoController extends Controller
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
            $descuentos = Descuento::select('id','concepto', 'tipo', 'porcentaje')
                                    ->where('concepto', 'like', "%$request->busqueda%")
                                    ->orWhere('tipo', 'like', "%$request->busqueda%")
                                    ->orderBy('id', 'desc')
                                    ->paginate(10);

        }elseif (!$request->busqueda && $request->criterio) {
            //Solo con criterio
            $descuentos = Descuento::select('id','concepto', 'tipo', 'porcentaje')
                                    ->where('tipo', $request->criterio)
                                    ->paginate(10);

        }elseif ($request->busqueda && $request->criterio) {
            //Con busqueda y criterio
            $descuentos = Descuento::select('id','concepto', 'tipo', 'porcentaje')
                                    ->where('tipo', $request->criterio)
                                    ->where(function($query){
                                        global $request;
                                        $query->where('concepto', 'like', "%$request->busqueda%")
                                              ->orWhere('tipo', 'like', "%$request->busqueda%")
                                              ->orderBy('personas.id', 'desc');
                                    })
                                    ->paginate(10);
        }else{
            //Todos los datos
            $descuentos = Descuento::select('id','concepto', 'tipo', 'porcentaje')
                                    ->orderBy('id', 'desc')
                                    ->paginate(10);
        };

        return [
          "pagination" => [
                "total" => $descuentos->total(),
                "current_page" => $descuentos->currentPage(),
                "per_page" => $descuentos->perPage(),
                "last_page" => $descuentos->lastPage(),
                "from" => $descuentos->firstItem(),
                "to" => $descuentos->lastPage()
            ],
          "descuentos" => $descuentos
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

        $descuento = new Descuento;

        $descuento->concepto = $request->concepto;
        $descuento->tipo = $request->tipo;
        $descuento->porcentaje = $request->porcentaje;
        $descuento->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');

        $descuento = Descuento::findOrFail($id);

        return ["descuento" => $descuento];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');

        $descuento = Descuento::findOrFail($id);

        return ["descuento" => $descuento];
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
        if (!$request->ajax()) return redirect('/');

        DB::table('descuentos')->where('id', $request->id)->update(["concepto"=>$request->concepto, "tipo"=>$request->tipo, "porcentaje"=>$request->porcentaje]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {   
        if (!$request->ajax()) return redirect('/');

        DB::table('descuentos')->delete($id);
    }

}
