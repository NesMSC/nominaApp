<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pago;
use App\Empleado;
use App\Salario;
use App\Nomina;
use App\Persona;

class pagosController extends Controller
{
    public function historialPagos($id)
    {
    	$pagos = Pago::join('nominas', 'pagos.id_nomina', 'nominas.id')
    				->select('pagos.id', 'sueldo', 'salarioNormal as pago', 'asignaciones', 'deducciones', 'descuentos', 'nominas.fecha', 'nominas.tipo', 'nominas.codigo as codigo')
    				->where('pagos.id_empleado', $id)
    				->orderBy('pagos.id', 'desc')
    				->paginate(10);


        for ($i=0; $i < count($pagos); $i++) { 
            $descuentos = json_decode($pagos[$i]->descuentos, true);
            $pagos[$i]->descuentos = $this->totalesDescuentos($descuentos);
            
            $deducciones = json_decode($pagos[$i]->deducciones, true);
            $pagos[$i]->deducciones = $this->totalesDeducciones($deducciones);

            $asignaciones = json_decode($pagos[$i]->asignaciones, true);
            $pagos[$i]->pago = $this->totalesPagos(
                $asignaciones, 
                $pagos[$i]->pago, 
                $pagos[$i]->deducciones["totalRetenciones"],
                $pagos[$i]->sueldo,
                $pagos[$i]->descuentos["total"]
            );
        }



    	return [
    		"pagination" => [
                "total" => $pagos->total(),
                "current_page" => $pagos->currentPage(),
                "per_page" => $pagos->perPage(),
                "last_page" => $pagos->lastPage(),
                "from" => $pagos->firstItem(),
                "to" => $pagos->lastPage()
            ],
    		"pagos" => $pagos
    	];

    }

    public function calcularPago($pago)
    {

        $salarioNormal = $pago->pago;
        $salarioTabla = $pago->sueldo;
        $deducciones = json_decode($pago->deducciones);
        $descuentos = json_decode($pago->descuentos);

        //Solo si es útimo <------- PENDIENTE
    	$pago = $salarioNormal-($salarioTabla/2);
    	$descontar = 0;

    	for ($i=0; $i < count($deducciones); $i++) { 
    		$calc = $deducciones[$i]->valor;

    		$descontar += $calc;
    	}  //Ahora se guardaran calculados PENDIENTE <--------

        for ($i=0; $i < count($descuentos); $i++) { 
    		$calc = $descuentos[$i]->valor;

    		$descontar += $calc;
    	} 

    	$pago = $pago - $descontar;

    	return round($pago, 2);

        return 0;
    }

    public function totalesDeducciones($deducciones)
    {
        $arrayTotales = ["totalRetenciones" => 0, "totalAportes" => 0];

        $arrayRetenciones = array_filter($deducciones, function($val){
            return $val['tipo'] == 'Retención';
        });

        $arrayAportes = array_filter($deducciones, function($val){
            return $val['tipo'] == 'Aporte';
        });

        $arrayTotales["totalRetenciones"] = array_reduce($arrayRetenciones, function($carry, $item){
            $carry += $item['valor'];
            return $carry;
        });
        $arrayTotales["totalAportes"] = array_reduce($arrayAportes,  function($carry, $item){
            $carry += $item['valor'];
            return $carry;
        });

        return $arrayTotales;
    }

    public function totalesDescuentos($descuentos)
    {
        $arrayTotales = ["total" => 0];

        $arrayTotales["total"] = array_reduce($descuentos, function($carry, $item){
            $carry += $item['valor'];
            return round($carry, 2);
        });

        return $arrayTotales;
    }

