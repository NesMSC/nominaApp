<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
    ];

    public function cuentasBancaria(){
        return belongsToMany('App/CuentasBancaria.php');
    }
}
