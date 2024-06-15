<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class MaintenanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('maintenance_types')->insert([
            [
                'name' => 'Kebersihan',
                'fee' => 15000,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ], [
                'name' => 'Keamanan',
                'fee' => 100000,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
        ]);
    }
}
