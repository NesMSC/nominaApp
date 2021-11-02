<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = [
        'nombre',
    ];
    function empleado(){
        return $this->hasMany('App\Empleado');
    }
    
}
