<?php
namespace App\Filament\Resources;

use App\Filament\Resources\EventRegistrationResource\Pages;
use App\Models\EventRegistration;
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

class EventRegistrationResource extends Resource
{
    protected static ?string $model = EventRegistration::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Event Management';
    protected static ?int $navigationSort = 3;
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
                    ->searchable()
                    ->sortable(),
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
