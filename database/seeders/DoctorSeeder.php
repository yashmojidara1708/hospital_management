<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Ensure specialities exist or seed them if not
        $this->call(SpecialitySeeder::class);

        // Get the specialization ID for the doctor
        $specializationId = DB::table('specialities')
            ->where('name', 'Cardiothoracic Surgery') // Example specialization
            ->value('id');

        // Check if a doctor with a specific email already exists
        $existingDoctor = DB::table('doctors')
            ->where('email', 'dr@gmail.com')
            ->first();

        if ($existingDoctor && $existingDoctor->isdeleted == 1) {
            // Update existing doctor information if marked as deleted
            DB::table('doctors')
                ->where('email', 'dr@gmail.com')
                ->update([
                    'name' => 'Dr. Admin',
                    'role' => 'Surgeon',
                    'specialization' => $specializationId, // Use the ID from the specialities table
                    'phone' => '123-456-7890',
                    'password' => Hash::make('Testing@123'), // Updated password
                    'experience' => 10,
                    'qualification' => 'MD',
                    'address' => '123 Main St',
                    'country' => 'USA',
                    'city' => 'New York',
                    'state' => 'NY',
                    'zip' => '10001',
                    'image' => 'default.jpg', // Optional field
                    'isdeleted' => 0,
                    'updated_at' => now(),
                ]);
            echo "Existing doctor record updated successfully.\n";
        } else if (!$existingDoctor) {
            // Insert new doctor if not exists
            DB::table('doctors')->insert([
                'name' => 'Dr. Admin',
                'role' => 'Surgeon',
                'specialization' => $specializationId, // Use the ID from the specialities table
                'phone' => '123-456-7890',
                'email' => 'dr@gmail.com',
                'password' => Hash::make('Testing@123'), // Updated password
                'experience' => 10,
                'qualification' => 'MD',
                'address' => '123 Main St',
                'country' => 'USA',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '10001',
                'image' => 'default.jpg', // Optional field
                'isdeleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "New doctor record created successfully.\n";
        }
    }
}
