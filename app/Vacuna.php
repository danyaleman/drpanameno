<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
     protected $table = 'vacunas';

    protected $fillable = [
        'prescription_id',
        'tipo',
        'dosis',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
