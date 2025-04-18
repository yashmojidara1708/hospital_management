<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if the staff table is empty
        if (DB::table('staff')->count() > 0) {
            echo "Skipping StaffTableSeeder, data already exists.\n";
            return;
        }
        $adminRole = DB::table('roles')->where('name', 'Admin')->first();
        $adminRoleId = $adminRole ? $adminRole->id : 1;

        $existingStaff = DB::table('staff')
            ->where('email', 'admin@hms.com')
            ->first();

        if ($existingStaff && $existingStaff->isdeleted == 1) {
            DB::table('staff')
                ->where('email', 'admin@hms.com')
                ->update([
                    'name' => 'Global Admin',
                    'roles' => json_encode([$adminRoleId]),
                    'date_of_birth' => '1990-01-01',
                    'phone' => '1234567890',
                    'password' => Hash::make('Admin@123'),
                    'address' => '123 Admin St, Admin City',
                    'country' => '101',
                    'state' => '12',
                    'city' => '19',
                    'zip' => '123456',
                    'isdeleted' => 0,
                    'updated_at' => now(),
                ]);
            echo "Existing record updated successfully.\n";
        } else {
            DB::table('staff')->insert([
                'name' => 'Global Admin',
                'email' => 'admin@hms.com',
                'roles' => json_encode([$adminRoleId]),
                'date_of_birth' => '1990-01-01',
                'phone' => '1234567890',
                'password' => Hash::make('Admin@123'),
                'address' => '123 Admin St, Admin City',
                'country' => '101',
                'state' => '12',
                'city' => '19',
                'zip' => '123456',
                'isdeleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "New record created successfully.\n";
        }
    }
}
