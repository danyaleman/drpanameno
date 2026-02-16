<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {

            // address ahora puede ser NULL
            $table->string('address')->nullable()->change();

            // Si dui tenía índice único, primero se elimina
            if (Schema::hasColumn('patients', 'dui')) {
                $table->dropUnique(['dui']);
                $table->string('dui')->nullable()->change();
            }
        });
    }

    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {

            // address vuelve a NOT NULL
            $table->string('address')->nullable(false)->change();

            // dui vuelve a NOT NULL y UNIQUE
            $table->string('dui')->nullable(false)->unique()->change();
        });
    }
};