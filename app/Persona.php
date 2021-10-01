<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'nombres',
        'apellidos',
        'cedula',
        'correo',
        'telefono',
        'nacimiento'
    ];

    public function empleado(){
        return $this->hasOne('App\Empleado');
    }
    public function user(){
        return $this->hasOne('App\User');
    }
    public function cuentaBancaria(){
        return $this->hasOne('App\CuentasBancaria');
    }
}
