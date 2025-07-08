<?php
namespace App\Filament\Resources;

use App\Filament\Resources\EventRegistrationResource\Pages;
use App\Models\EventRegistration;
use App\Services\SmsService;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class EventRegistrationResource extends Resource
{
    protected static ?string $model = EventRegistration::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Event Management';
    protected static ?int $navigationSort = 12;
    protected static ?string $recordTitleAttribute = 'registration_number';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Registration Information')
                ->description('Basic registration details')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('event_id')
                            ->relationship('event', 'title')
                            ->required()
                           // ->searchable()
                            ->preload(),
                        Select::make('member_id')
                            ->relationship('member', 'first_name')
                           //->searchable()
                            ->preload(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('registration_number')
                            ->default(fn () => 'REG-' . uniqid())
                            ->disabled()
                            ->dehydrated(),
                        DateTimePicker::make('registered_at')
                            ->default(now())
                            ->required(),
                    ]),
                ]),

            Section::make('Status & Attendance')
                ->description('Track registration and attendance status')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('status')
                            ->options([
                                'Pending' => 'Pending',
                                'Confirmed' => 'Confirmed',
                                'Cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('Pending'),
                        Select::make('attendance_status')
                            ->options([
                                'Pending' => 'Pending',
                                'Present' => 'Present',
                                'Absent' => 'Absent',
                            ])
                            ->default('Pending'),
                        DateTimePicker::make('attended_at')
                            ->label('Attendance Time'),
                    ]),
                ]),

            Section::make('Payment Information')
                ->description('Track payment details')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('amount_paid')
                            ->numeric()
                            ->prefix('ZMW')
                            ->default(0),
                        Select::make('payment_status')
                            ->options([
                                'Pending' => 'Pending',
                                'Partial' => 'Partial',
                                'Paid' => 'Paid',
                            ])
                            ->default('Pending'),
                    ]),
                ]),

            Section::make('Additional Information')
                ->schema([
                    Textarea::make('special_requirements')
                        ->rows(2),
                    Textarea::make('notes')
                        ->rows(2),
                ]),

            Section::make('Send SMS Notification')
                ->description('Send an SMS notification to this registrant')
                ->schema([
                    Textarea::make('sms_message')
                        ->label('SMS Message')
                        ->rows(3)
                        ->helperText('This message will be sent via SMS to the registrant')
                        ->default(function (EventRegistration $record) {
                            $eventTitle = $record->event->title ?? 'the event';
                            $memberName = $record->member->first_name ?? 'there';
                            return "Hi {$memberName}, your registration for {$eventTitle} has been confirmed. Thank you for registering!";
                        }),
                ])
                ->visible(fn ($record) => $record && $record->exists()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event.title')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('member.first_name')
                    ->label('Member')
                    ->formatStateUsing(fn ($record) =>
                        $record->member ? $record->member->first_name . ' ' . $record->member->last_name : '-')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('member.phone')
                    ->label('Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Confirmed' => 'success',
                        'Cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('attendance_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Present' => 'success',
                        'Absent' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('amount_paid')
                    ->money('zmw')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Paid' => 'success',
                        'Partial' => 'warning',
                        'Pending' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('registered_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title'),
                Tables\Filters\SelectFilter::make('status'),
                Tables\Filters\SelectFilter::make('attendance_status'),
                Tables\Filters\SelectFilter::make('payment_status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('sendSms')
                    ->label('Send SMS')
                    ->icon('heroicon-o-paper-airplane')
                    ->form([
                        Textarea::make('sms_message')
                            ->label('SMS Message')
                            ->required()
                            ->default(function (EventRegistration $record) {
                                $eventTitle = $record->event->title ?? 'the event';
                                $memberName = $record->member->first_name ?? 'there';
                                return "Hi {$memberName}, your registration for {$eventTitle} has been confirmed. Thank you for registering!";
                            })
                            ->rows(3),
                    ])
                    ->action(function (EventRegistration $record, array $data): void {
                        try {
                            // Get the phone number
                            $phone = $record->member->phone;

                            if (empty($phone)) {
                                Notification::make()
                                    ->title('SMS not sent')
                                    ->body('No phone number found for this registrant.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Send SMS and CHECK THE RETURN VALUE
                            $smsResult = SmsService::send($data['sms_message'], $phone);

                            if ($smsResult) {
                                // Only log success if SMS actually succeeded
                                Log::info('SMS sent to event registrant', [
                                    'registration_id' => $record->id,
                                    'member_id' => $record->member_id,
                                    'sent_by' => auth()->id(),
                                    'phone' => $phone
                                ]);

                                // Show success notification
                                Notification::make()
                                    ->title('SMS sent successfully')
                                    ->body("Message delivered to {$phone}")
                                    ->success()
                                    ->send();
                            } else {
                                // SMS failed - log error and notify user
                                Log::error('Failed to send SMS to event registrant', [
                                    'registration_id' => $record->id,
                                    'member_id' => $record->member_id,
                                    'phone' => $phone,
                                    'message' => $data['sms_message']
                                ]);

                                Notification::make()
                                    ->title('Failed to send SMS')
                                    ->body('SMS could not be delivered. Please check logs for details.')
                                    ->danger()
                                    ->send();
                            }

                        } catch (\Exception $e) {
                            Log::error('Exception when sending SMS to event registrant', [
                                'registration_id' => $record->id,
                                'error' => $e->getMessage()
                            ]);

                            Notification::make()
                                ->title('Failed to send SMS')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (EventRegistration $record) => $record->member && !empty($record->member->phone))
                    ->modalWidth('lg'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('sendBulkSms')
                        ->label('Send SMS')
                        ->icon('heroicon-o-paper-airplane')
                        ->form([
                            Textarea::make('bulk_sms_message')
                                ->label('SMS Message')
                                ->required()
                                ->default("Thank you for registering for our event. Your registration has been confirmed. We look forward to seeing you there!")
                                ->rows(3)
                                ->helperText('This message will be sent to all selected registrants with valid phone numbers.'),
                        ])
                        ->action(function ($records, array $data): void {
                            $successCount = 0;
                            $failCount = 0;

                            $records->each(function ($record) use ($data, &$successCount, &$failCount) {
                                try {
                                    // Get the phone number from the member
                                    if ($record->member && !empty($record->member->phone)) {
                                        // Send SMS and CHECK THE RETURN VALUE
                                        $smsResult = SmsService::send($data['bulk_sms_message'], $record->member->phone);

                                        if ($smsResult) {
                                            // Only log success if SMS actually succeeded
                                            Log::info('Bulk SMS sent to event registrant', [
                                                'registration_id' => $record->id,
                                                'member_id' => $record->member_id,
                                                'sent_by' => auth()->id(),
                                                'phone' => $record->member->phone
                                            ]);

                                            $successCount++;
                                        } else {
                                            // SMS failed
                                            Log::error('Failed to send bulk SMS to event registrant', [
                                                'registration_id' => $record->id,
                                                'member_id' => $record->member_id,
                                                'phone' => $record->member->phone
                                            ]);

                                            $failCount++;
                                        }
                                    } else {
                                        $failCount++;
                                    }
                                } catch (\Exception $e) {
                                    Log::error('Exception during bulk SMS to event registrant', [
                                        'registration_id' => $record->id,
                                        'error' => $e->getMessage()
                                    ]);

                                    $failCount++;
                                }
                            });

                            // Show accurate summary notification
                            if ($successCount > 0 && $failCount == 0) {
                                Notification::make()
                                    ->title('Bulk SMS completed successfully')
                                    ->body("All {$successCount} messages sent successfully.")
                                    ->success()
                                    ->send();
                            } elseif ($successCount > 0 && $failCount > 0) {
                                Notification::make()
                                    ->title('Bulk SMS partially completed')
                                    ->body("{$successCount} messages sent successfully. {$failCount} failed.")
                                    ->warning()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Bulk SMS failed')
                                    ->body("All {$failCount} messages failed to send.")
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Send SMS to Selected Registrants')
                        ->modalWidth('lg')
                ]),
            ])
            ->emptyStateHeading('No Registrations')
            ->emptyStateDescription('Start by creating your first event registration.')
            ->emptyStateIcon('heroicon-o-ticket');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventRegistrations::route('/'),
            'create' => Pages\CreateEventRegistration::route('/create'),
            'edit' => Pages\EditEventRegistration::route('/{record}/edit'),
        ];
    }
}
