<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Esquema de dosis para una vacuna del catálogo.
 * Define cuántos días después de la dosis anterior se aplica esta dosis.
 *
 * @property int    $id
 * @property int    $vaccine_catalog_id
 * @property int    $dose_number
 * @property string $dose_label
 * @property int    $days_after_previous
 * @property string $notes
 */
class VaccineSchedule extends Model
{
    protected $table = 'vaccine_schedules';

    protected $fillable = [
        'vaccine_catalog_id',
        'dose_number',
        'dose_label',
        'days_after_previous',
        'notes',
    ];

    protected $casts = [
        'dose_number'         => 'integer',
        'days_after_previous' => 'integer',
    ];

    /* =====================
     |  RELACIONES
     ===================== */

    /**
     * Vacuna a la que pertenece este esquema.
     */
    public function vaccine()
    {
        return $this->belongsTo(VaccineCatalog::class, 'vaccine_catalog_id');
    }

    /**
     * Registros de vacunación que usaron este esquema de dosis.
     */
    public function records()
    {
        return $this->hasMany(VaccineRecord::class, 'vaccine_schedule_id');
    }
}
