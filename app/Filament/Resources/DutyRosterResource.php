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
                                ->required(),
                            Forms\Components\Select::make('service_type')
                                ->required()
                                ->options([
                                    'Sunday Service' => 'Sunday Service',
                                    'Midweek Service' => 'Midweek Service',
                                    'Special Service' => 'Special Service',
                                ]),
                            Forms\Components\DatePicker::make('service_date')
                                ->required(),
                            Forms\Components\TimePicker::make('service_time')
                                ->required(),
                        ]),
                    ]),

                Section::make('Service Leaders')
                    ->description('Assign service responsibilities')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Select::make('service_host_id')
                                ->label('Service Host')
                                ->relationship('serviceHost', 'first_name', fn (Builder $query) => $query->orderBy('first_name'))
                                ->getOptionLabelFromRecordUsing(fn (Member $record) => "{$record->title} {$record->first_name} {$record->last_name}")
                                ->searchable()
                                ->preload(),

                            Forms\Components\Select::make('intercession_leader_id')
                                ->label('Intercession Leader')
                                ->relationship('intercessionLeader', 'first_name', fn (Builder $query) => $query->orderBy('first_name'))
                                ->getOptionLabelFromRecordUsing(fn (Member $record) => "{$record->title} {$record->first_name} {$record->last_name}")
                                ->searchable()
                                ->preload(),

                            Forms\Components\Select::make('worship_leader_id')
                                ->label('Worship Leader')
                                ->relationship('worshipLeader', 'first_name', fn (Builder $query) => $query->orderBy('first_name'))
                                ->getOptionLabelFromRecordUsing(fn (Member $record) => "{$record->title} {$record->first_name} {$record->last_name}")
                                ->searchable()
                                ->preload(),

                            Forms\Components\Select::make('announcer_id')
                                ->label('Announcer')
                                ->relationship('announcer', 'first_name', fn (Builder $query) => $query->orderBy('first_name'))
                                ->getOptionLabelFromRecordUsing(fn (Member $record) => "{$record->title} {$record->first_name} {$record->last_name}")
                                ->searchable()
                                ->preload(),

                            Forms\Components\Select::make('exhortation_leader_id')
                                ->label('Exhortation Leader')
                                ->relationship('exhortationLeader', 'first_name', fn (Builder $query) => $query->orderBy('first_name'))
                                ->getOptionLabelFromRecordUsing(fn (Member $record) => "{$record->title} {$record->first_name} {$record->last_name}")
                                ->searchable()
                                ->preload(),

                            Forms\Components\Select::make('sunday_school_teacher_id')
                                ->label('Sunday School Teacher')
                                ->relationship('sundaySchoolTeacher', 'first_name', fn (Builder $query) => $query->orderBy('first_name'))
                                ->getOptionLabelFromRecordUsing(fn (Member $record) => "{$record->title} {$record->first_name} {$record->last_name}")
                                ->searchable()
                                ->preload(),

                            Forms\Components\Select::make('special_song_singer_id')
                                ->label('Special Song Singer')
                                ->relationship('specialSongSinger', 'first_name', fn (Builder $query) => $query->orderBy('first_name'))
                                ->getOptionLabelFromRecordUsing(fn (Member $record) => "{$record->title} {$record->first_name} {$record->last_name}")
                                ->searchable()
                                ->preload(),
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
                            ->live(),

                        Forms\Components\Select::make('preacher_id')
                            ->label('Local Preacher')
                            ->relationship('preacher', 'first_name', fn (Builder $query) => $query->orderBy('first_name'))
                            ->getOptionLabelFromRecordUsing(fn (Member $record) => "{$record->title} {$record->first_name} {$record->last_name}")
                            ->searchable()
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_time')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->sortable(),

                // Fixed: Use formatStateUsing instead of referencing non-existent full_name column
                Tables\Columns\TextColumn::make('serviceHost.first_name')
                    ->label('Host')
                    ->formatStateUsing(function ($record) {
                        return $record->serviceHost ?
                            "{$record->serviceHost->title} {$record->serviceHost->first_name} {$record->serviceHost->last_name}" :
                            '-';
                    })
                    ->searchable(['first_name', 'last_name']) // Search actual database columns
                    ->sortable(),

                Tables\Columns\TextColumn::make('worshipLeader.first_name')
                    ->label('Worship')
                    ->formatStateUsing(function ($record) {
                        return $record->worshipLeader ?
                            "{$record->worshipLeader->title} {$record->worshipLeader->first_name} {$record->worshipLeader->last_name}" :
                            '-';
                    })
                    ->searchable(['first_name', 'last_name']) // Search actual database columns
                    ->sortable(),

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
