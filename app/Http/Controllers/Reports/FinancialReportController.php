<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends Controller
{
    public function generate(Request $request)
    {
        // Validate date range
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $period = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');

        // Get income summary
        $incomeSummary = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('transaction_type', '!=', 'expense')
            ->select(
                DB::raw('SUM(CASE WHEN transaction_type = "tithe" THEN amount ELSE 0 END) as tithe'),
                DB::raw('SUM(CASE WHEN transaction_type = "offering" THEN amount ELSE 0 END) as offering'),
                DB::raw('SUM(CASE WHEN transaction_type = "special_offering" THEN amount ELSE 0 END) as special_offering'),
                DB::raw('SUM(CASE WHEN transaction_type = "building_fund" THEN amount ELSE 0 END) as building_fund')
            )
            ->first();

        // Get expense summary
        $expenses = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('transaction_type', 'expense')
            ->join('transaction_categories', 'transactions.category_id', '=', 'transaction_categories.id')
            ->select('transaction_categories.name as category', DB::raw('SUM(amount) as amount'))
            ->groupBy('category_id', 'transaction_categories.name')
            ->get();

        // Calculate totals
        $totalIncome = $incomeSummary->tithe + $incomeSummary->offering +
                      $incomeSummary->special_offering + $incomeSummary->building_fund;
        $totalExpenses = $expenses->sum('amount');

        $summary = [
            'tithe' => $incomeSummary->tithe,
            'offering' => $incomeSummary->offering,
            'special_offering' => $incomeSummary->special_offering,
            'building_fund' => $incomeSummary->building_fund,
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_position' => $totalIncome - $totalExpenses
        ];

        $pdf = Pdf::loadView('reports.financial-report', [
            'period' => $period,
            'summary' => $summary,
            'expenses' => $expenses
        ]);

        return $pdf->download("financial-report-{$startDate->format('Y-m-d')}-to-{$endDate->format('Y-m-d')}.pdf");
    }

    public function generateTitheReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $period = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');

        // Get tithe transactions with member details
        $tithes = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('transaction_type', 'tithe')
            ->with('member')
            ->orderBy('transaction_date')
            ->get();

        // Calculate statistics
        $stats = [
            'total_tithers' => $tithes->pluck('member_id')->unique()->count(),
            'average_tithe' => $tithes->avg('amount'),
            'highest_tithe' => $tithes->max('amount'),
            'common_payment_method' => $tithes->groupBy('payment_method')
                ->map->count()
                ->sort()
                ->keys()
                ->last()
        ];

        $total_tithes = $tithes->sum('amount');

        $pdf = Pdf::loadView('reports.tithe-report', [
            'period' => $period,
            'tithes' => $tithes,
            'total_tithes' => $total_tithes,
            'stats' => $stats
        ]);

        return $pdf->download("tithe-report-{$startDate->format('Y-m-d')}-to-{$endDate->format('Y-m-d')}.pdf");
    }

    public function generateOfferingReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $period = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');

        // Get offerings by type
        $offerings_by_type = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('transaction_type', 'offering')
            ->join('transaction_categories', 'transactions.category_id', '=', 'transaction_categories.id')
            ->select(
                'transaction_categories.name',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('category_id', 'transaction_categories.name')
            ->get();

        $total_offerings = $offerings_by_type->sum('total');
        $total_count = $offerings_by_type->sum('count');

        // Calculate percentages
        $offerings_by_type->transform(function($item) use ($total_offerings) {
            $item->percentage = ($item->total / $total_offerings) * 100;
            return $item;
        });

        // Get daily summary
        $daily_summary = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('transaction_type', 'offering')
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                'transaction_type as service_type',
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date', 'transaction_type')
            ->orderBy('date')
            ->get();

        $pdf = Pdf::loadView('reports.offering-report', [
            'period' => $period,
            'offerings_by_type' => $offerings_by_type,
            'total_offerings' => $total_offerings,
            'total_count' => $total_count,
            'daily_summary' => $daily_summary
        ]);

        return $pdf->download("offering-report-{$startDate->format('Y-m-d')}-to-{$endDate->format('Y-m-d')}.pdf");
    }
}
