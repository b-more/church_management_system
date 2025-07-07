<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\CellGroup;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class MembershipOverviewStats extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        // Get current month stats
        $currentMonthMembers = Member::whereMonth('membership_date', now()->month)
            ->whereYear('membership_date', now()->year)
            ->count();

        // Get last month stats for comparison
        $lastMonthMembers = Member::whereMonth('membership_date', now()->subMonth()->month)
            ->whereYear('membership_date', now()->subMonth()->year)
            ->count();

        // Calculate growth percentage
        $memberGrowth = $lastMonthMembers > 0
            ? round((($currentMonthMembers - $lastMonthMembers) / $lastMonthMembers) * 100, 1)
            : ($currentMonthMembers > 0 ? 100 : 0);

        // Get total active members
        $totalActiveMembers = Member::where('is_active', true)->count();

        // Get members by status distribution
        $membersByStatus = Member::where('is_active', true)
            ->select('membership_status', DB::raw('count(*) as count'))
            ->groupBy('membership_status')
            ->pluck('count', 'membership_status')
            ->toArray();

        // Get cell groups count
        $activeCellGroups = CellGroup::where('status', 'Active')->count();

        return [
            Stat::make('Total Active Members', number_format($totalActiveMembers))
                ->description($memberGrowth > 0 ? "+{$memberGrowth}% this month" : "{$memberGrowth}% this month")
                ->descriptionIcon($memberGrowth > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($memberGrowth > 0 ? 'success' : ($memberGrowth < 0 ? 'danger' : 'warning'))
                ->chart([65, 61, 66, 62, 63, 68, 70]),

            Stat::make('New Members', $currentMonthMembers)
                ->description('This month')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info')
                ->chart([3, 5, 4, 6, 3, 5, 4]),

            Stat::make('Leaders', $membersByStatus['Leader'] ?? 0)
                ->description('Active leadership')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('Cell Groups', $activeCellGroups)
                ->description('Active groups')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
        ];
    }
}
