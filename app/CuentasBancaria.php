<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Banco;
class CuentasBancaria extends Model
{
    protected $fillable = [
        'tipo_cuenta',
        'numero_cuenta',
    ];

    public function persona(){
        return $this->belongsTo('App\Persona');
    }

    public function banco(){
        return $this->belongsTo('App\Banco');
    }
}
