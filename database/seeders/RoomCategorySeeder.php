<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomCategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('rooms_category')->insert([
            ['name' => 'General Ward'],
            ['name' => 'Semi-Private Room'],
            ['name' => 'Private Room'],
            ['name' => 'ICU'],
        ]);
    }
}
