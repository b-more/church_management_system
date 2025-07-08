<?php

namespace App\Filament\Resources\IncomeResource\Pages;

use App\Filament\Resources\IncomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewIncome extends ViewRecord
{
    protected static string $resource = IncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit Income')
                ->icon('heroicon-o-pencil'),
            Actions\DeleteAction::make()
                ->label('Delete')
                ->icon('heroicon-o-trash'),
            Actions\Action::make('send_sms')
                ->label('Send SMS Receipt')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->color('info')
                ->visible(fn () => $this->record->contributor_phone)
                ->requiresConfirmation()
                ->modalHeading('Send SMS Receipt')
                ->modalDescription('Send SMS receipt confirmation to contributor?')
                ->action(function () {
                    $income = $this->record;
                    $message = "Thank you for your contribution of K" . number_format($income->amount, 2) .
                              " to " . $income->offeringType->name .
                              " on " . $income->date->format('d/m/Y') .
                              ". God bless you! - " . $income->branch->name;

                    if (\App\Services\SmsService::send($message, $income->contributor_phone)) {
                        \Filament\Notifications\Notification::make()
                            ->title('SMS sent successfully')
                            ->success()
                            ->send();
                    } else {
                        \Filament\Notifications\Notification::make()
                            ->title('Failed to send SMS')
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function getTitle(): string
    {
        $income = $this->record;
        return 'Income Record: ' . $income->contributor_name;
    }

    public function getSubheading(): string
    {
        $income = $this->record;
        return 'K' . number_format($income->amount, 2) . ' • ' . $income->offeringType->name . ' • ' . $income->date->format('M j, Y');
    }
}
