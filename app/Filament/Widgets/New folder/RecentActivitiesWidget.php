<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\Transaction;
use App\Models\AttendanceRecord;
use App\Models\Event;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class RecentActivitiesWidget extends Widget
{
    protected static string $view = 'filament.widgets.recent-activities';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '30s';

    public function getRecentActivities(): Collection
    {
        $activities = collect();

        // Get recent members (last 7 days)
        $recentMembers = Member::where('created_at', '>=', now()->subDays(7))
            ->with('branch')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($member) {
                return [
                    'type' => 'member',
                    'title' => 'New Member Registered',
                    'description' => 'test', //"{$member->first_name} {$member->last_name} joined {$member->branch?->name ?? 'the church'}",
                    'time' => $member->created_at,
                    'icon' => 'heroicon-o-user-plus',
                    'color' => 'success',
                ];
            });

        // Get recent transactions (last 7 days)
        $recentTransactions = Transaction::where('created_at', '>=', now()->subDays(7))
            ->with('member')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($transaction) {
                $typeLabel = match($transaction->transaction_type) {
                    'tithe' => 'Tithe',
                    'offering' => 'Offering',
                    'special_offering' => 'Special Offering',
                    'building_fund' => 'Building Fund',
                    'expense' => 'Expense',
                    default => 'Transaction',
                };

                return [
                    'type' => 'transaction',
                    'title' => "{$typeLabel} Recorded",
                    'description' => "ZMW " . number_format($transaction->amount, 2) .
                        ($transaction->member ? " from {$transaction->member->first_name} {$transaction->member->last_name}" : ""),
                    'time' => $transaction->created_at,
                    'icon' => $transaction->transaction_type === 'expense' ? 'heroicon-o-arrow-up-circle' : 'heroicon-o-arrow-down-circle',
                    'color' => $transaction->transaction_type === 'expense' ? 'danger' : 'success',
                ];
            });

        // Get recent attendance records (last 3 days)
        $recentAttendance = AttendanceRecord::where('created_at', '>=', now()->subDays(3))
            ->with(['member', 'service'])
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($record) {
                $description = '';
                if ($record->member) {
                    $description = "{$record->member->first_name} {$record->member->last_name} attended";
                } elseif ($record->visitor_name) {
                    $description = "Visitor {$record->visitor_name} attended";
                } else {
                    $description = "Attendance recorded";
                }

                if ($record->service) {
                    $description .= " {$record->service->name}";
                }

                return [
                    'type' => 'attendance',
                    'title' => 'Attendance Recorded',
                    'description' => $description,
                    'time' => $record->created_at,
                    'icon' => 'heroicon-o-clipboard-document-check',
                    'color' => 'info',
                ];
            });

        // Get recent events (created in last 7 days)
        $recentEvents = Event::where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($event) {
                return [
                    'type' => 'event',
                    'title' => 'Event Created',
                    'description' => "{$event->title} scheduled for {$event->start_date->format('M d, Y')}",
                    'time' => $event->created_at,
                    'icon' => 'heroicon-o-calendar-plus',
                    'color' => 'warning',
                ];
            });

        // Combine all activities and sort by time
        $activities = $activities
            ->concat($recentMembers)
            ->concat($recentTransactions)
            ->concat($recentAttendance)
            ->concat($recentEvents)
            ->sortByDesc('time')
            ->take(20)
            ->values();

        return $activities;
    }
}
