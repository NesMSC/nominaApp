<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Salario;

class tabuladorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $tabuladores = Salario::select('id','tabulador')->get();

       return ['tabuladores' => $tabuladores]; 
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $tab)
    {
        if (!$request->ajax()) return redirect('/');

        $tabulador = $request->data;
        DB::table('salarios')->where('id', $tab)->update(['tabulador'=>$tabulador]);
    }

    public function indicadores(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $indicadores = DB::table('ind_economicos')
                        ->select('id', 'salarioMin', 'UnTributaria', 'gaceta', 'fecha', 'cestaTicket')
                        ->get();
        return ["indicadores" => $indicadores];

    }

    public function editarIndicador(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        DB::table('ind_economicos')->where('id', $request->id_ind)->update(["salarioMin"=>$request->salarioMin, "cestaTicket"=>$request->cestaTicket, "UnTributaria" => $request->ut, "gaceta" => $request->gaceta, "fecha" => $request->fecha]);

    }

}
