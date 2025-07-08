<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Church Income Management Scheduled Tasks

        // Send payment due reminders every Tuesday and Friday at 9 AM
        $schedule->command('pledges:send-reminders --type=payment_due')
                 ->twiceWeekly(2, 5, '09:00')
                 ->withoutOverlapping()
                 ->description('Send payment due reminders to pledgers');

        // Send overdue reminders every Monday at 10 AM
        $schedule->command('pledges:send-reminders --type=overdue')
                 ->weeklyOn(1, '10:00')
                 ->withoutOverlapping()
                 ->description('Send overdue payment reminders');

        // Send monthly progress updates on the 1st of each month at 8 AM
        $schedule->command('pledges:send-reminders --type=progress_update')
                 ->monthlyOn(1, '08:00')
                 ->withoutOverlapping()
                 ->description('Send monthly pledge progress updates');

        // Update project completion status daily at midnight
        $schedule->call(function () {
            // Update all projects' current amounts and completion status
            \App\Models\Project::active()->each(function ($project) {
                $project->updateCurrentAmount();
            });
        })->daily()->description('Update project completion status');

        // Check and update pledge statuses daily at 1 AM
        $schedule->call(function () {
            // Auto-complete pledges that have reached their target
            \App\Models\Pledge::active()
                ->whereColumn('received_amount', '>=', 'total_amount')
                ->update(['status' => 'completed']);
        })->dailyAt('01:00')->description('Update pledge completion status');

        // Weekly income summary for admins
        $schedule->call(function () {
            \Illuminate\Support\Facades\Log::info('Weekly income summary generated', [
                'week_total' => \App\Models\Income::whereBetween('date', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->sum('amount'),
                'week' => now()->weekOfYear,
                'year' => now()->year
            ]);
        })->weeklyOn(1, '06:00')->description('Generate weekly income summary');

        // Database cleanup - remove old temporary files
        $schedule->call(function () {
            $tempDir = storage_path('app/temp');
            if (is_dir($tempDir)) {
                $files = glob($tempDir . '/*');
                foreach ($files as $file) {
                    if (is_file($file) && filemtime($file) < strtotime('-30 days')) {
                        unlink($file);
                    }
                }
            }
        })->weekly()->description('Clean up old temporary files');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
