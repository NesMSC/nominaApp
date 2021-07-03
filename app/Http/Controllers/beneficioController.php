<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Beneficio;

class beneficioController extends Controller
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
            $beneficios = Beneficio::select('id','concepto', 'tipo_valor', 'valor')
                                    ->where('concepto', 'like', "%$request->busqueda%")
                                    ->orWhere('valor', 'like', "%$request->busqueda%")
                                    ->orderBy('id', 'desc')
                                    ->paginate(10);
        }else{
            //Todos los datos
            $beneficios = Beneficio::select('id', 'concepto', 'tipo_valor', 'valor')
                                    ->orderBy('id', 'desc')
                                    ->paginate(10);
        };

        return [
          "pagination" => [
                "total" => $beneficios->total(),
                "current_page" => $beneficios->currentPage(),
                "per_page" => $beneficios->perPage(),
                "last_page" => $beneficios->lastPage(),
                "from" => $beneficios->firstItem(),
                "to" => $beneficios->lastPage()
            ],
          "beneficios" => $beneficios
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

        $beneficio = new Beneficio;

        $beneficio->concepto = $request->concepto;
        $beneficio->valor = $request->valor;
        $beneficio->tipo_valor = $request->tipo_valor;
        $beneficio->save();
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

        $beneficio = Beneficio::findOrFail($id);

        return ["beneficio" => $beneficio];
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

        $beneficio = Beneficio::findOrFail($id);

        return ["beneficio" => $beneficio];
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

        DB::table('beneficios')->where('id', $request->id)->update(["concepto"=>$request->concepto, "valor"=>$request->valor, "tipo_valor"=>$request->tipo_valor]);

        return $request;
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

        DB::table('beneficios')->delete($id);
    }

    public function primaProfesional(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $json = file_get_contents('json/primaProfesional.json');
        $json_prima=json_decode($json);

        return ["primaProfesional" => $json_prima];
    }

    public function primaProUpdate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $json = 'json/primaProfesional.json';
        $json_prima = file_get_contents($json);
        $json_prima = json_encode($request->json_prima);
        file_put_contents($json, $json_prima);
    }
}
