<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run()
    {
        // Check if the rooms table is empty
        if (DB::table('rooms')->count() > 0) {
            echo "Skipping RoomSeeder, data already exists.\n";
            return;
        }
        $categories = DB::table('rooms_category')->pluck('id', 'name');

        DB::table('rooms')->insert([
            ['category_id' => $categories['General Ward'] ?? null, 'room_number' => '101', 'beds' => 5, 'charges' => 2000.00, 'status' => 1],
            ['category_id' => $categories['Semi-Private Room'] ?? null, 'room_number' => '201', 'beds' => 2, 'charges' => 4000.00, 'status' => 1],
            ['category_id' => $categories['Private Room'] ?? null, 'room_number' => '301', 'beds' => 1, 'charges' => 6000.00, 'status' => 1],
            ['category_id' => $categories['ICU'] ?? null, 'room_number' => 'ICU-1', 'beds' => 1, 'charges' => 10000.00, 'status' => 1],
        ]);
    }
}
