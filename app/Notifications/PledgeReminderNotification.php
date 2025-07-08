<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Pledge;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class PledgeReminderNotification extends Notification
{
    use Queueable;

    protected Pledge $pledge;
    protected string $reminderType;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pledge $pledge, string $reminderType = 'payment_due')
    {
        $this->pledge = $pledge;
        $this->reminderType = $reminderType;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['sms'];
    }

    /**
     * Send SMS notification
     */
    public function toSms(object $notifiable): bool
    {
        $phone = $this->pledge->pledger_phone;

        if (!$phone) {
            Log::warning('Cannot send SMS notification: No phone number provided', [
                'pledge_id' => $this->pledge->id
            ]);
            return false;
        }

        $message = $this->buildSmsMessage();

        $result = SmsService::send($message, $phone);

        if ($result) {
            Log::info('Pledge reminder SMS sent successfully', [
                'pledge_id' => $this->pledge->id,
                'phone' => $phone,
                'reminder_type' => $this->reminderType
            ]);
        } else {
            Log::error('Failed to send pledge reminder SMS', [
                'pledge_id' => $this->pledge->id,
                'phone' => $phone
            ]);
        }

        return $result;
    }

    /**
     * Build the SMS message based on reminder type
     */
    protected function buildSmsMessage(): string
    {
        $pledger = $this->pledge->pledger_name ?: 'Friend';
        $branch = $this->pledge->branch->name;

        return match($this->reminderType) {
            'payment_due' => $this->buildPaymentDueMessage($pledger, $branch),
            'overdue' => $this->buildOverdueMessage($pledger, $branch),
            'completion' => $this->buildCompletionMessage($pledger, $branch),
            'progress_update' => $this->buildProgressUpdateMessage($pledger, $branch),
            default => $this->buildDefaultMessage($pledger, $branch)
        };
    }

    /**
     * Build payment due message
     */
    protected function buildPaymentDueMessage(string $pledger, string $branch): string
    {
        $amount = 'K' . number_format($this->pledge->frequency_amount ?? $this->pledge->remaining_amount, 2);
        $dueDate = $this->pledge->next_payment_due_date ?
                   $this->pledge->next_payment_due_date->format('d/m/Y') : 'soon';

        $message = "Dear {$pledger}, this is a friendly reminder about your pledge payment of {$amount} ";

        if ($this->pledge->project) {
            $message .= "for '{$this->pledge->project->name}' ";
        }

        $message .= "due on {$dueDate}. ";

        $progress = $this->pledge->completion_percentage;
        $message .= "Current progress: {$progress}%. Thank you for your faithfulness! - {$branch}";

        return $message;
    }

    /**
     * Build overdue message
     */
    protected function buildOverdueMessage(string $pledger, string $branch): string
    {
        $remaining = 'K' . number_format($this->pledge->remaining_amount, 2);
        $overdueDays = $this->pledge->target_completion_date ?
                      $this->pledge->target_completion_date->diffInDays(now()) : 0;

        $message = "Dear {$pledger}, your pledge payment of {$remaining} is overdue by {$overdueDays} days. ";

        if ($this->pledge->project) {
            $message .= "Your support for '{$this->pledge->project->name}' is needed. ";
        }

        $message .= "Please contact us if you need to discuss your pledge. God bless! - {$branch}";

        return $message;
    }

    /**
     * Build completion message
     */
    protected function buildCompletionMessage(string $pledger, string $branch): string
    {
        $total = 'K' . number_format($this->pledge->total_amount, 2);

        $message = "Praise God! Dear {$pledger}, you have successfully completed your pledge of {$total}! ";

        if ($this->pledge->project) {
            $message .= "Thank you for your contribution to '{$this->pledge->project->name}'. ";
        }

        $message .= "Your faithfulness is a blessing. May God reward you abundantly! - {$branch}";

        return $message;
    }

    /**
     * Build progress update message
     */
    protected function buildProgressUpdateMessage(string $pledger, string $branch): string
    {
        $progress = $this->pledge->completion_percentage;
        $remaining = 'K' . number_format($this->pledge->remaining_amount, 2);

        $message = "Dear {$pledger}, your pledge is {$progress}% complete! ";

        if ($this->pledge->project) {
            $projectProgress = $this->pledge->project->progress_percentage;
            $message .= "Project '{$this->pledge->project->name}' is {$projectProgress}% funded. ";
        }

        $message .= "Remaining: {$remaining}. Thank you for your commitment! - {$branch}";

        return $message;
    }

    /**
     * Build default message
     */
    protected function buildDefaultMessage(string $pledger, string $branch): string
    {
        $remaining = 'K' . number_format($this->pledge->remaining_amount, 2);

        $message = "Dear {$pledger}, thank you for your pledge commitment. ";

        if ($this->pledge->project) {
            $message .= "Your support for '{$this->pledge->project->name}' ";
        } else {
            $message .= "Your pledge ";
        }

        $message .= "has a remaining balance of {$remaining}. God bless you! - {$branch}";

        return $message;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pledge_id' => $this->pledge->id,
            'pledger' => $this->pledge->pledger_name,
            'reminder_type' => $this->reminderType,
            'remaining_amount' => $this->pledge->remaining_amount,
            'completion_percentage' => $this->pledge->completion_percentage,
        ];
    }

    /**
     * Static method to send different types of reminders
     */
    public static function sendPaymentDue(Pledge $pledge): bool
    {
        return self::sendReminder($pledge, 'payment_due');
    }

    public static function sendOverdueReminder(Pledge $pledge): bool
    {
        return self::sendReminder($pledge, 'overdue');
    }

    public static function sendCompletionNotification(Pledge $pledge): bool
    {
        return self::sendReminder($pledge, 'completion');
    }

    public static function sendProgressUpdate(Pledge $pledge): bool
    {
        return self::sendReminder($pledge, 'progress_update');
    }

    protected static function sendReminder(Pledge $pledge, string $type): bool
    {
        if (!$pledge->pledger_phone) {
            return false;
        }

        try {
            $notification = new self($pledge, $type);
            return $notification->toSms($pledge);
        } catch (\Exception $e) {
            Log::error('Error sending pledge reminder', [
                'pledge_id' => $pledge->id,
                'reminder_type' => $type,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
