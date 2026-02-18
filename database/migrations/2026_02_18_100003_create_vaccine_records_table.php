<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registro transaccional de vacunación por paciente.
     *
     * NOTA: El proyecto usa MyISAM engine (no soporta FK constraints).
     * Las relaciones se manejan a nivel de aplicación (Eloquent).
     *
     * Estados posibles:
     *   - 'applied'  : La dosis ya fue aplicada
     *   - 'pending'  : Dosis programada (calculada automáticamente por Observer)
     *   - 'cancelled': Dosis cancelada
     */
    public function up()
    {
        Schema::create('vaccine_records', function (Blueprint $table) {
            $table->id();

            // Relaciones (sin FK constraints por compatibilidad con MyISAM)
            $table->unsignedBigInteger('patient_id')->index();
            $table->unsignedBigInteger('prescription_id')->nullable()->index();
            $table->unsignedBigInteger('vaccine_catalog_id')->index();
            $table->unsignedBigInteger('vaccine_schedule_id')->nullable()->index();

            $table->integer('dose_number');               // Número de dosis aplicada/pendiente
            $table->string('dose_label');                 // Etiqueta de la dosis
            $table->date('applied_date')->nullable();     // Fecha real de aplicación (null si pending)
            $table->date('scheduled_date')->nullable()->index(); // Fecha programada (calculada por Carbon)

            $table->enum('status', ['applied', 'pending', 'cancelled'])->default('pending')->index();

            $table->string('lot_number')->nullable();     // Número de lote de la vacuna
            $table->string('applied_by')->nullable();     // Nombre del profesional que aplicó
            $table->text('notes')->nullable();            // Observaciones

            // Para el sistema de recordatorios: evitar enviar múltiples notificaciones
            $table->boolean('reminder_sent')->default(false)->index();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vaccine_records');
    }
};
