<?php
namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Event Details')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->required(),
                        Select::make('department_id')
                            ->relationship('department', 'name'),
                        Select::make('event_type')
                            ->options([
                                'Seminar' => 'Seminar',
                                'Conference' => 'Conference',
                                'Workshop' => 'Workshop',
                                'Revival' => 'Revival',
                                'Other' => 'Other',
                            ])
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('title')->required(),
                        Textarea::make('description')->rows(3),
                    ]),
                ]),

            Section::make('Schedule')
                ->schema([
                    Grid::make(2)->schema([
                        DatePicker::make('start_date')->required(),
                        DatePicker::make('end_date')->required(),
                        TimePicker::make('start_time')->required(),
                        TimePicker::make('end_time')->required(),
                    ]),
                ]),

            Section::make('Location')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('venue')->required(),
                        Textarea::make('venue_address')->rows(2),
                    ]),
                ]),

            Section::make('Organization')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('organizer_id')
                            ->relationship('organizer', 'first_name')
                            ->searchable(),
                        Select::make('coordinator_id')
                            ->relationship('coordinator', 'first_name')
                            ->searchable(),
                    ]),
                ]),

            Section::make('Attendance & Registration')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('budget')
                            ->numeric()
                            ->prefix('ZMW'),
                        TextInput::make('expected_attendance')
                            ->numeric(),
                        TextInput::make('actual_attendance')
                            ->numeric(),
                    ]),
                    Grid::make(2)->schema([
                        Toggle::make('registration_required'),
                        DatePicker::make('registration_deadline')
                            ->visible(fn ($get) => $get('registration_required')),
                    ]),
                ]),

            Section::make('Status')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('status')
                            ->options([
                                'Planned' => 'Planned',
                                'Ongoing' => 'Ongoing',
                                'Completed' => 'Completed',
                                'Cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        Textarea::make('notes')->rows(3),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('venue'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Planned' => 'gray',
                        'Ongoing' => 'warning',
                        'Completed' => 'success',
                        'Cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('budget')
                    ->money('zmw'),
                Tables\Columns\TextColumn::make('actual_attendance'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('branch')
                    ->relationship('branch', 'name'),
                Tables\Filters\SelectFilter::make('event_type'),
                Tables\Filters\SelectFilter::make('status'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            //'view' => Pages\ViewDepartment::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}