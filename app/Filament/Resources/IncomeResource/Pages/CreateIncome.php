<?php

namespace App\Filament\Resources\IncomeResource\Pages;

use App\Filament\Resources\IncomeResource;
use App\Models\Income;
use App\Notifications\IncomeReceivedNotification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateIncome extends CreateRecord
{
    protected static string $resource = IncomeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $income = $this->record;

        // Send SMS notification if requested
        if ($this->data['send_sms'] ?? false) {
            if ($income->contributor_phone) {
                $success = IncomeReceivedNotification::sendTo($income);

                if ($success) {
                    Notification::make()
                        ->title('SMS notification sent successfully')
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('Failed to send SMS notification')
                        ->warning()
                        ->body('The income was recorded but SMS could not be sent.')
                        ->send();
                }
            } else {
                Notification::make()
                    ->title('No phone number available')
                    ->warning()
                    ->body('SMS notification could not be sent - no phone number provided.')
                    ->send();
            }
        }

        // Show success notification with details
        $offeringType = $income->offeringType->name;
        $amount = 'K' . number_format($income->amount, 2);
        $contributor = $income->contributor_name ?: 'Anonymous';

        Notification::make()
            ->title('Income recorded successfully')
            ->success()
            ->body("Recorded {$offeringType} contribution of {$amount} from {$contributor}")
            ->send();
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Income recorded successfully';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove send_sms from data as it's not a database field
        unset($data['send_sms']);

        return $data;
    }
}
