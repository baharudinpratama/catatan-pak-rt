<?php

namespace Database\Seeders;

use App\Models\Resident;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 30; $i++) {
            Resident::create([
                'name' => $faker->name,
                'id_card_number' => $faker->nik,
                'id_card_image' => null,
                'phone_number' => $faker->phoneNumber,
                'marital_status' => $faker->boolean,
                'active' => $faker->boolean(90),
            ]);
        }
    }
}
