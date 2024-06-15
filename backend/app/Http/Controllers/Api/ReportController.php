<?php

namespace App\Http\Controllers\api;

use App\Models\MaintenancePayment;
use App\Models\Expense;
use App\Models\Report;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function annual()
    {
        $currentYear = Carbon::now()->year;

        $monthlyIncomes = MaintenancePayment::getMonthlyIncomesForYear($currentYear);
        $yearlyIncome = MaintenancePayment::getYearIncomeTotal($currentYear);

        $monthlyExpenses = Expense::getMonthlyExpensesForYear($currentYear);
        $yearlyExpense = Expense::getYearExpenseTotal($currentYear);

        return response([
            'success' => true,
            'message' => 'Report tahunan',
            'data' => [
                'monthly_incomes' => $monthlyIncomes,
                'yearly_income' => $yearlyIncome,
                'monthly_expenses' => $monthlyExpenses,
                'yearly_expense' => $yearlyExpense,
            ],
        ]);
    }

    public function pdf($reportId, $month = null)
    {
        $report = '';
        $params = [];

        if ($reportId == 1) {
            $reportData = Report::getSummaryPerMonthReport();

            $report = $reportData['report'];
            $params = $reportData['params'];
        } else if ($reportId == 2) {
            $reportData = Report::getTransactionsPerMonthReport($month);

            $report = $reportData['report'];
            $params = $reportData['params'];
        }

        $pdf = PDF::loadView('reports/' . $report, $params);
        $pdf->setPaper('a4', 'potrait');

        $pdfContent = $pdf->download($report . '.pdf');

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $report . '.pdf' . '"');
    }
}
