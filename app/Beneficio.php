<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beneficio extends Model
{

    protected $fillable = [
        'concepto',
        'tipo_valor',
        'valor'
    ];
    function empleado(){
        return $this->belongsToMany('Empleado');
    }
}
