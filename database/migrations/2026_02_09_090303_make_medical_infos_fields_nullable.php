<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_infos', function (Blueprint $table) {

            $table->string('height')->nullable()->change();
            $table->string('b_group')->nullable()->change();
            $table->string('pulse')->nullable()->change();
            $table->string('allergy')->nullable()->change();
            $table->string('weight')->nullable()->change();
            $table->string('b_pressure')->nullable()->change();
            $table->string('respiration')->nullable()->change();
            $table->string('diet')->nullable()->change();

        });
    }

    public function down(): void
    {
        Schema::table('medical_infos', function (Blueprint $table) {

            $table->string('height')->nullable(false)->change();
            $table->string('b_group')->nullable(false)->change();
            $table->string('pulse')->nullable(false)->change();
            $table->string('allergy')->nullable(false)->change();
            $table->string('weight')->nullable(false)->change();
            $table->string('b_pressure')->nullable(false)->change();
            $table->string('respiration')->nullable(false)->change();
            $table->string('diet')->nullable(false)->change();

        });
    }
};