    public function totalesPagos($asignaciones, $salarioNormal, $totalRetenciones, $sueldo, $totalDesc)
    {
        $arrayTotales = [
            "salarioNormal" => (float)$salarioNormal, 
            "totalPrimas" => 0,
            "totalNeto" => 0,
        ];

        $arrayTotales["totalPrimas"] = array_reduce($asignaciones, function($carry, $item){
            $carry += $item['valor'];
            return round($carry, 2);
        });

        $total = ($arrayTotales["totalPrimas"] + $sueldo) - $totalRetenciones - $totalDesc;
        $arrayTotales["totalNeto"] = round($total, 2);

        return $arrayTotales;
    }

    public function pdf($id_empleado, $id)
    {
        $type = Empleado::find($id_empleado);
        
        if($type->tipoPersonal == 'Administrativo'){
            $pago_empleado = Pago::join('nominas', 'pagos.id_nomina', 'nominas.id')
                            ->join('empleados', 'pagos.id_empleado', 'empleados.id')
                            ->join('personas', 'empleados.persona_id', 'personas.id')
                            ->join('departamentos', 'empleados.departamento_id', 'departamentos.id')
                            ->select('personas.nombres', 'personas.apellidos', 'personas.cedula', 'empleados.grado', 'empleados.nivel', 
                            'departamentos.nombre as departamento', 'empleados.tipoPersonal', 'pagos.sueldo', 'pagos.salarioNormal', 
                            'pagos.asignaciones', 'pagos.deducciones', 'pagos.descuentos', 'nominas.fecha')
                            ->where('pagos.id', $id)
                            ->first();
        
        }else{
            $pago_empleado = Pago::join('nominas', 'pagos.id_nomina', 'nominas.id')
                            ->join('empleados', 'pagos.id_empleado', 'empleados.id')
                            ->join('personas', 'empleados.persona_id', 'personas.id')
                            ->select('personas.nombres', 'personas.apellidos', 'personas.cedula', 'empleados.grado', 'empleados.nivel', 'empleados.tipoPersonal', 'pagos.sueldo', 'pagos.salarioNormal', 
                            'pagos.asignaciones', 'pagos.deducciones', 'pagos.descuentos', 'nominas.fecha')
                            ->where('pagos.id', $id)
                            ->first();
        }

        $asig = json_decode($pago_empleado->asignaciones);
        $deduc = json_decode($pago_empleado->deducciones);
        $desc = json_decode($pago_empleado->descuentos);

        $datos = ["datosEmpleado" => $pago_empleado, "asignaciones" => $asig, "deducciones" => $deduc, "descuentos" => $desc];
        

        if ($pago_empleado->tipoPersonal == 'Docente') {
            $docente_cat = DB::table('docente_cat')
                            ->select('categoria', 'dedicacion', 'pnf')
                            ->where('docente_cat.empleado_id', $id_empleado)
                            ->first();
            $datos =  ["datosEmpleado" => $pago_empleado, "docente" => $docente_cat, "asignaciones" => $asig, "deducciones" => $deduc, "descuentos" => $desc];
        }
         
        $pdf = \PDF::loadView('pdf.reportePago', $datos);
        return $pdf->stream('Reporte.pdf'); 

    }

    public function datosEmpleado($id_empleado, $id)
    {
        $data = Empleado::join('personas', 'empleados.persona_id', 'personas.id')
                        ->where('empleados.id', $id_empleado)
                        ->first();

        if(!$data) return "EMPLEADO NO ENCONTRADO";

        if ($data->tipoPersonal == 'Docente') {
            $docente_cat = DB::table('docente_cat')
                            ->select('categoria', 'dedicacion', 'pnf')
                            ->where('docente_cat.empleado_id', $id_empleado)
                            ->first();
            return ["datosEmpleado" => $data, "docente" => $docente_cat];                  
        }

        return ["datosEmpleado" => $data];  
    }

