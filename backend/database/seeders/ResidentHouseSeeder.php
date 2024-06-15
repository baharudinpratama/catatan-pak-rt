<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\MaintenancePayment;
use App\Models\MaintenanceType;
use App\Models\Resident;
use App\Models\ResidentHouse;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResidentHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $houses = House::all();
        $residents = Resident::all();
        $maintenanceTypes = MaintenanceType::all();
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        foreach ($houses as $house) {
            $numberOfResidents = rand(1, 2);
            $assignedResidents = $residents->random($numberOfResidents);

            foreach ($assignedResidents as $resident) {
                $residentHouse = ResidentHouse::create([
                    'house_id' => $house->id,
                    'resident_id' => $resident->id,
                    'residential_status_id' => 1,
                ]);

                for ($i = 1; $i <= $currentMonth - 1; $i++) {
                    foreach ($maintenanceTypes as $maintenanceType) {
                        MaintenancePayment::create([
                            'resident_house_id' => $residentHouse->id,
                            'maintenance_type_id' => $maintenanceType->id,
                            'amount' => $maintenanceType->fee,
                            'year' => $currentYear,
                            'month' => $i,
                            'is_paid' => true,
                            'paid_at' => Carbon::createFromDate($currentYear, $i, 10, 'Asia/Jakarta'),
                        ]);
                    }
                }

                $residents = $residents->filter(function ($r) use ($resident) {
                    return $r->id != $resident->id;
                });
            }
        }
    }
}
