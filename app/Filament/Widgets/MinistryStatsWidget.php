<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MinistryStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pastors', Member::where('is_pastor', true)->count())
                ->description('Church pastors')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Intercessors', Member::where('is_intercessor', true)->count())
                ->description('Prayer warriors')
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color('info'),

            Stat::make('Worship Leaders', Member::where('is_worship_leader', true)->count())
                ->description('Leading worship')
                ->descriptionIcon('heroicon-m-musical-note')
                ->color('warning'),

            Stat::make('Total in Ministry', Member::where(function($query) {
                $query->where('is_pastor', true)
                      ->orWhere('is_intercessor', true)
                      ->orWhere('is_usher', true)
                      ->orWhere('is_worship_leader', true)
                      ->orWhere('is_sunday_school_teacher', true)
                      ->orWhere('is_offering_exhortation_leader', true)
                      ->orWhere('is_eligible_for_pulpit_ministry', true);
            })->count())
                ->description('Members with ministry roles')
                ->descriptionIcon('heroicon-m-star')
                ->color('purple'),
        ];
    }
}
