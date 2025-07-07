<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceRecord;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AttendanceOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Weekly Attendance Trends';

    protected static ?string $pollingInterval = '30s';

    protected int | string | array $columnSpan = 2;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Get last 12 weeks of attendance data
        $weeks = collect();
        for ($i = 11; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();

            // Fixed query - get the data directly instead of using problematic grouping
            $totalAttendance = AttendanceRecord::whereBetween('check_in_time', [$startOfWeek, $endOfWeek])->count();

            $visitors = AttendanceRecord::whereBetween('check_in_time', [$startOfWeek, $endOfWeek])
                ->whereIn('attendance_type', ['Visitor', 'First Timer'])
                ->count();

            $members = AttendanceRecord::whereBetween('check_in_time', [$startOfWeek, $endOfWeek])
                ->where('attendance_type', 'Regular')
                ->count();

            $weeks->push([
                'week' => $startOfWeek->format('M d'),
                'total' => $totalAttendance,
                'visitors' => $visitors,
                'members' => $members,
            ]);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Attendance',
                    'data' => $weeks->pluck('total')->toArray(),
                    'backgroundColor' => 'rgba(1, 30, 183, 0.8)',
                    'borderColor' => 'rgba(1, 30, 183, 1)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Members',
                    'data' => $weeks->pluck('members')->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Visitors',
                    'data' => $weeks->pluck('visitors')->toArray(),
                    'backgroundColor' => 'rgba(224, 176, 65, 0.8)',
                    'borderColor' => 'rgba(224, 176, 65, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $weeks->pluck('week')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
