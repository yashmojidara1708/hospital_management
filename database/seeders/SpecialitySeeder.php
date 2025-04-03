<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Check if the specialities table is empty
        if (DB::table('specialities')->count() > 0) {
            echo "Skipping SpecialitySeeder, data already exists.\n";
            return;
        }
        // Define default specialties
        $specialities = [
            ['name' => 'Cardiothoracic Surgery', 'status' => 1],
            ['name' => 'Pediatrics', 'status' => 1],
            ['name' => 'Dermatology', 'status' => 1],
            ['name' => 'Neurology', 'status' => 1],
            ['name' => 'Orthopedic Surgery', 'status' => 1],
            // Add more specialties as needed
        ];

        foreach ($specialities as $speciality) {
            // Check if speciality already exists
            $existingSpeciality = DB::table('specialities')
                ->where('name', $speciality['name'])
                ->first();

            if (!$existingSpeciality) {
                // Insert if not exists
                DB::table('specialities')->insert($speciality);
            }
        }

        echo "Specialities seeded successfully.\n";
    }
}
