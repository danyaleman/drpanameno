<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'patient_id',
        'payment_mode',
        'payment_status',
        'invoice_date',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    /**
     * Detalles de la factura
     */
    public function invoice_detail()
    {
        return $this->hasMany(InvoiceDetail::class)->where('is_deleted', 0);
    }

    /**
     * Paciente de la factura
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    /**
     * Doctor relacionado a la factura
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'id', 'doctor_id');
    }

    /**
     * Cita relacionada
     */
    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'id', 'appointment_id');
    }

    /**
     * Transacción relacionada
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
