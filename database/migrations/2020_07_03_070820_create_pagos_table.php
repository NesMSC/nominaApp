<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_empleado');
            $table->foreign('id_empleado')->references('id')->on('empleados');
            $table->unsignedBigInteger('id_nomina');
            $table->foreign('id_nomina')->references('id')->on('nominas')->onDelete('cascade');
            $table->string('sueldo');
            $table->string('salarioNormal');
            $table->json('asignaciones');
            $table->json('deducciones');
            $table->json('descuentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}
