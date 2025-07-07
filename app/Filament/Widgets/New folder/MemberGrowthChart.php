<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MemberGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'Member Growth Over Time';

    protected static ?string $pollingInterval = '60s';

    protected int | string | array $columnSpan = 1;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Get last 12 months of member growth
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(now()->subMonths($i));
        }

        $monthlyGrowth = $months->map(function ($month) {
            // Count new members joined this month - Fixed date handling
            $newMembers = Member::whereMonth('membership_date', $month->month)
                ->whereYear('membership_date', $month->year)
                ->count();

            // Count total active members by end of this month - Fixed date comparison
            $endOfMonth = $month->copy()->endOfMonth();
            $totalMembers = Member::where('membership_date', '<=', $endOfMonth)
                ->where('is_active', true)
                ->count();

            return [
                'month' => $month->format('M Y'),
                'new_members' => $newMembers,
                'total_members' => $totalMembers,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'New Members',
                    'data' => $monthlyGrowth->pluck('new_members')->toArray(),
                    'backgroundColor' => 'rgba(224, 176, 65, 0.8)',
                    'borderColor' => 'rgba(224, 176, 65, 1)',
                    'borderWidth' => 2,
                    'type' => 'bar',
                ],
                [
                    'label' => 'Total Members',
                    'data' => $monthlyGrowth->pluck('total_members')->toArray(),
                    'backgroundColor' => 'rgba(1, 30, 183, 0.2)',
                    'borderColor' => 'rgba(1, 30, 183, 1)',
                    'borderWidth' => 3,
                    'type' => 'line',
                    'fill' => false,
                ],
            ],
            'labels' => $monthlyGrowth->pluck('month')->toArray(),
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
                        'stepSize' => 5,
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
