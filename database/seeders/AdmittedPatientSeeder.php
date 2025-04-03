<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdmittedPatientSeeder extends Seeder
{
    public function run()
    {
        $patients = DB::table('patients')->pluck('id')->toArray();
        $doctors = DB::table('doctors')->pluck('id')->toArray();
        $rooms = DB::table('rooms')->pluck('id')->toArray();

        if (empty($patients) || empty($doctors) || empty($rooms)) {
            return; // Avoid seeding if related tables are empty
        }

        if (DB::table('admitted_patients')->count() > 0) {
            echo "Skipping AdmittedPatientSeeder, data already exists.\n";
            return;
        }

        DB::table('admitted_patients')->insert([
            [
                'patient_id' => $patients[array_rand($patients)],
                'doctor_id' => $doctors[array_rand($doctors)],
                'room_id' => $rooms[array_rand($rooms)],
                'admit_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'discharge_date' => null,
                'admission_reason' => 'Severe fever and dehydration',
                'status' => 1,
                'isdeleted' => 0,
            ],
            [
                'patient_id' => $patients[array_rand($patients)],
                'doctor_id' => $doctors[array_rand($doctors)],
                'room_id' => $rooms[array_rand($rooms)],
                'admit_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'discharge_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'admission_reason' => 'Surgery recovery',
                'status' => 2,
                'isdeleted' => 0,
            ],
        ]);
    }
}
