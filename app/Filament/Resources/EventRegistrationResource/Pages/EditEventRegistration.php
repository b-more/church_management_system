<?php

namespace App\Filament\Resources\EventRegistrationResource\Pages;

use App\Filament\Resources\EventRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class EditEventRegistration extends EditRecord
{
    protected static string $resource = EventRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Check if we need to send an SMS
        $data = $this->data;

        if (isset($data['sms_message']) && !empty($data['sms_message'])) {
            $record = $this->record;

            try {
                // Make sure we have a member with a phone number
                if ($record->member && !empty($record->member->phone)) {
                    // Send the SMS
                    SmsService::send($data['sms_message'], $record->member->phone);

                    // Log the action
                    Log::info('SMS sent to event registrant after edit', [
                        'registration_id' => $record->id,
                        'member_id' => $record->member_id,
                        'sent_by' => auth()->id()
                    ]);

                    // Notify user of success
                    Notification::make()
                        ->title('SMS sent successfully')
                        ->success()
                        ->send();
                } else {
                    // Notify user that the message couldn't be sent
                    Notification::make()
                        ->title('SMS not sent')
                        ->body('No valid phone number found for this registrant.')
                        ->warning()
                        ->send();
                }
            } catch (\Exception $e) {
                // Log the error
                Log::error('Failed to send SMS to registrant after edit', [
                    'registration_id' => $record->id,
                    'error' => $e->getMessage()
                ]);

                // Notify user of the error
                Notification::make()
                    ->title('Failed to send SMS')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        }

        parent::afterSave();
    }
}
