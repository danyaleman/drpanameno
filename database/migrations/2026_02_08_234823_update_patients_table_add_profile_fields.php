<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');

            $table->string('phone_primary')->nullable();
            $table->string('phone_secondary')->nullable();

            $table->string('email')->nullable()->index();

            $table->string('dui')->nullable()->unique();

            $table->enum('marital_status', [
                'soltero',
                'casado',
                'divorciado',
                'viudo'
            ])->nullable();

            $table->string('occupation')->nullable();
            $table->string('workplace')->nullable();

            $table->date('birth_date')->nullable();

            $table->string('referred_by')->nullable();

            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            $table->string('photo')->nullable();

            // user_id debe permitir null por ahora
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            $table->dropColumn([
                'first_name',
                'last_name',
                'phone_primary',
                'phone_secondary',
                'email',
                'dui',
                'marital_status',
                'occupation',
                'workplace',
                'birth_date',
                'referred_by',
                'emergency_contact_name',
                'emergency_contact_phone',
                'photo',
            ]);
        });
    }
};