<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlyIncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Income Trends';

    protected static ?string $pollingInterval = '30s';

    protected int | string | array $columnSpan = 2;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Get last 6 months of data
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i));
        }

        $monthlyData = $months->map(function ($month) {
            $monthData = Transaction::whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->where('transaction_type', '!=', 'expense')
                ->selectRaw('
                    SUM(CASE WHEN transaction_type = "tithe" THEN amount ELSE 0 END) as tithes,
                    SUM(CASE WHEN transaction_type = "offering" THEN amount ELSE 0 END) as offerings,
                    SUM(CASE WHEN transaction_type = "special_offering" THEN amount ELSE 0 END) as special_offerings,
                    SUM(CASE WHEN transaction_type = "building_fund" THEN amount ELSE 0 END) as building_fund
                ')
                ->first();

            return [
                'month' => $month->format('M Y'),
                'tithes' => $monthData->tithes ?? 0,
                'offerings' => $monthData->offerings ?? 0,
                'special_offerings' => $monthData->special_offerings ?? 0,
                'building_fund' => $monthData->building_fund ?? 0,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Tithes',
                    'data' => $monthlyData->pluck('tithes')->toArray(),
                    'backgroundColor' => 'rgba(1, 30, 183, 0.7)',
                    'borderColor' => 'rgba(1, 30, 183, 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                ],
                [
                    'label' => 'Offerings',
                    'data' => $monthlyData->pluck('offerings')->toArray(),
                    'backgroundColor' => 'rgba(224, 176, 65, 0.7)',
                    'borderColor' => 'rgba(224, 176, 65, 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                ],
                [
                    'label' => 'Special Offerings',
                    'data' => $monthlyData->pluck('special_offerings')->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.7)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                ],
                [
                    'label' => 'Building Fund',
                    'data' => $monthlyData->pluck('building_fund')->toArray(),
                    'backgroundColor' => 'rgba(239, 68, 68, 0.7)',
                    'borderColor' => 'rgba(239, 68, 68, 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                ],
            ],
            'labels' => $monthlyData->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "ZMW " + value.toLocaleString(); }',
                    ],
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }
}
