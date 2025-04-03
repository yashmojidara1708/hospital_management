<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Check if the medicines table is empty
        if (DB::table('medicines')->count() > 0) {
            echo "Skipping MedicineSeeder, data already exists.\n";
            return;
        }
        // Define default medicines
        $medicines = [
            [
                'name' => 'Paracetamol',
                'price' => 10.50,
                'stock' => 100,
                'expiry_date' => '2025-12-31',
                'isdeleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ibuprofen',
                'price' => 12.00,
                'stock' => 200,
                'expiry_date' => '2025-11-30',
                'isdeleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Amoxicillin',
                'price' => 15.00,
                'stock' => 150,
                'expiry_date' => '2025-09-15',
                'isdeleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ciprofloxacin',
                'price' => 20.00,
                'stock' => 75,
                'expiry_date' => '2026-03-01',
                'isdeleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lisinopril',
                'price' => 8.75,
                'stock' => 120,
                'expiry_date' => '2025-05-10',
                'isdeleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($medicines as $medicine) {
            // Check if medicine already exists
            $existingMedicine = DB::table('medicines')
                ->where('name', $medicine['name'])
                ->first();

            if (!$existingMedicine) {
                // Insert if not exists
                DB::table('medicines')->insert($medicine);
                echo "Inserted: " . $medicine['name'] . "\n"; // Log the insertion
            } else {
                echo "Medicine already exists: " . $medicine['name'] . "\n"; // Log if it exists
            }
        }

        echo "Medicines seeding completed.\n";
    }
}
