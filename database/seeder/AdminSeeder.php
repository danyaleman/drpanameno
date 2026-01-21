<?php

// namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin seed
        $credentials = [
            'first_name' => 'Doctorly',
            'last_name'  => 'Admin',
            'mobile'     => '5142323114',
            'profile_photo'=>'avatar-5.jpg',
            'email'      => 'admin@themesbrand.website',
            'password' => 'admin@123456',
            'last_login' => now(),
        ];
        $user = Sentinel::registerAndActivate( $credentials );
        $role = Sentinel::findRoleBySlug('admin');
        $role->users()->attach($user);

        // accountant
        $acc_credentials = [
            'first_name' => 'Accountant',
            'last_name'  => 'Doctorly',
            'mobile'     => '5142323114',
            'profile_photo' => 'avatar-5.jpg',
            'email'      => 'accountant@themesbrand.website',
            'password' => 'accountant@123456',
            'last_login' => now(),
        ];
        $accountant = Sentinel::registerAndActivate($acc_credentials);
        $acc_role = Sentinel::findRoleBySlug('accountant');
        $acc_role->users()->attach($accountant);
    }
}
