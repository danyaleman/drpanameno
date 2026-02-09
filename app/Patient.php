<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patients';

    protected $fillable = [
        'user_id',

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

    function appointment(){
        return $this->hasMany(Appointment::class,'appointment_for','id');
    }
    function user(){
        return $this->belongsTo(User::class)->where('is_deleted', 0);
    }

    /* =====================
     |  ACCESORES
     ===================== */

    // Edad calculada (NO guardada en BD)
    public function getAgeAttribute()
    {
        return $this->birth_date
            ? Carbon::parse($this->birth_date)->age
            : null;
    }
}
