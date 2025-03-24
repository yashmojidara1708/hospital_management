<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define default roles
        $roles = [
            ['name' => 'Admin', 'status' => 1, 'isdeleted' => 0]
        ];

        foreach ($roles as $role) {
            // Check if role already exists
            $existingRole = DB::table('roles')->where('name', $role['name'])->first();

            if (!$existingRole) {
                // Insert if not exists
                DB::table('roles')->insert([
                    'name' => $role['name'],
                    'status' => $role['status'],
                    'isdeleted' => $role['isdeleted'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                echo "Inserted: " . $role['name'] . "\n"; 
            } else {
                echo "Role already exists: " . $role['name'] . "\n";
            }
        }

        echo "Roles seeding completed.\n";
    }
}
