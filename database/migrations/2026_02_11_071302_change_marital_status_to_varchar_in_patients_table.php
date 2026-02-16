<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('marital_status', 50)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->enum('marital_status', ['soltero', 'casado', 'divorciado', 'viudo'])
                  ->nullable()
                  ->change();
        });
    }
};