<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Agrega el tipo de notificación para recordatorios de vacunas.
     * notification_type_id = 5 es usado por el comando vaccines:check-reminders
     */
    public function up()
    {
        // Verificar si ya existe para evitar duplicados
        $exists = DB::table('notification_types')->where('id', 5)->exists();

        if (!$exists) {
            DB::table('notification_types')->insert([
                'id'         => 5,
                'type'       => 'Vaccine Reminder',
                'is_deleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        DB::table('notification_types')->where('id', 5)->delete();
    }
};
