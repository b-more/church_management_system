<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pledge;
use App\Notifications\PledgeReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendPledgeReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'pledges:send-reminders
                            {--type=payment_due : Type of reminder (payment_due, overdue, progress_update)}
                            {--dry-run : Run without sending actual SMS}
                            {--branch= : Filter by specific branch ID}';

    /**
     * The console command description.
     */
    protected $description = 'Send SMS reminders to pledgers based on payment schedules and overdue status';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');
        $dryRun = $this->option('dry-run');
        $branchId = $this->option('branch');

        $this->info("Starting pledge reminder process...");
        $this->info("Reminder type: {$type}");
        $this->info("Dry run: " . ($dryRun ? 'Yes' : 'No'));

        if ($branchId) {
            $this->info("Branch filter: {$branchId}");
        }

        try {
            $pledges = $this->getPledgesForReminder($type, $branchId);

            if ($pledges->isEmpty()) {
                $this->info('No pledges found for reminder.');
                return self::SUCCESS;
            }

            $this->info("Found {$pledges->count()} pledges for reminder.");

            $sentCount = 0;
            $failedCount = 0;

            $this->withProgressBar($pledges, function ($pledge) use ($type, $dryRun, &$sentCount, &$failedCount) {
                if ($dryRun) {
                    $this->logReminderAction($pledge, $type, true);
                    $sentCount++;
                } else {
                    $result = $this->sendReminder($pledge, $type);
                    if ($result) {
                        $sentCount++;
                    } else {
                        $failedCount++;
                    }
                }
            });

            $this->newLine(2);
            $this->info("Reminder process completed:");
            $this->info("- Sent: {$sentCount}");
            if (!$dryRun) {
                $this->info("- Failed: {$failedCount}");
            }

            Log::info('Pledge reminders sent', [
                'type' => $type,
                'sent_count' => $sentCount,
                'failed_count' => $failedCount,
                'dry_run' => $dryRun,
                'branch_id' => $branchId,
            ]);

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error sending pledge reminders: " . $e->getMessage());
            Log::error('Error sending pledge reminders', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return self::FAILURE;
        }
    }

    /**
     * Get pledges that need reminders based on type
     */
    protected function getPledgesForReminder(string $type, ?string $branchId = null)
    {
        $query = Pledge::with(['member', 'branch', 'project'])
            ->where('status', 'active')
            ->whereColumn('received_amount', '<', 'total_amount');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        // Filter pledges that have phone numbers
        $query->where(function ($q) {
            $q->whereNotNull('phone_number')
              ->orWhereHas('member', function ($memberQuery) {
                  $memberQuery->whereNotNull('phone');
              });
        });

        return match($type) {
            'payment_due' => $this->getPaymentDuePledges($query),
            'overdue' => $this->getOverduePledges($query),
            'progress_update' => $this->getProgressUpdatePledges($query),
            default => collect([])
        };
    }

    /**
     * Get pledges with payments due soon
     */
    protected function getPaymentDuePledges($query)
    {
        return $query->where(function ($q) {
            // One-time pledges overdue for more than 7 days
            $q->where('frequency', 'one-time')
              ->where('pledge_date', '<=', now()->subDays(7))
              ->whereColumn('received_amount', '<', 'total_amount');
        })->orWhere(function ($q) {
            // Recurring pledges - check if next payment is due within 3 days
            $q->where('frequency', '!=', 'one-time')
              ->where(function ($frequencyQuery) {
                  $this->addFrequencyFilters($frequencyQuery);
              });
        })->get()->filter(function ($pledge) {
            // Additional filter for recurring pledges
            if ($pledge->frequency === 'one-time') {
                return true;
            }

            $nextDue = $pledge->next_payment_due_date;
            if (!$nextDue) {
                return false;
            }

            // Send reminder if payment is due within 3 days
            return $nextDue->diffInDays(now(), false) >= -3 && $nextDue->diffInDays(now(), false) <= 1;
        });
    }

    /**
     * Get overdue pledges
     */
    protected function getOverduePledges($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('target_completion_date')
              ->where('target_completion_date', '<', now());
        })->orWhere(function ($q) {
            // Also include recurring pledges that missed several payments
            $q->where('frequency', '!=', 'one-time')
              ->where(function ($frequencyQuery) {
                  $this->addOverdueFrequencyFilters($frequencyQuery);
              });
        })->get();
    }

    /**
     * Get pledges for progress updates (monthly)
     */
    protected function getProgressUpdatePledges($query)
    {
        // Send progress updates on the 1st of each month for active pledges
        if (now()->day !== 1) {
            return collect([]);
        }

        return $query->where('frequency', '!=', 'one-time')
                    ->where('pledge_date', '<=', now()->subMonth())
                    ->get();
    }

    /**
     * Add frequency-based filters for payment due logic
     */
    protected function addFrequencyFilters($query)
    {
        $now = now();

        $query->where(function ($q) use ($now) {
            // Weekly pledges - due within 3 days of week cycle
            $q->where('frequency', 'weekly')
              ->where('pledge_date', '<=', $now->copy()->subDays(4));
        })->orWhere(function ($q) use ($now) {
            // Bi-weekly pledges
            $q->where('frequency', 'bi-weekly')
              ->where('pledge_date', '<=', $now->copy()->subDays(11));
        })->orWhere(function ($q) use ($now) {
            // Monthly pledges
            $q->where('frequency', 'monthly')
              ->where('pledge_date', '<=', $now->copy()->subDays(27));
        })->orWhere(function ($q) use ($now) {
            // Quarterly pledges
            $q->where('frequency', 'quarterly')
              ->where('pledge_date', '<=', $now->copy()->subDays(87));
        })->orWhere(function ($q) use ($now) {
            // Yearly pledges
            $q->where('frequency', 'yearly')
              ->where('pledge_date', '<=', $now->copy()->subDays(360));
        });
    }

    /**
     * Add frequency-based filters for overdue logic
     */
    protected function addOverdueFrequencyFilters($query)
    {
        $now = now();

        $query->where(function ($q) use ($now) {
            // Weekly pledges - overdue by more than 2 weeks
            $q->where('frequency', 'weekly')
              ->where('pledge_date', '<=', $now->copy()->subWeeks(3));
        })->orWhere(function ($q) use ($now) {
            // Monthly pledges - overdue by more than 6 weeks
            $q->where('frequency', 'monthly')
              ->where('pledge_date', '<=', $now->copy()->subWeeks(6));
        });
    }

    /**
     * Send reminder for a specific pledge
     */
    protected function sendReminder(Pledge $pledge, string $type): bool
    {
        try {
            $result = match($type) {
                'payment_due' => PledgeReminderNotification::sendPaymentDue($pledge),
                'overdue' => PledgeReminderNotification::sendOverdueReminder($pledge),
                'progress_update' => PledgeReminderNotification::sendProgressUpdate($pledge),
                default => false
            };

            $this->logReminderAction($pledge, $type, $result);
            return $result;

        } catch (\Exception $e) {
            Log::error('Failed to send pledge reminder', [
                'pledge_id' => $pledge->id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Log reminder action
     */
    protected function logReminderAction(Pledge $pledge, string $type, bool $success)
    {
        Log::info('Pledge reminder processed', [
            'pledge_id' => $pledge->id,
            'pledger' => $pledge->pledger_name,
            'phone' => $pledge->pledger_phone,
            'type' => $type,
            'success' => $success,
            'total_amount' => $pledge->total_amount,
            'received_amount' => $pledge->received_amount,
            'remaining_amount' => $pledge->remaining_amount,
        ]);
    }
}
