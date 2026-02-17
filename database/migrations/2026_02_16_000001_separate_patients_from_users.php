<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Esta migración completa la separación de Pacientes y Usuarios
     */
    public function up()
    {
        // 1. Actualizar tabla appointments: agregar patient_id
        if (!Schema::hasColumn('appointments', 'patient_id')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->unsignedBigInteger('patient_id')->nullable()->after('appointment_for');
            });
        }

        // 2. Actualizar tabla prescriptions: agregar patient_id si no existe
        if (!Schema::hasColumn('prescriptions', 'patient_id')) {
            Schema::table('prescriptions', function (Blueprint $table) {
                $table->unsignedBigInteger('patient_id')->nullable()->after('id');
            });
        }

        // 3. Actualizar tabla medical_infos: cambiar user_id por patient_id
        if (Schema::hasColumn('medical_infos', 'user_id') && !Schema::hasColumn('medical_infos', 'patient_id')) {
            Schema::table('medical_infos', function (Blueprint $table) {
                $table->unsignedBigInteger('patient_id')->nullable()->after('id');
            });
        }

        // 4. Las invoices ya tienen patient_id, solo validamos
        
        // 5. Limpiar datos: remover referencias a user_id de tabla patients
        // Ya se hizo en migración anterior
        
        // 6. Remover foreign keys antiguos que referencian users
        try {
            Schema::table('appointments', function (Blueprint $table) {
                // Intentar soltar la FK anterior si existe
                $table->dropForeign(['appointment_for']);
            });
        } catch (\Exception $e) {
            // Ignorar si ya no existe
        }
    }

    public function down()
    {
        // Eliminar columnas agregadas
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'patient_id')) {
                $table->dropColumn('patient_id');
            }
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            if (Schema::hasColumn('prescriptions', 'patient_id')) {
                $table->dropColumn('patient_id');
            }
        });

        Schema::table('medical_infos', function (Blueprint $table) {
            if (Schema::hasColumn('medical_infos', 'patient_id')) {
                $table->dropColumn('patient_id');
            }
        });
    }
};
