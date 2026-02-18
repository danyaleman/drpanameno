<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Esquema de dosis para cada vacuna del catálogo.
     * Define el intervalo de tiempo entre dosis consecutivas.
     *
     * Ejemplo para COVID-19 (3 dosis):
     *  - Dosis 1: dose_number=1, days_after_previous=0  (primera dosis, sin espera)
     *  - Dosis 2: dose_number=2, days_after_previous=21 (21 días después de la dosis 1)
     *  - Dosis 3: dose_number=3, days_after_previous=180 (6 meses después de la dosis 2)
     */
    public function up()
    {
        Schema::create('vaccine_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaccine_catalog_id')
                  ->constrained('vaccine_catalogs')
                  ->onDelete('cascade');

            $table->integer('dose_number');                // Número de dosis (1, 2, 3...)
            $table->string('dose_label');                  // Etiqueta (ej: "Dosis 1", "Refuerzo")
            $table->integer('days_after_previous')->default(0);
                // Días de espera desde la dosis anterior.
                // Para la dosis 1 siempre es 0.
            $table->text('notes')->nullable();             // Notas adicionales para esta dosis
            $table->timestamps();

            // Una vacuna no puede tener dos registros con el mismo número de dosis
            $table->unique(['vaccine_catalog_id', 'dose_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vaccine_schedules');
    }
};
