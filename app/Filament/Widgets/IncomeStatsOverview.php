<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Income;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IncomeStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $currentUser = Auth::user();
        $userBranch = $currentUser->branch_id ?? null;

        return [
            $this->getWeeklyIncomeStat($userBranch),
            $this->getMonthlyIncomeStat($userBranch),
            $this->getYearlyIncomeStat($userBranch),
            $this->getAverageContributionStat($userBranch),
            $this->getTotalContributorsStat($userBranch),
            $this->getTopOfferingTypeStat($userBranch),
        ];
    }

    protected function getWeeklyIncomeStat(?int $branchId): Stat
    {
        $currentWeek = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount');

        $previousWeek = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereBetween('date', [
                now()->subWeek()->startOfWeek(),
                now()->subWeek()->endOfWeek()
            ])
            ->sum('amount');

        $growth = $previousWeek > 0
            ? (($currentWeek - $previousWeek) / $previousWeek) * 100
            : ($currentWeek > 0 ? 100 : 0);

        return Stat::make('This Week', 'K' . number_format($currentWeek, 2))
            ->description($growth >= 0 ? 'Increase' : 'Decrease')
            ->descriptionIcon($growth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($growth >= 0 ? 'success' : 'danger')
            ->chart($this->getWeeklyChart($branchId))
            ->extraAttributes([
                'class' => 'cursor-pointer',
                'wire:click' => '$dispatch("filterByWeek")'
            ]);
    }

    protected function getMonthlyIncomeStat(?int $branchId): Stat
    {
        $currentMonth = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $previousMonth = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereMonth('date', now()->subMonth()->month)
            ->whereYear('date', now()->subMonth()->year)
            ->sum('amount');

        $growth = $previousMonth > 0
            ? (($currentMonth - $previousMonth) / $previousMonth) * 100
            : ($currentMonth > 0 ? 100 : 0);

        return Stat::make('This Month', 'K' . number_format($currentMonth, 2))
            ->description(abs(round($growth, 1)) . '% from last month')
            ->descriptionIcon($growth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($growth >= 0 ? 'success' : 'danger')
            ->chart($this->getMonthlyChart($branchId));
    }

    protected function getYearlyIncomeStat(?int $branchId): Stat
    {
        $currentYear = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereYear('date', now()->year)
            ->sum('amount');

        $previousYear = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereYear('date', now()->subYear()->year)
            ->sum('amount');

        $growth = $previousYear > 0
            ? (($currentYear - $previousYear) / $previousYear) * 100
            : ($currentYear > 0 ? 100 : 0);

        return Stat::make('This Year', 'K' . number_format($currentYear, 2))
            ->description('Year-to-date total')
            ->descriptionIcon('heroicon-m-calendar-days')
            ->color('primary');
    }

    protected function getAverageContributionStat(?int $branchId): Stat
    {
        $thisMonth = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year);

        $totalAmount = $thisMonth->sum('amount');
        $totalCount = $thisMonth->count();
        $average = $totalCount > 0 ? $totalAmount / $totalCount : 0;

        return Stat::make('Avg Contribution', 'K' . number_format($average, 2))
            ->description('This month average')
            ->descriptionIcon('heroicon-m-calculator')
            ->color('warning');
    }

    protected function getTotalContributorsStat(?int $branchId): Stat
    {
        $uniqueContributors = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->whereNotNull('member_id')
            ->distinct('member_id')
            ->count();

        $totalContributions = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();

        return Stat::make('Contributors', $uniqueContributors)
            ->description($totalContributions . ' total contributions')
            ->descriptionIcon('heroicon-m-users')
            ->color('info');
    }

    protected function getTopOfferingTypeStat(?int $branchId): Stat
    {
        $topType = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->with('offeringType')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->selectRaw('offering_type_id, SUM(amount) as total')
            ->groupBy('offering_type_id')
            ->orderByDesc('total')
            ->first();

        $typeName = $topType?->offeringType?->name ?? 'None';
        $amount = $topType ? 'K' . number_format($topType->total, 2) : 'K0.00';

        return Stat::make('Top Category', $typeName)
            ->description($amount . ' this month')
            ->descriptionIcon('heroicon-m-trophy')
            ->color('success');
    }

    protected function getWeeklyChart(?int $branchId): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $amount = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                ->whereDate('date', $date)
                ->sum('amount');
            $data[] = (float) $amount;
        }
        return $data;
    }

    protected function getMonthlyChart(?int $branchId): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $amount = Income::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                ->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
            $data[] = (float) $amount;
        }
        return $data;
    }

    public static function canView(): bool
    {
        return true; // Add your permission logic here if needed
    }
}
