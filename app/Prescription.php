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
    function doctor(){
        return $this->hasOne(User::class,'id','created_by');
    }
    function patient(){
        return $this->hasOne(User::class,'id','patient_id');
    }
    function appointment(){
        return $this->hasOne(Appointment::class,'id','appointment_id');
    }
    // 🔹 NUEVA RELACIÓN: SIGNOS VITALES
    function signos()
    {
        return $this->hasOne(Signos::class, 'prescription_id', 'id');
    }
    public function archivos()
    {
    return $this->hasMany(Archivo::class, 'prescription_id');
    }
    public function vacunas()
    {
    return $this->hasMany(Vacuna::class, 'prescription_id');
    }
}
