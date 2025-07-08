<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnershipResource\Pages;
use App\Models\Partnership;
use App\Models\Member;
use App\Models\Branch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Storage;

class PartnershipResource extends Resource
{
    protected static ?string $model = Partnership::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Income Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Partner Information')
                    ->schema([
                        Forms\Components\Select::make('branch_id')
                            ->label('Branch')
                            ->options(Branch::pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('member_id')
                            ->label('Church Member')
                            ->options(Member::active()->get()->pluck('full_name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $member = Member::find($state);
                                    if ($member) {
                                        $set('name', $member->full_name);
                                        $set('phone_number', $member->phone);
                                    }
                                }
                            })
                            ->helperText('Select a member or leave blank for non-member'),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live()
                            ->disabled(fn (Get $get) => !empty($get('member_id')))
                            ->helperText('Auto-filled if member selected, or enter manually for non-members'),

                        Forms\Components\TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->disabled(fn (Get $get) => !empty($get('member_id')))
                            ->helperText('Auto-filled if member selected, or enter manually for non-members'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Partnership Details')
                    ->schema([
                        Forms\Components\TextInput::make('monthly_amount')
                            ->label('Monthly Partnership Amount (K)')
                            ->numeric()
                            ->required()
                            ->prefix('K')
                            ->step(0.01)
                            ->minValue(0),

                        Forms\Components\DatePicker::make('start_date')
                            ->required()
                            ->default(today()),

                        Forms\Components\DatePicker::make('end_date')
                            ->after('start_date')
                            ->helperText('Leave blank for ongoing partnership'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'terminated' => 'Terminated',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Agreement Document')
                    ->schema([
                        Forms\Components\FileUpload::make('agreement_file')
                            ->label('Partnership Agreement')
                            ->directory('partnerships')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(5120) // 5MB
                            ->downloadable()
                            ->openable()
                            ->helperText('Upload the signed partnership agreement (PDF or Image, max 5MB)'),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->maxLength(1000),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contributor_name')
                    ->label('Partner Name')
                    ->searchable(['name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('contributor_phone')
                    ->label('Phone')
                    ->searchable(['phone_number'])
                    ->toggleable(),

                Tables\Columns\TextColumn::make('monthly_amount')
                    ->label('Monthly Amount')
                    ->money('ZMW')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_contributed')
                    ->label('Total Contributed')
                    ->money('ZMW')
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_contribution_date')
                    ->label('Last Payment')
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'inactive',
                        'danger' => 'terminated',
                    ]),

                Tables\Columns\IconColumn::make('agreement_file')
                    ->label('Agreement')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-check')
                    ->falseIcon('heroicon-o-document-minus'),

                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->options(Branch::pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'terminated' => 'Terminated',
                    ]),

                Tables\Filters\Filter::make('has_agreement')
                    ->label('Has Agreement')
                    ->query(fn ($query) => $query->whereNotNull('agreement_file')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download_agreement')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (Partnership $record) => $record->agreement_file)
                    ->url(fn (Partnership $record) => Storage::url($record->agreement_file))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('mark_inactive')
                    ->icon('heroicon-o-pause')
                    ->color('warning')
                    ->visible(fn (Partnership $record) => $record->status === 'active')
                    ->requiresConfirmation()
                    ->action(function (Partnership $record) {
                        $record->update(['status' => 'inactive']);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Partner Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('contributor_name')
                            ->label('Partner Name'),
                        Infolists\Components\TextEntry::make('contributor_phone')
                            ->label('Phone Number'),
                        Infolists\Components\TextEntry::make('branch.name')
                            ->label('Branch'),
                        Infolists\Components\TextEntry::make('member.full_name')
                            ->label('Church Member')
                            ->placeholder('Non-member'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Partnership Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('monthly_amount')
                            ->label('Monthly Amount')
                            ->money('ZMW'),
                        Infolists\Components\TextEntry::make('start_date')
                            ->date(),
                        Infolists\Components\TextEntry::make('end_date')
                            ->date()
                            ->placeholder('Ongoing'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'warning',
                                'terminated' => 'danger',
                            }),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Financial Summary')
                    ->schema([
                        Infolists\Components\TextEntry::make('total_contributed')
                            ->label('Total Contributed')
                            ->money('ZMW'),
                        Infolists\Components\TextEntry::make('expected_total')
                            ->label('Expected Total')
                            ->money('ZMW'),
                        Infolists\Components\TextEntry::make('contribution_variance')
                            ->label('Variance')
                            ->money('ZMW')
                            ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),
                        Infolists\Components\TextEntry::make('last_contribution_date')
                            ->label('Last Payment')
                            ->date(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Agreement')
                    ->schema([
                        Infolists\Components\TextEntry::make('agreement_file')
                            ->label('Agreement File')
                            ->formatStateUsing(fn ($state) => $state ? 'Available' : 'Not uploaded')
                            ->color(fn ($state) => $state ? 'success' : 'warning'),
                    ])
                    ->visible(fn (Partnership $record) => $record->agreement_file),

                Infolists\Components\Section::make('Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->collapsed()
                    ->visible(fn (Partnership $record) => $record->notes),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerships::route('/'),
            'create' => Pages\CreatePartnership::route('/create'),
            //'view' => Pages\ViewPartnership::route('/{record}'),
            'edit' => Pages\EditPartnership::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::active()->count();
    }
}
