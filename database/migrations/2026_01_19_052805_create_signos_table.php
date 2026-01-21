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
        Schema::create('signos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')
          ->constrained('prescriptions')
          ->onDelete('cascade');
            $table->decimal('peso', 5, 2)->nullable();
            $table->decimal('talla', 5, 2)->nullable();
            $table->integer('frec_respiratoria')->nullable();
            $table->integer('presion_arterial_sistolica')->nullable();
            $table->integer('presion_arterial_diastolica')->nullable();
            $table->decimal('temperatura', 4, 2)->nullable();
            $table->integer('frec_cardiaca')->nullable();
            $table->integer('spo')->nullable();
            $table->text('examen')->nullable();
            $table->text('observaciones_adicionales')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signos');
    }
};
