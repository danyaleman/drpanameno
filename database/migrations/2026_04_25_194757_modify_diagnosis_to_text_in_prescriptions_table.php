<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cambia la columna `diagnosis` de la tabla `prescriptions` a tipo TEXT y nullable.
 * Este campo se reutilizará para almacenar la "Historia Clínica" del paciente
 * durante la consulta, sin agregar nuevas columnas a la base de datos.
 */
class ModifyDiagnosisToTextInPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->text('diagnosis')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('diagnosis', 255)->nullable()->change();
        });
    }
}
