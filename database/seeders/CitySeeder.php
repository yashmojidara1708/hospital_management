<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cities')->delete();
        $cities = [
            // Andaman and Nicobar Islands (state_id = 1)
            ['name' => 'Port Blair', 'state_id' => 1, 'country_id' => 101],

            // Andhra Pradesh (state_id = 2)
            ['name' => 'Visakhapatnam', 'state_id' => 2, 'country_id' => 101],
            ['name' => 'Vijayawada', 'state_id' => 2, 'country_id' => 101],
            ['name' => 'Guntur', 'state_id' => 2, 'country_id' => 101],

            // Arunachal Pradesh (state_id = 3)
            ['name' => 'Itanagar', 'state_id' => 3, 'country_id' => 101],

            // Assam (state_id = 4)
            ['name' => 'Guwahati', 'state_id' => 4, 'country_id' => 101],
            ['name' => 'Silchar', 'state_id' => 4, 'country_id' => 101],

            // Bihar (state_id = 5)
            ['name' => 'Patna', 'state_id' => 5, 'country_id' => 101],
            ['name' => 'Gaya', 'state_id' => 5, 'country_id' => 101],

            // Chandigarh (state_id = 6)
            ['name' => 'Chandigarh', 'state_id' => 6, 'country_id' => 101],

            // Chhattisgarh (state_id = 7)
            ['name' => 'Raipur', 'state_id' => 7, 'country_id' => 101],
            ['name' => 'Bhilai', 'state_id' => 7, 'country_id' => 101],

            // Dadra and Nagar Haveli (state_id = 8)
            ['name' => 'Silvassa', 'state_id' => 8, 'country_id' => 101],

            // Daman and Diu (state_id = 9)
            ['name' => 'Daman', 'state_id' => 9, 'country_id' => 101],
            ['name' => 'Diu', 'state_id' => 9, 'country_id' => 101],

            // Delhi (state_id = 10)
            ['name' => 'New Delhi', 'state_id' => 10, 'country_id' => 101],

            // Goa (state_id = 11)
            ['name' => 'Panaji', 'state_id' => 11, 'country_id' => 101],
            ['name' => 'Margao', 'state_id' => 11, 'country_id' => 101],

            // Gujarat (state_id = 12)
            ['name' => 'Ahmedabad', 'state_id' => 12, 'country_id' => 101],
            ['name' => 'Surat', 'state_id' => 12, 'country_id' => 101],

            // Haryana (state_id = 13)
            ['name' => 'Faridabad', 'state_id' => 13, 'country_id' => 101],
            ['name' => 'Gurgaon', 'state_id' => 13, 'country_id' => 101],

            // Himachal Pradesh (state_id = 14)
            ['name' => 'Shimla', 'state_id' => 14, 'country_id' => 101],

            // Jammu and Kashmir (state_id = 15)
            ['name' => 'Srinagar', 'state_id' => 15, 'country_id' => 101],
            ['name' => 'Jammu', 'state_id' => 15, 'country_id' => 101],

            // Jharkhand (state_id = 16)
            ['name' => 'Ranchi', 'state_id' => 16, 'country_id' => 101],

            // Karnataka (state_id = 17)
            ['name' => 'Bangalore', 'state_id' => 17, 'country_id' => 101],

            // Kerala (state_id = 19)
            ['name' => 'Kochi', 'state_id' => 19, 'country_id' => 101],

            // Lakshadweep (state_id = 20)
            ['name' => 'Kavaratti', 'state_id' => 20, 'country_id' => 101],

            // Madhya Pradesh (state_id = 21)
            ['name' => 'Bhopal', 'state_id' => 21, 'country_id' => 101],
            ['name' => 'Indore', 'state_id' => 21, 'country_id' => 101],

            // Maharashtra (state_id = 22)
            ['name' => 'Mumbai', 'state_id' => 22, 'country_id' => 101],
            ['name' => 'Pune', 'state_id' => 22, 'country_id' => 101],

            // Manipur (state_id = 23)
            ['name' => 'Imphal', 'state_id' => 23, 'country_id' => 101],

            // Meghalaya (state_id = 24)
            ['name' => 'Shillong', 'state_id' => 24, 'country_id' => 101],

            // Mizoram (state_id = 25)
            ['name' => 'Aizawl', 'state_id' => 25, 'country_id' => 101],

            // Nagaland (state_id = 26)
            ['name' => 'Kohima', 'state_id' => 26, 'country_id' => 101],

            // Odisha (state_id = 29)
            ['name' => 'Bhubaneswar', 'state_id' => 29, 'country_id' => 101],

            // Punjab (state_id = 32)
            ['name' => 'Ludhiana', 'state_id' => 32, 'country_id' => 101],

            // Rajasthan (state_id = 33)
            ['name' => 'Jaipur', 'state_id' => 33, 'country_id' => 101],

            // Sikkim (state_id = 34)
            ['name' => 'Gangtok', 'state_id' => 34, 'country_id' => 101],

            // Tamil Nadu (state_id = 35)
            ['name' => 'Chennai', 'state_id' => 35, 'country_id' => 101],

            // Telangana (state_id = 36)
            ['name' => 'Hyderabad', 'state_id' => 36, 'country_id' => 101],

            // Tripura (state_id = 37)
            ['name' => 'Agartala', 'state_id' => 37, 'country_id' => 101],

            // Uttar Pradesh (state_id = 38)
            ['name' => 'Lucknow', 'state_id' => 38, 'country_id' => 101],

            // Uttarakhand (state_id = 39)
            ['name' => 'Dehradun', 'state_id' => 39, 'country_id' => 101],

            // West Bengal (state_id = 41)
            ['name' => 'Kolkata', 'state_id' => 41, 'country_id' => 101],
        ];

        DB::table('cities')->insert($cities);
    }
}
