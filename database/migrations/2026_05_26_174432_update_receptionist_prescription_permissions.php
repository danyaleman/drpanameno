<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateReceptionistPrescriptionPermissions extends Migration
{
    /**
     * Agrega permisos de prescription.delete, prescription.create y prescription.update
     * al rol receptionist.
     */
    public function up()
    {
        $role = DB::table('roles')->where('slug', 'receptionist')->first();

        if ($role) {
            $permissions = json_decode($role->permissions, true) ?? [];

            $permissions['prescription.delete'] = true;
            $permissions['prescription.create'] = true;
            $permissions['prescription.update'] = true;

            DB::table('roles')
                ->where('slug', 'receptionist')
                ->update(['permissions' => json_encode($permissions)]);
        }
    }

    /**
     * Revierte los permisos agregados.
     */
    public function down()
    {
        $role = DB::table('roles')->where('slug', 'receptionist')->first();

        if ($role) {
            $permissions = json_decode($role->permissions, true) ?? [];

            unset($permissions['prescription.delete']);
            unset($permissions['prescription.create']);
            unset($permissions['prescription.update']);

            DB::table('roles')
                ->where('slug', 'receptionist')
                ->update(['permissions' => json_encode($permissions)]);
        }
    }
}
