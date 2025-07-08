<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Income Trends';

    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '60s';

    public ?string $filter = 'monthly';

    protected function getData(): array
    {
        $currentUser = Auth::user();
        $userBranch = $currentUser->branch_id ?? null;

        return match ($this->filter) {
            'daily' => $this->getDailyData($userBranch),
            'weekly' => $this->getWeeklyData($userBranch),
            'monthly' => $this->getMonthlyData($userBranch),
            'yearly' => $this->getYearlyData($userBranch),
            default => $this->getMonthlyData($userBranch),
        };
    }

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Last 30 Days',
            'weekly' => 'Last 12 Weeks',
            'monthly' => 'Last 12 Months',
            'yearly' => 'Last 5 Years',
        ];
    }

    protected function getDailyData(?int $branchId): array
    {
        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M j');

            $amount = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                ->whereDate('date', $date)
                ->sum('amount');
            $data[] = (float) $amount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Daily Income',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getWeeklyData(?int $branchId): array
    {
        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();

            $labels[] = $startOfWeek->format('M j') . ' - ' . $endOfWeek->format('j');

            $amount = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->sum('amount');
            $data[] = (float) $amount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Weekly Income',
                    'data' => $data,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getMonthlyData(?int $branchId): array
    {
        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');

            $amount = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                ->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
            $data[] = (float) $amount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Income',
                    'data' => $data,
                    'backgroundColor' => 'rgba(168, 85, 247, 0.1)',
                    'borderColor' => 'rgb(168, 85, 247)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getYearlyData(?int $branchId): array
    {
        $labels = [];
        $data = [];

        for ($i = 4; $i >= 0; $i--) {
            $year = now()->subYears($i)->year;
            $labels[] = (string) $year;

            $amount = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                ->whereYear('date', $year)
                ->sum('amount');
            $data[] = (float) $amount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Yearly Income',
                    'data' => $data,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public static function canView(): bool
    {
        return true; // Add your permission logic here if needed
    }
}
