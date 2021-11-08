<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pago;
use App\Empleado;
use App\Salario;

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
    		if (!$pagos[$i]->quincena % 2 == 0) {
    			
    			$pagos[$i]->pago = $this->calcularPago($pagos[$i]);

    		}
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

    public function pdf($id_empleado, $id)
    {

        $pago_empleado = Pago::join('nominas', 'pagos.id_nomina', 'nominas.id')
                            ->join('empleados', 'pagos.id_empleado', 'empleados.id')
                            ->join('personas', 'empleados.persona_id', 'personas.id')
                            ->join('departamentos', 'empleados.departamento_id', 'departamentos.id')
                            ->select('personas.nombres', 'personas.apellidos', 'personas.cedula', 'empleados.grado', 'empleados.nivel', 
                            'departamentos.nombre as departamento', 'empleados.tipoPersonal', 'pagos.sueldo', 'pagos.salarioNormal', 
                            'pagos.asignaciones', 'pagos.deducciones', 'pagos.descuentos', 'nominas.fecha')
                            ->where('pagos.id', $id)
                            ->first();

        $asig = json_decode($pago_empleado->asignaciones);
        $deduc = json_decode($pago_empleado->deducciones);
        $desc = json_decode($pago_empleado->descuentos);

        $datos = ["datosEmpleado" => $pago_empleado, "asignaciones" => $asig, "deducciones" => $deduc, "descuentos" => $desc];

        if ($pago_empleado->tipoPersonal == 'Docente') {
            $docente_cat = DB::table('docente_cat')
                            ->select('categoria', 'dedicacion', 'pnf')
                            ->where('docente_cat.empleado_id', $id_empleado)
                            ->first();
            $datos=  ["datosEmpleado" => $pago_empleado, "docente" => $docente_cat, "asignaciones" => $asig, "deducciones" => $deduc, "descuentos" => $desc];
        } 

         
        /* $pdf = \PDF::loadView('pdf.reportePago', $datos);
        return $pdf->stream('Reporte.pdf');  */ 

        return $datos;
    }

}
