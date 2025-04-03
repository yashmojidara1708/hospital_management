<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Check if the patients table is empty
        if (DB::table('patients')->count() > 0) {
            echo "Skipping PatientsSeeder, data already exists.\n";
            return;
        }
        // Define default patients
        $patients = [
            [
                'name' => 'Test Patient',
                'age' => 30,
                'address' => '123 Test St',
                'country' => 1,
                'city' => 1,
                'state' => 1,
                'zip' => '123456',
                'phone' => '123-456-7890',
                'email' => 'test@gmail.com',
                'last_visit' => '2025-03-24',
            ],
        ];

        foreach ($patients as $patient) {
            // Check if patient already exists
            $existingPatient = DB::table('Patients')
                ->where('email', $patient['email'])
                ->first();

            if (!$existingPatient) {
                // Insert if not exists
                DB::table('Patients')->insert($patient);
                echo "Inserted: " . $patient['name'] . "\n";
            } else {
                echo "Patient already exists: " . $patient['name'] . "\n";
            }
        }

        echo "Patients seeding completed.\n";
    }
}
