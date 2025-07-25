<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DutyRosterResource\Pages;
use App\Models\DutyRoster;
use App\Models\Member;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;

class DutyRosterResource extends Resource
{
    protected static ?string $model = DutyRoster::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Church Management';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Duty Roster';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Service Details')
                    ->description('Basic service information')
                    ->schema([
                        Grid::make(3)->schema([
                            Forms\Components\Select::make('branch_id')
                                ->label('Branch')
                                ->relationship('branch', 'name')
                                ->required()
                                ->default(function () {
                                    // Get the branch ID for "Internal Prayer Center"
                                    return \App\Models\Branch::where('name', 'Internal Prayer Center')->first()?->id;
                                }),
                            Forms\Components\Select::make('service_type')
                                ->required()
                                ->options([
                                    'Sunday Main Service' => 'Sunday Main Service',
                                    'Sunday Service' => 'Sunday Service',
                                    'Midweek Service' => 'Midweek Service',
                                    'Special Service' => 'Special Service',
                                ])
                                ->default('Sunday Main Service'),
                            Forms\Components\DatePicker::make('service_date')
                                ->required()
                                ->default(now()),
                            Forms\Components\TimePicker::make('service_time')
                                ->required()
                                ->default('09:30'),
                            Forms\Components\TimePicker::make('end_time')
                                ->label('End Time')
                                ->required()
                                ->default('12:30'),
                        ]),
                    ]),

                Section::make('Service Leaders')
                    ->description('Assign service responsibilities')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Select::make('service_host_id')
                                ->label('Service Host')
                                ->options(function () {
                                    return Member::where('is_active', true)
                                        ->where('is_eligible_for_pulpit_ministry', true) // Assuming ushers can be hosts
                                        ->orderBy('first_name')
                                        ->get()
                                        ->mapWithKeys(function ($member) {
                                            return [$member->id => "{$member->title} {$member->first_name} {$member->last_name}"];
                                        });
                                })
                                ->preload(),

                            Forms\Components\Select::make('intercession_leader_id')
                                ->label('Intercession Leader')
                                ->options(function () {
                                    return Member::where('is_active', true)
                                        ->where('is_intercessor', true)
                                        ->orderBy('first_name')
                                        ->get()
                                        ->mapWithKeys(function ($member) {
                                            return [$member->id => "{$member->title} {$member->first_name} {$member->last_name}"];
                                        });
                                })
                                ->preload(),

                            Forms\Components\Select::make('worship_leader_id')
                                ->label('Worship Leader')
                                ->options(function () {
                                    return Member::where('is_active', true)
                                        ->where('is_worship_leader', true)
                                        ->orderBy('first_name')
                                        ->get()
                                        ->mapWithKeys(function ($member) {
                                            return [$member->id => "{$member->title} {$member->first_name} {$member->last_name}"];
                                        });
                                })
                                ->preload(),

                            Forms\Components\Select::make('announcer_id')
                                ->label('Announcer')
                                ->options(function () {
                                    return Member::where('is_active', true)
                                        ->where('is_usher', true) // Assuming ushers can be announcers
                                        ->orderBy('first_name')
                                        ->get()
                                        ->mapWithKeys(function ($member) {
                                            return [$member->id => "{$member->title} {$member->first_name} {$member->last_name}"];
                                        });
                                })
                                ->preload(),

                            Forms\Components\Select::make('exhortation_leader_id')
                                ->label('Exhortation Leader')
                                ->options(function () {
                                    return Member::where('is_active', true)
                                        ->where('is_offering_exhortation_leader', true)
                                        ->orderBy('first_name')
                                        ->get()
                                        ->mapWithKeys(function ($member) {
                                            return [$member->id => "{$member->title} {$member->first_name} {$member->last_name}"];
                                        });
                                })
                                ->preload(),

                            Forms\Components\Select::make('sunday_school_teacher_id')
                                ->label('Sunday School Teacher')
                                ->options(function () {
                                    return Member::where('is_active', true)
                                        ->where('is_sunday_school_teacher', true)
                                        ->orderBy('first_name')
                                        ->get()
                                        ->mapWithKeys(function ($member) {
                                            return [$member->id => "{$member->title} {$member->first_name} {$member->last_name}"];
                                        });
                                })
                                ->preload(),

