<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('codigos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->decimal('precio', 10, 2);
            $table->boolean('estado')->default(true);
            $table->foreignId('tipo_consulta_id')
                  ->constrained('tipo_consultas')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codigos');
    }
};