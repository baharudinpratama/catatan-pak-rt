<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'date',
    ];

    public static function getMonthlyExpensesForYear($year): array
    {
        $allMonths = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthlyExpenses = Expense::selectRaw('SUM(amount) as total')
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->first();

            $total = $monthlyExpenses->total ? intval($monthlyExpenses->total) : 0;

            $allMonths[] = [
                'year' => $year,
                'month' => $month,
                'total' => $total,
            ];
        }

        return $allMonths;
    }

    public static function getYearExpenseTotal($year): int
    {
        return Expense::whereYear('date', $year)->sum('amount');
    }
}
