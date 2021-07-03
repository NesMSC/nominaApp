<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
        'persona_id',
        'salario_id',
        'grado',
        'nivel',
        'fechaIngreso',
        'departamento',
        'instruccion',
        'estado'
    ];

    public function persona(){
        return $this->belongsTo('App\Persona');
    }

    public function salario(){
        return $this->belongsTo('App\Salario');
    }

    public function pago(){
        return $this->hasMany('App\Pago');
    }

    public function beneficio(){
        return $this->belongsToMany('App\Beneficio');
    }

    public function descuento(){
        return $this->belongsToMany('App\Descuento');
    }
}
