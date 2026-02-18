<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Catálogo de vacunas disponibles en la clínica.
     * Ejemplo: COVID-19, Tétanos, Hepatitis B, Influenza, etc.
     */
    public function up()
    {
        Schema::create('vaccine_catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('name');                        // Nombre de la vacuna (ej: "COVID-19 Pfizer")
            $table->string('code')->unique()->nullable();  // Código interno (ej: "COV-PFZ")
            $table->text('description')->nullable();       // Descripción / indicaciones
            $table->string('manufacturer')->nullable();    // Fabricante
            $table->integer('total_doses')->default(1);   // Total de dosis que requiere el esquema
            $table->boolean('is_active')->default(true);  // Para desactivar sin borrar
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vaccine_catalogs');
    }
};
