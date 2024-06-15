<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenancePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_house_id',
        'maintenance_type_id',
        'amount',
        'year',
        'month',
        'is_paid',
        'paid_at',
    ];

    protected $appends = ['is_paid_text'];

    protected function isPaidText(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->is_paid == 1 ?
                    'Sudah Bayar' : 'Belum Bayar';
            },
        );
    }

    public function residentHouse(): BelongsTo
    {
        return $this->belongsTo(ResidentHouse::class, 'resident_house_id');
    }

    public function maintenanceType(): BelongsTo
    {
        return $this->belongsTo(MaintenanceType::class, 'maintenance_type_id');
    }

    public static function getMonthlyIncomesForYear($year)
    {
        $allMonths = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthlyIncomes = MaintenancePayment::selectRaw('SUM(amount) as total')
                ->whereYear('paid_at', $year)
                ->whereMonth('paid_at', $month)
                ->first();

            $total = $monthlyIncomes->total ? intval($monthlyIncomes->total) : 0;

            $allMonths[] = [
                'year' => $year,
                'month' => $month,
                'total' => $total,
            ];
        }

        return $allMonths;
    }

    public static function getYearIncomeTotal($year): int
    {
        return MaintenancePayment::whereYear('paid_at', $year)->sum('amount');
    }
}
