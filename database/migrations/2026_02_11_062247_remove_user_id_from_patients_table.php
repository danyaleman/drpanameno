<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {

            // ⚠️ Si existe foreign key, primero se elimina
            if (Schema::hasColumn('patients', 'user_id')) {
                $table->dropColumn('user_id');
            }

        });
    }

    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });
    }
};