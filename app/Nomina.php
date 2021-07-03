<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    public function pago(){
    	return $this->hasMany('App\Pago');
    }
}
