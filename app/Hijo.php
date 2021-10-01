<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hijo extends Model
{
    public $timestamps = false;

    function empleado(){
        return $this->belongsToMany('App\Empleado');
    }
}
