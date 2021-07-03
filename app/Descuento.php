<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    protected $fillable= [
        'concepto',
        'tipo',
        'porcentaje'
    ];
    
    function empleado(){
        return $this->belongsToMany('Empleado');
    }
}
