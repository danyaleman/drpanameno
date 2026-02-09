<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            $table->text('pathological_history')->nullable()->after('photo');
            $table->text('non_pathological_history')->nullable()->after('pathological_history');
            $table->text('medications_allergies')->nullable()->after('non_pathological_history');

        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            $table->dropColumn([
                'pathological_history',
                'non_pathological_history',
                'medications_allergies',
            ]);

        });
    }
};