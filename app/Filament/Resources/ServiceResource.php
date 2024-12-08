<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
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
use Illuminate\Support\Facades\Storage;
use Filament\Support\Enums\IconPosition;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Church Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Service Overview')
                    ->description('Basic information about the service')
                    ->icon('heroicon-o-information-circle')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('branch_id')
                            ->label('Branch')
                            ->relationship('branch', 'name')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Select::make('service_type')
                            ->required()
                            ->options([
                                'Sunday Service' => 'Sunday Service',
                                'Midweek Service' => 'Midweek Service',
                                'Special Service' => 'Special Service',
                                'Prayer Meeting' => 'Prayer Meeting',
                                'Bible Study' => 'Bible Study',
                                'Youth Service' => 'Youth Service',
                                'Children Service' => 'Children Service',
                            ])
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('service_name')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TimePicker::make('start_time')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TimePicker::make('end_time')
                            ->required()
                            ->columnSpan(1),
                    ]),

                Section::make('Leadership Team')
                    ->description('Assign service leaders and responsibilities')
                    ->icon('heroicon-o-users')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Section::make('Primary Leaders')
                                    ->compact()
                                    ->schema([
                                        Forms\Components\Select::make('host_id')
                                            ->label('Service Host')
                                            ->options(function () {
                                                return Member::query()
                                                    ->get()
                                                    ->mapWithKeys(fn ($member) => [
                                                        $member->id => $member->title . ' ' . $member->first_name . ' ' . $member->last_name
                                                    ]);
                                            })
                                            ->searchable()
                                            ->preload(),
                                        Forms\Components\Select::make('worship_leader_id')
                                            ->label('Worship Leader')
                                            ->options(function () {
                                                return Member::query()
                                                    ->get()
                                                    ->mapWithKeys(fn ($member) => [
                                                        $member->id => $member->title . ' ' . $member->first_name . ' ' . $member->last_name
                                                    ]);
                                            })
                                            ->searchable()
                                            ->preload(),
                                    ]),
                                Section::make('Support Leaders')
                                    ->compact()
                                    ->schema([
                                        Forms\Components\Select::make('intercession_leader_id')
                                            ->label('Intercession Leader')
                                            ->options(function () {
                                                return Member::all()->mapWithKeys(fn ($member) => [
                                                    $member->id => $member->title . ' ' . $member->first_name . ' ' . $member->last_name
                                                ]);
                                            })
                                            ->searchable()
                                            ->preload(),
                                        Forms\Components\Select::make('offering_exhortation_leader_id')
                                            ->label('Offering Exhortation')
                                            ->options(function () {
                                                return Member::all()->mapWithKeys(fn ($member) => [
                                                    $member->id => $member->title . ' ' . $member->first_name . ' ' . $member->last_name
                                                ]);
                                            })
                                            ->searchable()
                                            ->preload(),
                                    ]),
                            ]),

                        Section::make('Preacher Information')
                            ->compact()
                            ->columns(2)
                            ->schema([
                                Forms\Components\Select::make('preacher_type')
                                    ->label('Preacher Type')
                                    ->options([
                                        'local' => 'Local Preacher',
                                        'visiting' => 'Visiting Preacher',
                                    ])
                                    ->required()
                                    ->live()
                                    ->columnSpan(1),

                                Forms\Components\Select::make('preacher_id')
                                    ->label('Local Preacher')
                                    ->options(function () {
                                        return Member::query()
                                            ->get()
                                            ->mapWithKeys(fn ($member) => [
                                                $member->id => $member->title . ' ' . $member->first_name . ' ' . $member->last_name
                                            ]);
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn (Get $get) => $get('preacher_type') === 'local')
                                    ->columnSpan(1),

                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('visiting_preacher_name')
                                            ->required(fn (Get $get) => $get('preacher_type') === 'visiting'),
                                        Forms\Components\TextInput::make('visiting_preacher_church')
                                            ->required(fn (Get $get) => $get('preacher_type') === 'visiting'),
                                        Forms\Components\TextInput::make('visiting_preacher_city')
                                            ->required(fn (Get $get) => $get('preacher_type') === 'visiting'),
                                        Forms\Components\TextInput::make('visiting_preacher_country')
                                            ->required(fn (Get $get) => $get('preacher_type') === 'visiting'),
                                        Forms\Components\TextInput::make('visiting_preacher_phone')
                                            ->tel()
                                            ->required(fn (Get $get) => $get('preacher_type') === 'visiting'),
                                    ])
                                    ->visible(fn (Get $get) => $get('preacher_type') === 'visiting')
                                    ->columnSpan(2),
                            ]),

                        Forms\Components\Select::make('sunday_school_teacher_id')
                            ->label('Sunday School Teacher')
                            ->options(function () {
                                return Member::all()->mapWithKeys(fn ($member) => [
                                    $member->id => $member->title . ' ' . $member->first_name . ' ' . $member->last_name
                                ]);
                            })
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('announcer_id')
                            ->label('Church Programs Announcer')
                            ->options(function () {
                                return Member::all()->mapWithKeys(fn ($member) => [
                                    $member->id => $member->title . ' ' . $member->first_name . ' ' . $member->last_name
                                ]);
                            })
                            ->searchable()
                            ->preload(),
                    ]),

                Grid::make(2)
                    ->schema([
                        Section::make('Message Details')
                            ->description('Sermon information and scripture references')
                            ->icon('heroicon-o-document-text')
                            ->collapsible()
                            ->schema([
                                Forms\Components\TextInput::make('message_title')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('bible_reading')
                                    ->placeholder('Enter bible references...')
                                    ->rows(3),
                            ]),

                        Section::make('Attendance & Financial Information')
                            ->description('Track attendance and offerings')
                            ->icon('heroicon-o-chart-bar')
                            ->collapsible()
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('total_attendance')
                                            ->label('Total Attendance')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),
                                        Forms\Components\TextInput::make('total_members')
                                            ->label('Members')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),
                                        Forms\Components\TextInput::make('total_visitors')
                                            ->label('Visitors')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),
                                        Forms\Components\TextInput::make('total_children')
                                            ->label('Children')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),
                                    ]),
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('offering_amount')
                                            ->numeric()
                                            ->prefix('ZMW')
                                            ->default(0)
                                            ->required(),
                                        Forms\Components\TextInput::make('tithe_amount')
                                            ->numeric()
                                            ->prefix('ZMW')
                                            ->default(0)
                                            ->required(),
                                    ]),
                            ]),
                    ]),

                Section::make('Media Resources')
                    ->description('Upload media files and stream links')
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->schema([
                        Forms\Components\FileUpload::make('service_banner')
                            ->image()
                            ->directory('service-banners')
                            ->imageEditor()
                            ->columnSpanFull(),
                        Grid::make(3)
                            ->schema([
                                Forms\Components\FileUpload::make('audio_recording')
                                    ->acceptedFileTypes(['audio/mpeg', 'audio/wav'])
                                    ->directory('service-recordings'),
                                Forms\Components\TextInput::make('facebook_stream_link')
                                    ->url()
                                    ->prefix('https://')
                                    ->placeholder('Facebook stream URL'),
                                Forms\Components\TextInput::make('youtube_stream_link')
                                    ->url()
                                    ->prefix('https://')
                                    ->placeholder('YouTube stream URL'),
                            ]),
                    ]),

                Section::make('Status & Notes')
                    ->description('Service status and additional information')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'Scheduled' => 'Scheduled',
                                'In Progress' => 'In Progress',
                                'Completed' => 'Completed',
                                'Cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('Scheduled'),
                        Forms\Components\Textarea::make('notes')
                            ->placeholder('Enter any additional notes...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
         
            ->columns([
                Tables\Columns\TextColumn::make('branch.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('service_banner')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-banner.jpg')),
                Tables\Columns\TextColumn::make('service_type')
                    ->badge()
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
                        'Sunday Service' => 'success',
                        'Midweek Service' => 'info',
                        'Special Service' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('service_name')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->time()
                    ->sortable(),
                // Combined preacher information
                Tables\Columns\TextColumn::make('preacher_type')
                    ->label('Preacher')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record) return 'N/A';
                        
                        if ($record->preacher_type === 'visiting' && $record->visiting_preacher_name) {
                            return "Visiting: {$record->visiting_preacher_name}" . 
                                   ($record->visiting_preacher_church ? " ({$record->visiting_preacher_church})" : '');
                        }
                        
                        if ($record->preacher_type === 'local' && $record->preacher) {
                            return "Local: {$record->preacher->title} {$record->preacher->first_name} {$record->preacher->last_name}";
                        }
                        
                        return 'Not Assigned';
                    })
                    ->wrap(),
                // Message Details
                Tables\Columns\TextColumn::make('message_title')
                ->label('Message')
                ->tooltip(fn (Service $record): ?string => 
                    $record->bible_reading ? "Scripture: {$record->bible_reading}" : null
                )
                ->wrap(),
                // Attendance & Offering Summary
                Tables\Columns\TextColumn::make('total_attendance')
                    ->numeric()
                    ->sortable()
                    ->description(fn (Service $record): string => 
                        "Members: {$record->total_members} | Visitors: {$record->total_visitors} | Children: {$record->total_children}"
                    ),
                Tables\Columns\TextColumn::make('offering_amount')
                    ->money('ZMW')
                    ->sortable(),
                // Media Indicators
                Tables\Columns\IconColumn::make('audio_recording')
                    ->boolean()
                    ->label('Recording')
                    ->tooltip(fn (Service $record) => 
                        $record->audio_recording ? 'Audio recording available' : 'No audio recording'
                    ),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Completed' => 'success',
                        'In Progress' => 'info',
                        'Scheduled' => 'warning',
                        'Cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('date', 'desc')
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
                        'Prayer Meeting' => 'Prayer Meeting',
                        'Bible Study' => 'Bible Study',
                        'Youth Service' => 'Youth Service',
                        'Children Service' => 'Children Service',
                    ])
                    ->multiple(),
                SelectFilter::make('preacher_type')
                    ->options([
                        'local' => 'Local Preacher',
                        'visiting' => 'Visiting Preacher',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'Scheduled' => 'Scheduled',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
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
                Filter::make('has_recording')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('audio_recording')),
                Filter::make('has_streams')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('facebook_stream_link')
                        ->orWhereNotNull('youtube_stream_link')),
            ])
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('download_report')
                ->icon('heroicon-o-document-text')
                ->label('Download Report')
                ->action(function (Service $record) {
                    $pdf = Pdf::loadView('pdf.service-report', ['service' => $record]);
                    
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        "service-report-{$record->date->format('Y-m-d')}.pdf"
                    );
                }),
                // Download Audio Action
                Tables\Actions\Action::make('download_audio')
                    ->icon('heroicon-o-musical-note')
                    ->label('Download Audio')
                    ->visible(fn (Service $record): bool => $record->audio_recording !== null)
                    ->url(fn (Service $record): string => $record->audio_recording ? Storage::url($record->audio_recording) : '')
                    ->openUrlInNewTab(),
                // View Banner Action
                Tables\Actions\Action::make('view_banner')
                    ->icon('heroicon-o-photo')
                    ->label('View Banner')
                    ->visible(fn (Service $record): bool => $record->service_banner !== null)
                    ->url(fn (Service $record): string => $record->service_banner ? Storage::url($record->service_banner) : '')
                    ->openUrlInNewTab(),
                // Stream Links Dropdown
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('facebook_stream')
                        ->icon('heroicon-o-video-camera')
                        ->label('Facebook Stream')
                        ->visible(fn (Service $record): bool => !empty($record->facebook_stream_link))
                        ->url(fn (Service $record): string => $record->facebook_stream_link)
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('youtube_stream')
                        ->icon('heroicon-o-video-camera')
                        ->label('YouTube Stream')
                        ->visible(fn (Service $record): bool => !empty($record->youtube_stream_link))
                        ->url(fn (Service $record): string => $record->youtube_stream_link)
                        ->openUrlInNewTab(),
                ])
                ->label('Streams')
                ->icon('heroicon-m-play')
                ->visible(fn (Service $record): bool => 
                    !empty($record->facebook_stream_link) || 
                    !empty($record->youtube_stream_link)
                ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No services yet')
            ->emptyStateDescription('Once you create services, they will appear here.')
            ->emptyStateIcon('heroicon-o-building-library')
            ->striped()
            ->defaultSort('date', 'desc');
    }

    // Helper function for storage URLs
    protected static function storage_url($path)
    {
        if (!$path) return null;
        return Storage::url($path);
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'view' => Pages\ViewService::route('/{record}'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}