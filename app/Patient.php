<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Patient extends Model
{
    protected $table = 'patients';

    protected $fillable = [
        // Datos personales
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'email',
        'address',
        'dui',

        // Contactos
        'phone_primary',
        'phone_secondary',

        // Información social
        'marital_status',
        'occupation',
        'workplace',
        'referred_by',

        // Emergencia
        'emergency_contact_name',
        'emergency_contact_phone',

        // Antecedentes clínicos
        'pathological_history',
        'non_pathological_history',
        'medications_allergies',

        // Foto
        'photo',

        'is_deleted',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    /* =====================
     |  RELACIONES
     ===================== */

    /**
     * Citas del paciente
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'id');
    }

    /**
     * Información médica del paciente
     */
    public function medicalInfo()
    {
        return $this->hasOne(MedicalInfo::class, 'patient_id', 'id');
    }

    /**
     * Recetas del paciente
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id', 'id');
    }

    /**
     * Facturas/Invoices del paciente
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'patient_id', 'id');
    }

    /**
     * Registros de vacunación del paciente
     */
    public function vaccineRecords()
    {
        return $this->hasMany(VaccineRecord::class, 'patient_id', 'id');
    }

    /**
     * Vacunas pendientes del paciente
     */
    public function pendingVaccines()
    {
        return $this->hasMany(VaccineRecord::class, 'patient_id', 'id')
                    ->where('status', 'pending')
                    ->orderBy('scheduled_date');
    }

    /* =====================
     |  ACCESORES
     ===================== */

    /**
     * Edad calculada (NO guardada en BD)
     */
    public function getAgeAttribute()
    {
        return $this->birth_date
            ? Carbon::parse($this->birth_date)->age
            : null;
    }

    /**
     * Nombre completo
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
