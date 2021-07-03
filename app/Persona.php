<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
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
}
