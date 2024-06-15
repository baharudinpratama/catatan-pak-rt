<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            HouseSeeder::class,
            ResidentialStatusSeeder::class,
            ResidentSeeder::class,
            MaintenanceTypeSeeder::class,
            ResidentHouseSeeder::class,
            ExpenseSeeder::class,
        ]);
    }
}
