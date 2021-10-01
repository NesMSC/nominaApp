<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    public function pagos(){
    	return $this->hasMany('App\Pago', 'id_nomina');
    }
}
