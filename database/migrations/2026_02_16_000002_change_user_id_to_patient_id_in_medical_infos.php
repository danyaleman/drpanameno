<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Migración para cambiar user_id a patient_id en tabla medical_infos
     */
    public function up()
    {
        // 1. Si user_id existe y patient_id NO existe, renombrar
        if (Schema::hasColumn('medical_infos', 'user_id') && !Schema::hasColumn('medical_infos', 'patient_id')) {
            Schema::table('medical_infos', function (Blueprint $table) {
                // Renombrar user_id a patient_id
                $table->renameColumn('user_id', 'patient_id');
            });
        }

        // 2. Si patient_id existe pero no está nullable, hacerlo nullable
        if (Schema::hasColumn('medical_infos', 'patient_id')) {
            DB::statement("ALTER TABLE medical_infos MODIFY COLUMN patient_id BIGINT UNSIGNED NULL");
        }
    }

    public function down()
    {
        // Revertir: cambiar patient_id de vuelta a user_id si es necesario
        if (Schema::hasColumn('medical_infos', 'patient_id') && !Schema::hasColumn('medical_infos', 'user_id')) {
            Schema::table('medical_infos', function (Blueprint $table) {
                $table->renameColumn('patient_id', 'user_id');
            });
        }
    }
};