    public function pagosIntervaloPDF($start, $end, $id_empleado, $id)
    {
        $datos = $this->buscarPagos($start, $end, $id_empleado);
        $pagos = $datos["pagos"];
        $data = $this->datosEmpleado($id_empleado, $id);

        $total = [];
        foreach($pagos as $key => $value){
            $total[$key] = $value;
        }

        $totalPagos = array_reduce($total, function($carry, $item){
            $carry += $item->pago["totalNeto"];
            return $carry;
        });

        $totalNormal = array_reduce($total, function($carry, $item){
            $carry += $item->pago["salarioNormal"];
            return $carry;
        });

        $totalSueldo = array_reduce($total, function($carry, $item){
            $carry += $item->sueldo;
            return $carry;
        });

        $totales = [
            $totalPagos,
            $totalNormal,
            $totalSueldo
        ];

       // return $data;

        if($data["datosEmpleado"]->tipoPersonal == "Docente"){
            $pdf = \PDF::loadView('pdf.pagosIntervaloReporte', ["pagos" => $pagos, "total" => $totales, "datosEmpleado" => $data["datosEmpleado"], "docente" => $data["docente"]]);
        }else{
            $pdf = \PDF::loadView('pdf.pagosIntervaloReporte', ["pagos" => $pagos, "total" => $totales, "datosEmpleado" => $data["datosEmpleado"]]);
        }

        return $pdf->stream('Reporte.pdf');
    }

    public function buscarPagos($start, $end, $id)
    {
        $pagos = Nomina::join('pagos', 'nominas.id', 'pagos.id_nomina')
                        ->select('pagos.id', 'sueldo', 'salarioNormal as pago', 'asignaciones', 
                        'deducciones', 'descuentos', 'nominas.fecha', 'nominas.tipo', 
                        'nominas.codigo as codigo')
                        ->where('pagos.id_empleado', $id)
                        ->whereBetween('fecha', [$start, $end])
                        ->paginate(10);

        for ($i=0; $i < count($pagos); $i++) { 
            $descuentos = json_decode($pagos[$i]->descuentos, true);
            $pagos[$i]->descuentos = $this->totalesDescuentos($descuentos);
            
            $deducciones = json_decode($pagos[$i]->deducciones, true);
            $pagos[$i]->deducciones = $this->totalesDeducciones($deducciones);

            $asignaciones = json_decode($pagos[$i]->asignaciones, true);
            $pagos[$i]->asignaciones = $asignaciones;
            $pagos[$i]->pago = $this->totalesPagos(
                $asignaciones, 
                $pagos[$i]->pago, 
                $pagos[$i]->deducciones["totalRetenciones"],
                $pagos[$i]->sueldo,
                $pagos[$i]->descuentos["total"]
            );
        }

        return [
            "pagination" => [
                "total" => $pagos->total(),
                "current_page" => $pagos->currentPage(),
                "per_page" => $pagos->perPage(),
                "last_page" => $pagos->lastPage(),
                "from" => $pagos->firstItem(),
                "to" => $pagos->lastPage()
            ],
            "pagos" => $pagos
        ];
    }