                            Forms\Components\Select::make('special_song_group')
                                ->label('Special Song Group')
                                ->options([
                                    'Men of Courage' => 'Men of Courage',
                                    'Royal Women' => 'Royal Women',
                                    'Transformed Youths' => 'Transformed Youths',
                                    "King's Kids" => "King's Kids",
                                    'Heart of Worship' => 'Heart of Worship',
                                ]),
                        ]),
                    ]),

                Section::make('Preacher Information')
                    ->schema([
                        Forms\Components\Select::make('preacher_type')
                            ->options([
                                'local' => 'Local Preacher',
                                'visiting' => 'Visiting Preacher',
                            ])
                            ->required()
                            ->default('local')
                            ->live(),

                        Forms\Components\Select::make('preacher_id')
                            ->label('Local Preacher')
                            ->options(function () {
                                return Member::where('is_active', true)
                                    ->where('is_pastor', true)
                                    ->orderBy('first_name')
                                    ->get()
                                    ->mapWithKeys(function ($member) {
                                        return [$member->id => "{$member->title} {$member->first_name} {$member->last_name}"];
                                    });
                            })
                            ->preload()
                            ->visible(fn (Get $get) => $get('preacher_type') === 'local'),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('visiting_preacher_name')
                                    ->required(fn (Get $get) => $get('preacher_type') === 'visiting'),
                                Forms\Components\TextInput::make('visiting_preacher_church')
                                    ->required(fn (Get $get) => $get('preacher_type') === 'visiting'),
                            ])
                            ->visible(fn (Get $get) => $get('preacher_type') === 'visiting'),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->required()
                            ->default('draft'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_type')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sunday Main Service' => 'success',
                        'Sunday Service' => 'success',
                        'Midweek Service' => 'info',
                        'Special Service' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('service_time')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('serviceHost.first_name')
                    ->label('Host')
                    ->formatStateUsing(function ($record) {
                        return $record->serviceHost ?
                            "{$record->serviceHost->title} {$record->serviceHost->first_name} {$record->serviceHost->last_name}" :
                            '-';
                    })
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('worshipLeader.first_name')
                    ->label('Worship')
                    ->formatStateUsing(function ($record) {
                        return $record->worshipLeader ?
                            "{$record->worshipLeader->title} {$record->worshipLeader->first_name} {$record->worshipLeader->last_name}" :
                            '-';
                    })
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('special_song_group')
                    ->label('Special Song')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Men of Courage' => 'blue',
                        'Royal Women' => 'pink',
                        'Transformed Youths' => 'green',
                        "King's Kids" => 'orange',
                        'Heart of Worship' => 'purple',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('preacher_info')
                    ->label('Preacher')
                    ->formatStateUsing(function ($record) {
                        if ($record->preacher_type === 'visiting') {
                            return "Visiting: {$record->visiting_preacher_name}";
                        }
                        return $record->preacher ?
                            "{$record->preacher->title} {$record->preacher->first_name} {$record->preacher->last_name}" :
                            '-';
                    })
                    ->searchable(['visiting_preacher_name']),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                    }),
            ])
            ->filters([
                SelectFilter::make('branch')
                    ->relationship('branch', 'name'),
                SelectFilter::make('service_type'),
                SelectFilter::make('special_song_group')
                    ->options([
                        'Men of Courage' => 'Men of Courage',
                        'Royal Women' => 'Royal Women',
                        'Transformed Youths' => 'Transformed Youths',
                        "King's Kids" => "King's Kids",
                        'Heart of Worship' => 'Heart of Worship',
                    ]),
                Filter::make('service_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('service_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('service_date', '<=', $date),
                            );
                    }),
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('print_roster')
                    ->icon('heroicon-o-printer')
                    ->label('Print Roster')
                    ->action(function (DutyRoster $record) {
                        $pdf = Pdf::loadView('pdf.duty-roster', ['roster' => $record]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "duty-roster-{$record->service_date}.pdf"
                        );
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('service_date', 'desc');
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
            'index' => Pages\ListDutyRosters::route('/'),
            'create' => Pages\CreateDutyRoster::route('/create'),
            //'view' => Pages\ViewDutyRoster::route('/{record}'),
            'edit' => Pages\EditDutyRoster::route('/{record}/edit'),
        ];
    }
}
