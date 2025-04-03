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
            RolesSeeder::class,
            CountriesSeeder::class,
            StatesSeeder::class,
            CitySeeder::class,
            StaffTableSeeder::class,
            DoctorSeeder::class,
            PatientsSeeder::class,
            MedicineSeeder::class,
            SpecialitySeeder::class,
            AdmittedPatientSeeder::class,
            RoomCategorySeeder::class,
            RoomSeeder::class,
        ]);
    }
}
