<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class HouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            DB::table('houses')->insert([
                'house_number' => $i,
                'description' => 'Rumah nyaman 2 kamar tidur dekat pusat kota.',
                'house_image' => null,
                'active' => true,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ]);
        }
    }
}
