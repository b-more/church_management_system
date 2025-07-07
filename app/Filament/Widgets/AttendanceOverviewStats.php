<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceRecord;
use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AttendanceOverviewStats extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        // Get today's attendance
        $todayAttendance = AttendanceRecord::whereDate('check_in_time', today())->count();

        // Get last Sunday's attendance for comparison
        $lastSunday = now()->previous('Sunday');
        $lastSundayAttendance = AttendanceRecord::whereDate('check_in_time', $lastSunday)->count();

        // Calculate attendance change
        $attendanceChange = $lastSundayAttendance > 0
            ? round((($todayAttendance - $lastSundayAttendance) / $lastSundayAttendance) * 100, 1)
            : ($todayAttendance > 0 ? 100 : 0);

        // Get this month's average attendance - FIXED QUERY
        $monthlyAttendanceData = AttendanceRecord::whereMonth('check_in_time', now()->month)
            ->whereYear('check_in_time', now()->year)
            ->select(DB::raw('DATE(check_in_time) as date'), DB::raw('COUNT(*) as daily_count'))
            ->groupBy(DB::raw('DATE(check_in_time)'))
            ->get();

        $monthlyAvg = $monthlyAttendanceData->avg('daily_count') ?? 0;

        // Get visitors this week
        $weeklyVisitors = AttendanceRecord::whereBetween('check_in_time', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->whereIn('attendance_type', ['Visitor', 'First Timer'])
            ->count();

        // Get follow-ups required
        $followUpsRequired = AttendanceRecord::where('follow_up_required', true)
            ->whereNull('follow_up_notes')
            ->count();

        return [
            Stat::make('Today\'s Attendance', $todayAttendance)
                ->description($attendanceChange > 0 ? "+{$attendanceChange}% vs last Sunday" : "{$attendanceChange}% vs last Sunday")
                ->descriptionIcon($attendanceChange > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($attendanceChange > 0 ? 'success' : 'danger')
                ->chart([45, 52, 48, 61, 55, 58, $todayAttendance]),

            Stat::make('Monthly Average', round($monthlyAvg))
                ->description('Average attendance')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Weekly Visitors', $weeklyVisitors)
                ->description('New & returning')
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color('warning'),

            Stat::make('Follow-ups Pending', $followUpsRequired)
                ->description('Requires attention')
                ->descriptionIcon($followUpsRequired > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($followUpsRequired > 0 ? 'danger' : 'success'),
        ];
    }
}
