<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateAppointmentForToPatientId extends Migration
{
    /**
     * Run the migrations.
     * Copia appointment_for -> patient_id buscando por email
     */
    public function up()
    {
        // Paso 1: Para appointments que tengan appointment_for pero NO patient_id
        // Buscamos el email del usuario y lo matcheamos con el email del paciente
        DB::statement("
            UPDATE appointments a
            INNER JOIN users u ON a.appointment_for = u.id
            INNER JOIN patients p ON u.email = p.email
            SET a.patient_id = p.id
            WHERE a.patient_id IS NULL 
            AND a.appointment_for IS NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No hacer nada en reverse - los datos ya están copiados
    }
}

