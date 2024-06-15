<?php

namespace App\Models;

use App\Models\MaintenancePayment;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    public static function getSummaryPerMonthReport()
    {
        Carbon::setLocale('id');
        $currentYear = Carbon::now()->year;
        $months = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->translatedFormat('F');

            array_push($months, $monthName);
        }

        $monthlyIncomes = MaintenancePayment::getMonthlyIncomesForYear($currentYear);
        $monthlyExpenses = Expense::getMonthlyExpensesForYear($currentYear);

        $summaryPerMonths = [];
        $balanceTotal = 0;

        foreach ($months as $index => $month) {
            $income = $monthlyIncomes[$index]['total'];
            $expense = $monthlyExpenses[$index]['total'];
            $balance = intval($income) - intval($expense);
            $balanceTotal += $balance;

            $summaryPerMonths[] = [
                'monthName' => $month,
                'income' => $income,
                'expense' => $expense,
                'balance' => $balance,
            ];
        }

        $report = 'summary-per-month';
        $params = [
            'title' => strtoupper('Rukun Tetangga'),
            'subtitle' => 'Laporan Rangkuman Transaksi Bulanan Tahun ' . $currentYear,
            'summaryPerMonths' => $summaryPerMonths,
            'balanceTotal' => $balanceTotal,
        ];

        return [
            'report' => $report,
            'params' => $params,
        ];
    }

    public static function getTransactionsPerMonthReport($month)
    {
        Carbon::setLocale('id');
        $currentYear = Carbon::now()->year;

        $expenses = Expense::select('id', 'description', 'amount')->whereMonth('date', $month)
            ->whereYear('date', $currentYear)
            ->get();

        $maintenancePayments = MaintenancePayment::with('residentHouse.house', 'residentHouse.resident', 'maintenanceType')
            ->whereMonth('paid_at', $month)
            ->whereYear('paid_at', $currentYear)
            ->where('is_paid', true)
            ->get();

        $newExpenses = $expenses->map(function ($expense) {
            return (object) [
                'id' => $expense->id,
                'type' => 'Pengeluaran',
                'description' => $expense->description,
                'amount' => $expense->amount,
            ];
        });

        $newMaintenancePayments = $maintenancePayments->map(function ($payment) {
            $description = 'Iuran ' . $payment->maintenanceType->name . ' '
                . $payment->residentHouse->resident->name . ' rumah no. '
                . $payment->residentHouse->house->house_number . ' bulan '
                . $payment->month;

            return (object) [
                'id' => $payment->id,
                'type' => 'Pendapatan',
                'description' => $description,
                'amount' => $payment->maintenanceType->fee,
            ];
        });

        $totalExpenses = $newExpenses->sum('amount');
        $totalIncomes = $newMaintenancePayments->sum('amount');

        $transactions = $newExpenses->merge($newMaintenancePayments);

        return [
            'report' => 'monthly-transactions',
            'params' => [
                'title' => strtoupper('Rukun Tetangga'),
                'subtitle' => 'Laporan Transaksi Bulan ' . Carbon::create()->month($month)->translatedFormat('F') . ' ' . $currentYear,
                'monthlyTransactions' => $transactions,
                'balanceTotal' => intval($totalIncomes) - intval($totalExpenses),
            ],
        ];
    }
}
