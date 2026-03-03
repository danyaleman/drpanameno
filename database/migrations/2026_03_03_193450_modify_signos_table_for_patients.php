<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signos', function (Blueprint $table) {
            // Drop foreign key and column if it exists
            if (Schema::hasColumn('signos', 'prescription_id')) {
                $table->dropForeign(['prescription_id']);
                $table->dropColumn('prescription_id');
            }
            // Add patient_id
            $table->foreignId('patient_id')->nullable()->constrained('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signos', function (Blueprint $table) {
            if (Schema::hasColumn('signos', 'patient_id')) {
                $table->dropForeign(['patient_id']);
                $table->dropColumn('patient_id');
            }
            $table->foreignId('prescription_id')->nullable()->constrained('prescriptions')->onDelete('cascade');
        });
    }
};
