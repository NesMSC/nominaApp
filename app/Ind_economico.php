<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ind_economico extends Model
{
    protected $fillable = [
        'salarioMin',
        'UnTributaria',
        'gaceta',
        'fecha'
    ];

    public function salario(){
        return $this->hasMany('App\Salario');
    }
}
