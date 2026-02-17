<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalInfo extends Model
{
    protected $table = 'medical_infos';

    protected $fillable = [
        'patient_id',
        'height',
        'weight',
        'b_group',
        'b_pressure',
        'pulse',
        'respiration',
        'allergy',
        'diet',
        'is_deleted',
    ];

    /* =====================
     |  RELACIONES
     ===================== */

    /**
     * Paciente dueño de la información médica
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }
}
