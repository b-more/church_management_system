<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceStatisticResource\Pages;
use App\Filament\Resources\AttendanceStatisticResource\RelationManagers;
use App\Models\AttendanceStatistic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;

class AttendanceStatisticResource extends Resource
{
    protected static ?string $model = AttendanceStatistic::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Analytics';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Attendance Analytics';

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->description('Record attendance statistics')
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(now()),

                        Forms\Components\Select::make('service_type')
                            ->required()
                            ->options([
                                'Sunday Service' => 'Sunday Service',
                                'Midweek Service' => 'Midweek Service',
                                'Special Service' => 'Special Service',
                                'Youth Service' => 'Youth Service',
                            ]),

                        Forms\Components\Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->required()
                            ->searchable(),
                            
                        Forms\Components\TextInput::make('service_name')
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Attendance Breakdown')
                    ->description('Detailed attendance numbers')
                    ->icon('heroicon-o-users')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('total_attendance')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),

                        Forms\Components\TextInput::make('total_members')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),

                        Forms\Components\TextInput::make('total_visitors')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),

                        Forms\Components\TextInput::make('total_first_timers')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),

                        Forms\Components\TextInput::make('total_children')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),

                        Forms\Components\TextInput::make('total_youth')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                    ]),

                Section::make('Additional Information')
                    ->description('Other relevant details')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_special_event')
                            ->label('Special Event?')
                            ->default(false),

                        Forms\Components\TextInput::make('event_name')
                            ->maxLength(255)
                            ->visible(fn (Get $get): bool => $get('is_special_event')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('branch.name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('service_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sunday Service' => 'success',
                        'Midweek Service' => 'info',
                        'Special Service' => 'warning',
                        'Youth Service' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total_attendance')
                    ->numeric()
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Average::make()
                            ->label('Avg. Attendance'),
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total'),
                    ]),

                Tables\Columns\TextColumn::make('attendance_breakdown')
                    ->label('Breakdown')
                    ->description(fn (AttendanceStatistic $record): string => 
                        "Members: {$record->total_members} | Visitors: {$record->total_visitors} | First Timers: {$record->total_first_timers}"
                    ),

                Tables\Columns\IconColumn::make('is_special_event')
                    ->boolean()
                    ->label('Special Event'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('branch')
                    ->relationship('branch', 'name')
                    ->multiple()
                    ->preload(),

                SelectFilter::make('service_type')
                    ->options([
                        'Sunday Service' => 'Sunday Service',
                        'Midweek Service' => 'Midweek Service',
                        'Special Service' => 'Special Service',
                        'Youth Service' => 'Youth Service',
                    ])
                    ->multiple(),

                Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),

                Filter::make('is_special_event')
                    ->toggle()
                    ->label('Special Events Only')
                    ->query(fn (Builder $query): Builder => $query->where('is_special_event', true)),

                Filter::make('high_attendance')
                    ->toggle()
                    ->label('High Attendance (>100)')
                    ->query(fn (Builder $query): Builder => $query->where('total_attendance', '>', 100)),
            ])
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export Selected')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn (Collection $records) => static::export($records)),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-chart-bar')
            ->emptyStateHeading('No attendance records yet')
            ->emptyStateDescription('Start tracking your church attendance by creating a new record.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create Record')
                    ->icon('heroicon-o-plus'),
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
            'index' => Pages\ListAttendanceStatistics::route('/'),
            'create' => Pages\CreateAttendanceStatistic::route('/create'),
            'view' => Pages\ViewAttendanceStatistic::route('/{record}'),
            'edit' => Pages\EditAttendanceStatistic::route('/{record}/edit'),
        ];
    }

    // public static function getWidgets(): array
    // {
    //     return [
    //         AttendanceStatisticResource\Widgets\AttendanceOverview::class,
    //     ];
    // }
}