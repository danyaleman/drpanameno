<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Signos;

class Prescription extends Model
{
    protected $table = 'prescriptions'; 

    protected $fillable = [
        'patient_id',
        'symptoms',
        'diagnosis',
        'prescription_date',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    /**
     * Doctor que creó la prescripción
     */
    public function doctor()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    /**
     * Paciente de la prescripción
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    /**
     * Cita relacionada
     */
    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'id', 'appointment_id');
    }

    /**
     * Signos vitales relacionados
     */
    public function signos()
    {
        return $this->hasOne(Signos::class, 'prescription_id', 'id');
    }

    /**
     * Archivos relacionados
     */
    public function archivos()
    {
        return $this->hasMany(Archivo::class, 'prescription_id');
    }

    /**
     * Vacunas relacionadas
     */
    public function vacunas()
    {
    return $this->hasMany(Vacuna::class, 'prescription_id');
    }
}
