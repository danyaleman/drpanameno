<?php

// namespace Database\Seeders;

use App\Departments;
use Illuminate\Database\Seeder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hospitalDepartments = [
            [
                'name' => 'Cardiology',
                'description' => 'Specializing in heart health and cardiovascular care.',
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Focused on the health and well-being of children and infants.',
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Dealing with musculoskeletal conditions and injuries.',
            ],
            [
                'name' => 'Oncology',
                'description' => 'Devoted to the treatment of cancer and oncological care.',
            ],
            [
                'name' => 'Neurology',
                'description' => 'Specializing in the nervous system and neurological disorders.',
            ],
            [
                'name' => 'Gynecology',
                'description' => 'Concentrating on women\'s reproductive health and well-being.',
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Managing skin-related conditions and dermatological care.',
            ],
            [
                'name' => 'Urology',
                'description' => 'Addressing urinary tract and urological health concerns.',
            ],
            [
                'name' => 'ENT (Ear, Nose, and Throat)',
                'description' => 'Focusing on disorders related to the ears, nose, and throat.',
            ],
            [
                'name' => 'Psychiatry',
                'description' => 'Providing mental health care and psychological services.',
            ],
        ];

        foreach ($hospitalDepartments as $key => $value) {
            Departments::create($value);
        }
          
    }
}
