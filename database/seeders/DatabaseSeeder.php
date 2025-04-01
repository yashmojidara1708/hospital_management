<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StaffTableSeeder::class,
            StatesSeeder::class,
            CitySeeder::class,
            CountriesSeeder::class,
            PatientsSeeder::class,
            DoctorSeeder::class,
            RolesSeeder::class,
            MedicineSeeder::class,
            SpecialitySeeder::class,
            AdmittedPatientSeeder::class,
            RoomCategorySeeder::class,
            RoomSeeder::class,
        ]);
    }
}
