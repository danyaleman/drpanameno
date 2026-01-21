<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signos extends Model
{
    protected $table = 'signos';

    protected $fillable = [
        'prescription_id',
        'peso',
        'talla',
        'frec_respiratoria',
        'presion_arterial_sistolica',
        'presion_arterial_diastolica',
        'temperatura',
        'frec_cardiaca',
        'spo',
        'examen',
        'observaciones_adicionales',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