    public function datosReciboPago($ci, $year, $quincena)
    {
        $type = Persona::join('empleados', 'personas.id', 'empleados.persona_id')
                        ->where('cedula', "V-$ci")
                        ->orWhere('cedula', "E-$ci")
                        ->first();

        if(!$type) return response(["msg"=> "El empleado no existe en el sistema.", "code" => 404], 404);

        if($type->tipoPersonal == 'Administrativo'){
            $pago_empleado = Pago::join('nominas', 'pagos.id_nomina', 'nominas.id')
                            ->join('empleados', 'pagos.id_empleado', 'empleados.id')
                            ->join('personas', 'empleados.persona_id', 'personas.id')
                            ->join('departamentos', 'empleados.departamento_id', 'departamentos.id')
                            ->select('personas.nombres', 'personas.apellidos', 'personas.cedula', 'empleados.grado', 'empleados.nivel', 
                            'departamentos.nombre as departamento', 'empleados.tipoPersonal', 'pagos.sueldo', 'pagos.salarioNormal', 
                            'pagos.asignaciones', 'pagos.deducciones', 'pagos.descuentos', 'nominas.fecha')
                            ->where('nominas.codigo', "NA-$quincena".$year)
                            ->where('personas.id', $type->persona_id)
                            ->first();
        
        }elseif($type->tipoPersonal == 'Docente'){
            $pago_empleado = Pago::join('nominas', 'pagos.id_nomina', 'nominas.id')
                            ->join('empleados', 'pagos.id_empleado', 'empleados.id')
                            ->join('personas', 'empleados.persona_id', 'personas.id')
                            ->select('personas.nombres', 'personas.apellidos', 'personas.cedula', 'empleados.grado', 'empleados.nivel', 'empleados.tipoPersonal', 'pagos.sueldo', 'pagos.salarioNormal', 
                            'pagos.asignaciones', 'pagos.deducciones', 'pagos.descuentos', 'nominas.fecha')
                            ->where('nominas.codigo', "ND-$quincena".$year)
                            ->where('personas.id', $type->persona_id)
                            ->first();
        }else{
            $pago_empleado = Pago::join('nominas', 'pagos.id_nomina', 'nominas.id')
                            ->join('empleados', 'pagos.id_empleado', 'empleados.id')
                            ->join('personas', 'empleados.persona_id', 'personas.id')
                            ->select('personas.nombres', 'personas.apellidos', 'personas.cedula', 'empleados.grado', 'empleados.tipoPersonal', 'pagos.sueldo', 'pagos.salarioNormal', 
                            'pagos.asignaciones', 'pagos.deducciones', 'pagos.descuentos', 'nominas.fecha')
                            ->where('nominas.codigo', "NO-$quincena".$year)
                            ->where('personas.id', $type->persona_id)
                            ->first();
        }

        if(!$pago_empleado) return response(["msg"=> "El registro no existe.", "code" => 404], 404);
       
        $asig = json_decode($pago_empleado->asignaciones, true);
        $retencion = json_decode($pago_empleado->deducciones, true);
        $aportes = json_decode($pago_empleado->deducciones, true);
        $desc = json_decode($pago_empleado->descuentos, true);

        $retencion = array_filter($retencion, function($val){
            return $val["tipo"] == 'Retención';
        });

        $aportes = array_filter($aportes, function($val){
            return $val["tipo"] == 'Aporte';
        });

        $totalesDeducciones = $this->totalesDeducciones(json_decode($pago_empleado->deducciones, true));
        $totalDescuento = $this->totalesDescuentos($desc);
        $totalesPagos = $this->totalesPagos($asig, $pago_empleado->salarioNormal, $totalesDeducciones["totalRetenciones"], $pago_empleado->sueldo, $totalDescuento["total"]);
        
        // Array para las asignaciones
        $arrPrimas = [];
        foreach($asig as $key => $value){
            array_push($arrPrimas, $asig[$key]);
        }

        $pago_empleado->asignaciones = [
            "primas" => $arrPrimas,
            "totales" => $totalesPagos
        ];
        
        // Array para las retenciones
        $arrayRetenciones = [];
        foreach ($retencion as $key => $value) {
            array_push($arrayRetenciones, $retencion[$key]);
        }
        
        //Array para los aportes
        $arrayAportes = [];
        foreach ($aportes as $key => $value) {
            array_push($arrayAportes, $aportes[$key]);
        }

        $pago_empleado->deducciones = [
            "retenciones" => $arrayRetenciones,
            "aportes" => $arrayAportes,
            "totales" => $totalesDeducciones,
        ];

        //Array para los descuentos
        $arrayDescuentos = [];
        foreach ($desc as $key => $value) {
            array_push($arrayDescuentos, $desc[$key]);
        }
        $pago_empleado->descuentos = [
            "descuentos" => $arrayDescuentos,
            "total" => $totalDescuento["total"]
        ];

        return $pago_empleado;
    }

}
