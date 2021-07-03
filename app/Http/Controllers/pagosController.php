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
    				->select('pagos.id', 'sueldo', 'salarioNormal as pago', 'codigo', 'asignaciones', 'deducciones', 'descuentos', 'nominas.fecha', 'nominas.quincena')
    				->where('pagos.id_empleado', $id)
    				->orderBy('pagos.id', 'desc')
    				->paginate(10);




    	for ($i=0; $i < count($pagos); $i++) { 
    		if (!$pagos[$i]->quincena % 2 == 0) {
    			
    			$pagos[$i]->pago = $this->calcularPago($pagos[$i]->pago, $pagos[$i]->sueldo, $pagos[$i]->deducciones, $pagos[$i]->descuentos);

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

    public function calcularPago($salarioNormal, $salario, $deducciones, $descuentos)
    {
    	$pago = $salarioNormal-($salario/2);
    	$deducciones = json_decode($deducciones, true);
    	$descuentos = json_decode($descuentos, true);
    	$descontar = 0;

    	for ($i=0; $i < count($deducciones); $i++) { 
    		$calc = ($salario*$deducciones[$i]["porcentaje"])/100;

    		$descontar += $calc;
    	}

    	$pago = $pago - $descontar;

    	return round($pago, 2);
    }

    public function pdf($id_empleado, $id)
    {

        $pago_empleado = Pago::join('nominas', 'pagos.id_nomina', 'nominas.id')
                            ->join('empleados', 'pagos.id_empleado', 'empleados.id')
                            ->join('personas', 'empleados.persona_id', 'personas.id')
                            ->select('personas.nombres', 'personas.apellidos', 'personas.cedula', 'empleados.grado', 'empleados.nivel', 'empleados.departamento', 'empleados.tipoPersonal', 'pagos.sueldo', 'pagos.salarioNormal', 'pagos.codigo', 'pagos.asignaciones', 'pagos.deducciones', 'pagos.descuentos', 'nominas.fecha', 'nominas.quincena')
                            ->where('pagos.id', $id)
                            ->first();

        $asig= $this->calcArrayAsig(json_decode($pago_empleado->asignaciones), $pago_empleado->sueldo, $id_empleado);
        $deduc=$this->calcArrayDeduc(json_decode($pago_empleado->asignaciones), json_decode($pago_empleado->deducciones), $pago_empleado->sueldo, $id_empleado);
        $desc=$this->calcArrayDesc(json_decode($pago_empleado->descuentos), $pago_empleado->sueldo, $id_empleado);

        $datos = ["datosEmpleado" => $pago_empleado, "asignaciones" => $asig, "deducciones" => $deduc, "descuentos" => $desc];

        if ($pago_empleado->tipoPersonal == 'Docente') {
            $docente_cat = DB::table('docente_cat')
                            ->select('categoria', 'dedicacion', 'pnf')
                            ->where('docente_cat.empleado_id', $id_empleado)
                            ->first();
            $datos=  ["datosEmpleado" => $pago_empleado, "docente" => $docente_cat, "asignaciones" => $asig, "deducciones" => $deduc, "descuentos" => $desc];
        }


        /*$pdf = \PDF::loadView('pdf.reportePago', $datos);
        return $pdf->stream('Reporte.pdf');*/

        return $datos;
    }

    public function calcArrayAsig($asig, $sueldo, $id)
    {

        $arrayAsig = [];
        $totalAsig = 0;

        
        for ($i=0; $i < count($asig); $i++) { 

            if ($asig[$i]->concepto == 'Prima de Profesionalización') {

                $calc = $this->primaProfesional($id, $sueldo);

                array_push($arrayAsig, ["concepto"=>"Prima de Profesionalización","valor"=>$calc]);
                 

                $totalAsig += $calc;
            };

            if ($asig[$i]->tipo_valor == 'U.T') {

                $calc = $this->calcUnidadTr($asig[$i]->valor);

                array_push($arrayAsig, ["concepto"=>$asig[$i]->concepto,"valor"=>$calc]);
                

                $totalAsig += $calc;
            };

            if ($asig[$i]->tipo_valor == '%' && $asig[$i]->concepto != 'Prima de Profesionalización' && $asig[$i]->concepto != 'Prima de Antiguedad') {

                $calc = ($sueldo*$asig[$i]->valor)/100;
                array_push($arrayAsig, ["concepto"=>$asig[$i]->concepto,"valor"=>$calc]);

                $totalAsig += $calc;
            };
        }

        $antiguedad = $this->primaAntiguedad($id, $totalAsig+$sueldo);

        array_push($arrayAsig, ["concepto"=>"Prima de Antiguedad","valor"=>$antiguedad]);

        return ["arrayAsignaciones"=>$arrayAsig, "totalAsignaciones" => $totalAsig+$antiguedad+$sueldo/2];

    }

    public function calcArrayDeduc($beneficios, $deducciones, $salario, $id_empleado)
    {
        $asignaciones = $this->calcArrayAsig($beneficios, $salario, $id_empleado);
        $totalAsig = $asignaciones["totalAsignaciones"] + $salario/2;
        $arrayDeduc = [];
        $total = 0;

        for ($i=0; $i < count($deducciones); $i++) {


            if ($deducciones[$i]->concepto =='S.S.O' || $deducciones[$i]->concepto == 'R.P.E') {
                $calc = $this->sso_rpe($deducciones[$i]->porcentaje, $totalAsig);
            }else if($deducciones[$i]->concepto == 'V.H' || $deducciones[$i]->concepto == 'F.J'){
                $calc = (($totalAsig*$deducciones[$i]->porcentaje)/100);
            }else{
                $calc = (($salarioTabla*$deducciones[$i]->porcentaje)/100);
            };

            array_push($arrayDeduc, ["concepto" => $deducciones[$i]->concepto, "valor" => $calc]);
             
            $total += $calc;
        }

        return ["arrayDeducciones" => $arrayDeduc, "totalDeducciones" => $total];

    }

    public function calcArrayDesc($desc, $salario)
    {
        $arrayDesc = [];
        $total = 0;

        for ($i=0; $i < count($desc); $i++) {

            $calc = ($salario*$desc[$i]->porcentaje)/100;

            array_push($arrayDesc, ["concepto" => $desc[$i]->concepto, "valor" => $calc]);

            $total += $calc; 
        }

        return ["arrayDescuentos"=>$arrayDesc, "totalDescuentos" => $total];
    }

    public function primaProfesional($id, $sueldo)
    {

        $empleado = Empleado::select('instruccion')
                                    ->where('id', $id)
                                    ->first();

        $json = file_get_contents('json/primaProfesional.json');
        $porcentajes = json_decode($json, true);

        if (!isset($porcentajes[$empleado->instruccion])) {
            return 0;
        }
        $prima = $porcentajes[$empleado->instruccion];

        return ($prima*$sueldo)/100;
    }

    public function primaAntiguedad($id, $total)
    {
        $empleado_bene = Empleado::join('beneficio_empleado', 'empleados.id', 'beneficio_empleado.empleado_id')
                                ->join('beneficios', 'beneficio_empleado.beneficio_id', 'beneficios.id')
                                ->select('beneficio_id')
                                ->where('empleado_id', $id)
                                ->where('beneficios.concepto', 'Prima de Antiguedad')
                                ->first();

        if ($empleado_bene) {
            $porcentaje = DB::table('beneficios')
                            ->select('valor')
                            ->where('concepto', 'Prima de Antiguedad')
                            ->first();
            $valor = (($total*$porcentaje->valor)/100)*$this->calcAñoServ($id);
            return  $valor;
        }else{
            return 0;
        }

    }

    public function calcUnidadTr($cantidad)
    {
        $unidad = DB::table('ind_economicos')
                    ->select('UnTributaria')
                    ->first();
        return $unidad->UnTributaria*$cantidad;
    }

    public function calcAñoServ($id)
    {
        $empleado = Empleado::select('fechaIngreso')
                            ->where('id', $id)
                            ->first();

        $año = substr($empleado->fechaIngreso, 0, 4);

        $actual = getdate()["year"];

        $añosServ = $actual-$año;

        return $añosServ;
    }

    public function sso_rpe($porcentaje, $totalAsig)
    {
        $valor = ((($totalAsig * 12)/52)*$porcentaje/100)*4;
        
        return $valor;
    }
}
