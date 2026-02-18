<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Registro transaccional de vacunación por paciente.
 *
 * Estados posibles:
 *  - 'applied'  : La dosis ya fue aplicada al paciente
 *  - 'pending'  : Dosis programada (calculada automáticamente por el Observer)
 *  - 'cancelled': Dosis cancelada
 *
 * @property int    $id
 * @property int    $patient_id
 * @property int    $prescription_id
 * @property int    $vaccine_catalog_id
 * @property int    $vaccine_schedule_id
 * @property int    $dose_number
 * @property string $dose_label
 * @property \Carbon\Carbon $applied_date
 * @property \Carbon\Carbon $scheduled_date
 * @property string $status
 * @property string $lot_number
 * @property string $applied_by
 * @property string $notes
 * @property bool   $reminder_sent
 */
class VaccineRecord extends Model
{
    protected $table = 'vaccine_records';

    protected $fillable = [
        'patient_id',
        'prescription_id',
        'vaccine_catalog_id',
        'vaccine_schedule_id',
        'dose_number',
        'dose_label',
        'applied_date',
        'scheduled_date',
        'status',
        'lot_number',
        'applied_by',
        'notes',
        'reminder_sent',
    ];

    protected $casts = [
        'applied_date'   => 'date',
        'scheduled_date' => 'date',
        'reminder_sent'  => 'boolean',
        'dose_number'    => 'integer',
    ];

    /* =====================
     |  BOOT - Observer inline
     ===================== */

    /**
     * Al crear un registro con status='applied', el Observer
     * calcula automáticamente la próxima dosis y crea un registro 'pending'.
     */
    protected static function booted()
    {
        static::created(function (VaccineRecord $record) {
            // Solo actuar cuando se aplica una dosis real
            if ($record->status !== 'applied') {
                return;
            }

            // Buscar el esquema de la SIGUIENTE dosis
            $nextSchedule = VaccineSchedule::where('vaccine_catalog_id', $record->vaccine_catalog_id)
                ->where('dose_number', $record->dose_number + 1)
                ->first();

            if (!$nextSchedule) {
                // No hay más dosis en el esquema, el ciclo de vacunación está completo
                return;
            }

            // Calcular la fecha de la próxima dosis usando Carbon
            $appliedDate   = \Carbon\Carbon::parse($record->applied_date);
            $scheduledDate = $appliedDate->copy()->addDays($nextSchedule->days_after_previous);

            // Crear el registro de la próxima dosis como 'pending'
            VaccineRecord::create([
                'patient_id'         => $record->patient_id,
                'prescription_id'    => $record->prescription_id,
                'vaccine_catalog_id' => $record->vaccine_catalog_id,
                'vaccine_schedule_id'=> $nextSchedule->id,
                'dose_number'        => $nextSchedule->dose_number,
                'dose_label'         => $nextSchedule->dose_label,
                'applied_date'       => null,
                'scheduled_date'     => $scheduledDate->toDateString(),
                'status'             => 'pending',
                'reminder_sent'      => false,
            ]);
        });
    }

    /* =====================
     |  RELACIONES
     ===================== */

    /**
     * Paciente al que pertenece este registro.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Prescripción asociada (si se aplicó en consulta).
     */
    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id');
    }

    /**
     * Vacuna del catálogo.
     */
    public function vaccine()
    {
        return $this->belongsTo(VaccineCatalog::class, 'vaccine_catalog_id');
    }

    /**
     * Esquema de dosis específico.
     */
    public function schedule()
    {
        return $this->belongsTo(VaccineSchedule::class, 'vaccine_schedule_id');
    }

    /* =====================
     |  SCOPES
     ===================== */

    /**
     * Solo registros pendientes.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Solo registros aplicados.
     */
    public function scopeApplied($query)
    {
        return $query->where('status', 'applied');
    }

    /**
     * Vacunas pendientes en los próximos N días.
     */
    public function scopeUpcomingInDays($query, int $days = 3)
    {
        return $query->where('status', 'pending')
                     ->whereBetween('scheduled_date', [
                         \Carbon\Carbon::today()->toDateString(),
                         \Carbon\Carbon::today()->addDays($days)->toDateString(),
                     ]);
    }
}
