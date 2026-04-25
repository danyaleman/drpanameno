<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'appointment_for',
        'patient_id',
        'appointment_with',
        'appointment_date',
        'appointment_time',
        'booked_by',
        'status',
        'is_telemedicine',
        'appointment_type',
        'is_deleted',
    ];

    /**
     * Paciente de la cita
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    /**
     * Usuario que reservó la cita
     */
    public function bookedBy()
    {
        return $this->hasOne(User::class, 'id', 'booked_by');
    }

    /**
     * Doctor de la cita
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'id', 'appointment_with');
    }

    public function receptionlist_doctor()
    {
        return $this->hasMany(ReceptionListDoctor::class, 'doctor_id', 'appointment_with');
    }

    public function timeSlot()
    {
        return $this->hasOne(DoctorAvailableSlot::class, 'id', 'available_slot');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class)->where('payment_status', 'Paid');
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class)->where('is_deleted', 0);
    }

    public function teleconsultation()
    {
        return $this->hasOne(Teleconsultation::class, 'appointment_id', 'id');
    }
}
