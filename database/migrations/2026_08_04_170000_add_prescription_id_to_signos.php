<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agrega prescription_id a la tabla signos para vincular signos vitales
     * por consulta en lugar de por paciente.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('signos', 'prescription_id')) {
            Schema::table('signos', function (Blueprint $table) {
                $table->unsignedBigInteger('prescription_id')->nullable()->after('patient_id');
                $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
            });
        }

        // Migrar datos existentes: para cada registro de signos que solo tiene patient_id,
        // buscar la prescripción más reciente del paciente y asociarla.
        $signosRecords = DB::table('signos')->whereNull('prescription_id')->get();
        foreach ($signosRecords as $signo) {
            $latestPrescription = DB::table('prescriptions')
                ->where('patient_id', $signo->patient_id)
                ->where('is_deleted', 0)
                ->orderBy('id', 'desc')
                ->first();

            if ($latestPrescription) {
                DB::table('signos')
                    ->where('id', $signo->id)
                    ->update(['prescription_id' => $latestPrescription->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signos', function (Blueprint $table) {
            $table->dropForeign(['prescription_id']);
            $table->dropColumn('prescription_id');
        });
    }
};
