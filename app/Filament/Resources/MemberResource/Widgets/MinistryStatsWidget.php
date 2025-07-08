<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MinistryStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $ministryStats = Member::getMinistryStats();

        return [
            Stat::make('Pastors', $ministryStats['pastors'])
                ->description('Church pastors')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Intercessors', $ministryStats['intercessors'])
                ->description('Prayer warriors')
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color('info'),

            Stat::make('Worship Leaders', $ministryStats['worship_leaders'])
                ->description('Leading worship')
                ->descriptionIcon('heroicon-m-musical-note')
                ->color('warning'),

            Stat::make('Ushers', $ministryStats['ushers'])
                ->description('Serving as ushers')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Sunday School Teachers', $ministryStats['sunday_school_teachers'])
                ->description('Teaching Sunday school')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Offering/Exhortation Leaders', $ministryStats['offering_exhortation_leaders'])
                ->description('Leading offerings & exhortations')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('danger'),

            Stat::make('Pulpit Ministry Eligible', $ministryStats['pulpit_ministry_eligible'])
                ->description('Eligible for pulpit ministry')
                ->descriptionIcon('heroicon-m-microphone')
                ->color('gray'),

            Stat::make('Total in Ministry', $ministryStats['total_with_ministry'])
                ->description('Members with ministry roles')
                ->descriptionIcon('heroicon-m-star')
                ->color('purple'),
        ];
    }

    protected function getColumns(): int
    {
        return 4; // Show 4 stats per row
    }
}
