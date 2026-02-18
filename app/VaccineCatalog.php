<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo maestro de vacunas disponibles en la clínica.
 *
 * @property int    $id
 * @property string $name
 * @property string $code
 * @property string $description
 * @property string $manufacturer
 * @property int    $total_doses
 * @property bool   $is_active
 */
class VaccineCatalog extends Model
{
    protected $table = 'vaccine_catalogs';

    protected $fillable = [
        'name',
        'code',
        'description',
        'manufacturer',
        'total_doses',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'total_doses' => 'integer',
    ];

    /* =====================
     |  RELACIONES
     ===================== */

    /**
     * Esquemas de dosis definidos para esta vacuna.
     * Ordenados por número de dosis ascendente.
     */
    public function schedules()
    {
        return $this->hasMany(VaccineSchedule::class, 'vaccine_catalog_id')
                    ->orderBy('dose_number');
    }

    /**
     * Todos los registros de vacunación de esta vacuna.
     */
    public function records()
    {
        return $this->hasMany(VaccineRecord::class, 'vaccine_catalog_id');
    }

    /* =====================
     |  SCOPES
     ===================== */

    /**
     * Solo vacunas activas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
