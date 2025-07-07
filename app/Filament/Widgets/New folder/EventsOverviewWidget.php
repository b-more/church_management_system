<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class EventsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        // Get upcoming events (next 30 days)
        $upcomingEvents = Event::where('start_date', '>=', now())
            ->where('start_date', '<=', now()->addDays(30))
            ->where('status', '!=', 'Cancelled')
            ->count();

        // Get events this week
        $thisWeekEvents = Event::whereBetween('start_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->where('status', '!=', 'Cancelled')
            ->count();

        // Get events requiring action (no organizer or coordinator)
        $eventsNeedingAttention = Event::where('start_date', '>=', now())
            ->where(function($query) {
                $query->whereNull('organizer_id')
                      ->orWhereNull('coordinator_id')
                      ->orWhereNull('budget');
            })
            ->count();

        // Get total event budget this month
        $monthlyEventBudget = Event::whereMonth('start_date', now()->month)
            ->whereYear('start_date', now()->year)
            ->sum('budget');

        return [
            Stat::make('Upcoming Events', $upcomingEvents)
                ->description('Next 30 days')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('This Week', $thisWeekEvents)
                ->description('Events this week')
                ->descriptionIcon('heroicon-m-calendar')
                ->color($thisWeekEvents > 0 ? 'warning' : 'success'),

            Stat::make('Need Attention', $eventsNeedingAttention)
                ->description('Missing details')
                ->descriptionIcon($eventsNeedingAttention > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($eventsNeedingAttention > 0 ? 'danger' : 'success'),

            Stat::make('Monthly Budget', 'ZMW ' . number_format($monthlyEventBudget, 0))
                ->description('This month\'s events')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}
