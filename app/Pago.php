<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    public $timestamps = false;

    public function empleado(){
        return $this->belongsTo('App\Empleado');
    }

    public function nomina(){
    	return $this->belongsTo('App\Nomina', 'id_nomina');
    }
}
