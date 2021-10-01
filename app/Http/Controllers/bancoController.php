<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Banco;

class bancoController extends Controller
{
    public function index()
    {
        $bancos = Banco::select('id', 'nombre', 'codigo')
                        ->orderBy('id', 'asc')
                        ->get();

        return ['bancos' => $bancos];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $banco = new Banco;
        $banco->nombre = $request->nombre;
        $banco->codigo = $request->codigo;
        $banco->save();

        return $banco->id;
    }

    public function show($id)
    {
        $banco = Banco::find($id);

        return ['banco' => $banco];
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        DB::table('bancos')->where('id', $request->id)
                            ->update([
                                        "nombre" => $request->nombre, 
                                        "codigo" => $request->codigo
                            ]);
    }

    public function destroy($id)
    {

        DB::table('bancos')
            ->where('id', $id)
            ->delete();

    }
}