<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Codigo extends Model
{
    protected $table = 'codigos';

    protected $fillable = [
        'codigo',
        'precio',
        'estado',
        'tipo_consulta_id',
    ];

    public function tipoConsulta()
    {
        return $this->belongsTo(TipoConsulta::class);
    }
}