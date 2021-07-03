<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salario extends Model
{
    protected $fillable = [
        'tabulador',
        'indicador_id'
    ];

    public function empleado(){
        return $this->hasMany('App\Empleado');
    }

    public function indEconomico(){
        return $this->belongsTo('App\Ind_economico');
    }
}
