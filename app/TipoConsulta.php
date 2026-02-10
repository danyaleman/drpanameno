<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoConsulta extends Model
{
    protected $table = 'tipo_consultas';

    protected $fillable = [
        'nombre',
        'estado',
        'prescription_id',
    ];

    public function codigos()
    {
        return $this->hasMany(Codigo::class);
    }
}