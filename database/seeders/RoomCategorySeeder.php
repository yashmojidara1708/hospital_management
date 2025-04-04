<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomCategorySeeder extends Seeder
{
    public function run()
    {
        // Check if the rooms_category table is empty
        if (DB::table('rooms_category')->count() > 0) {
            echo "Skipping RoomCategorySeeder, data already exists.\n";
            return;
        }

        DB::table('rooms_category')->insert([
            ['name' => 'General Ward'],
            ['name' => 'Semi-Private Room'],
            ['name' => 'Private Room'],
            ['name' => 'ICU'],
        ]);
    }
}
