<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega los campos de facturación a la tabla prescriptions:
 *  - tipo_consulta_id  → FK a tipo_consultas
 *  - codigo_id         → FK a codigos
 *  - precio_consulta   → precio editable por el médico (decimal)
 */
class AddBillingFieldsToPrescriptionsTable extends Migration
{
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_consulta_id')->nullable()->after('appointment_id');
            $table->unsignedBigInteger('codigo_id')->nullable()->after('tipo_consulta_id');
            $table->decimal('precio_consulta', 10, 2)->nullable()->after('codigo_id');

            $table->foreign('tipo_consulta_id')->references('id')->on('tipo_consultas')->nullOnDelete();
            $table->foreign('codigo_id')->references('id')->on('codigos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['tipo_consulta_id']);
            $table->dropForeign(['codigo_id']);
            $table->dropColumn(['tipo_consulta_id', 'codigo_id', 'precio_consulta']);
        });
    }
}
