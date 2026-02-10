<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';

    protected $fillable = [
        'prescription_id',
        'estudios_laboratorios',
        'diagnostico',
        'medicamentos',
        'diagnostico_repor',
    ];
}