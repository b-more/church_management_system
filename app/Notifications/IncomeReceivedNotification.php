<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Income;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class IncomeReceivedNotification extends Notification
{
    use Queueable;

    protected Income $income;

    /**
     * Create a new notification instance.
     */
    public function __construct(Income $income)
    {
        $this->income = $income;
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
        $phone = $this->income->contributor_phone;

        if (!$phone) {
            Log::warning('Cannot send SMS notification: No phone number provided', [
                'income_id' => $this->income->id
            ]);
            return false;
        }

        $message = $this->buildSmsMessage();

        $result = SmsService::send($message, $phone);

        if ($result) {
            Log::info('Income receipt SMS sent successfully', [
                'income_id' => $this->income->id,
                'phone' => $phone,
                'amount' => $this->income->amount
            ]);
        } else {
            Log::error('Failed to send income receipt SMS', [
                'income_id' => $this->income->id,
                'phone' => $phone
            ]);
        }

        return $result;
    }

    /**
     * Build the SMS message
     */
    protected function buildSmsMessage(): string
    {
        $amount = 'K' . number_format($this->income->amount, 2);
        $type = $this->income->offeringType->name;
        $date = $this->income->date->format('d/m/Y');
        $branch = $this->income->branch->name;
        $contributor = $this->income->contributor_name ?: 'Friend';

        $message = "Dear {$contributor}, thank you for your {$type} contribution of {$amount} on {$date}. ";

        // Add project-specific message
        if ($this->income->project) {
            $message .= "Your support for '{$this->income->project->name}' is greatly appreciated. ";
        }

        // Add pledge progress if applicable
        if ($this->income->pledge) {
            $progress = $this->income->pledge->completion_percentage;
            $message .= "Pledge progress: {$progress}%. ";
        }

        $message .= "May God bless you abundantly! - {$branch}";

        return $message;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'income_id' => $this->income->id,
            'amount' => $this->income->amount,
            'offering_type' => $this->income->offeringType->name,
            'contributor' => $this->income->contributor_name,
            'date' => $this->income->date->toDateString(),
        ];
    }

    /**
     * Static method to send notification
     */
    public static function sendTo(Income $income): bool
    {
        if (!$income->contributor_phone) {
            return false;
        }

        try {
            $notification = new self($income);
            return $notification->toSms($income);
        } catch (\Exception $e) {
            Log::error('Error sending income notification', [
                'income_id' => $income->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
