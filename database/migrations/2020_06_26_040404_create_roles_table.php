<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('id');
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });

        DB::table('roles')->insert(['nombre'=>'Administrador', 'descripcion'=>'Administrador del sistema']);
        DB::table('roles')->insert(['nombre'=>'Apoyo', 'descripcion'=>'Administrativo de apoyo']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
