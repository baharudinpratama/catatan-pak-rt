<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\MaintenancePayment;
use App\Models\MaintenanceType;
use App\Models\ResidentHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaintenancePaymentController extends Controller
{
    public function index()
    {
        $payments = MaintenancePayment::with('residentHouse.resident', 'residentHouse.house', 'maintenanceType')
            ->orderBy('created_at', 'desc')
            ->get();

        return response(json_encode([
            'success' => true,
            'message' => 'Data tagihan iuran',
            'data' => $payments,
        ]));
    }

    public function store()
    {
        $residentHouses = ResidentHouse::all();
        $maintenanceTypes = MaintenanceType::all();
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        foreach ($residentHouses as $residentHouse) {
            foreach ($maintenanceTypes as $maintenanceType) {
                $existingPayment = MaintenancePayment::where('resident_house_id', $residentHouse->id)
                    ->where('maintenance_type_id', $maintenanceType->id)
                    ->where('year', $currentYear)
                    ->where('month', $currentMonth)
                    ->first();

                if (!$existingPayment) {
                    MaintenancePayment::create([
                        'resident_house_id' => $residentHouse->id,
                        'maintenance_type_id' => $maintenanceType->id,
                        'amount' => $maintenanceType->fee,
                        'year' => $currentYear,
                        'month' => $currentMonth,
                        'is_paid' => false,
                        'paid_at' => null,
                    ]);
                }
            }
        }

        return response(json_encode([
            'success' => true,
            'message' => 'Tambah tagihan berhasil',
            'data' => null,
        ]));
    }

    public function show($id)
    {
        $payment = MaintenancePayment::where('id', $id)->with('residentHouse.resident', 'residentHouse.house', 'maintenanceType')->first();

        return response(json_encode([
            'success' => true,
            'message' => 'Detail tagihan iuran',
            'data' => $payment,
        ]));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'months_to_pay' => 'required',
            'paid_at' => 'required|date',
        ], [
            'months_to_pay.required' => 'Mohon input banyak bulan',
            'paid_at.required' => 'Mohon input tanggal transaksi',
            'paid_at.date' => 'Tanggal tidak dikenali',
        ]);

        if ($validator->fails()) {
            return response(json_encode([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ]), 422);
        }

        $validatedRequest = $validator->validated();

        $payment = MaintenancePayment::where('id', $id)
            ->with('residentHouse.resident', 'residentHouse.house', 'maintenanceType')
            ->first();

        $maintenanceTypeId = $payment->maintenance_type_id;
        $residentHouseId = $payment->resident_house_id;
        $amount = $payment->amount;
        $startYear = $payment->year;
        $startMonth = $payment->month;
        $monthsToPay = $validatedRequest['months_to_pay'];
        $paidAt = $validatedRequest['paid_at'];

        $currentYear = $startYear;
        $currentMonth = $startMonth;

        for ($i = 0; $i < $monthsToPay; $i++) {
            MaintenancePayment::updateOrCreate(
                [
                    'resident_house_id' => $residentHouseId,
                    'maintenance_type_id' => $maintenanceTypeId,
                    'year' => $currentYear,
                    'month' => $currentMonth
                ],
                [
                    'amount' => $amount,
                    'is_paid' => true,
                    'paid_at' => $paidAt,
                ]
            );

            $currentMonth++;

            if ($currentMonth > 12) {
                $currentMonth = $currentMonth - 12;
                $currentYear++;
            }
        }

        return response(json_encode([
            'success' => true,
            'message' => 'Berhasil membayar iuran',
            'data' => $payment,
        ]));
    }
}
