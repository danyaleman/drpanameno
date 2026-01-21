<?php

use App\LandingSections;
use Database\Seeders\MedicalInfoSeeder;
use Database\Seeders\NotificationTypeSeeder;
use Database\Seeders\DoctorAvailableDaySeeder;
use Database\Seeders\DoctorAvailableSlotSeeder;
use Database\Seeders\DoctorAvailableTimeSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            AdminSeeder::class,
            DoctorSeeder::class,
            PatientSeeder::class,
            ReceptionistSeeder::class,
            MedicalInfoSeeder::class,
            NotificationTypeSeeder::class,
            DoctorAvailableDaySeeder::class,
            DoctorAvailableTimeSeeder::class,
            DoctorAvailableSlotSeeder::class,

        ]);

        // landing sections
        $sections = [
            "Home",
            "Services",
            "Why Us",
            "Features",
            "Department",
            "Team",
            "Feedback",
            "FaQ's",
            "Contact"
        ];

        foreach ($sections as $key => $sec) {
          LandingSections::create(["title" => $sec]);  
        }
    }
}
