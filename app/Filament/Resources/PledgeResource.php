<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PledgeResource\Pages;
use App\Models\Pledge;
use App\Models\Member;
use App\Models\Branch;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms\Get;

class PledgeResource extends Resource
{
    protected static ?string $model = Pledge::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Income Management';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pledger Information')
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
                            ->disabled(fn (Get $get) => !empty($get('member_id')))
                            ->helperText('Auto-filled if member selected'),

                        Forms\Components\TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->disabled(fn (Get $get) => !empty($get('member_id'))),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pledge Details')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label('Project (Optional)')
                            ->options(Project::active()->get()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->helperText('Link this pledge to a specific project'),

                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Pledge Amount (K)')
                            ->numeric()
                            ->required()
                            ->prefix('K')
                            ->step(0.01)
                            ->minValue(0)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Get $get) {
                                $frequency = $get('frequency');
                                if ($frequency === 'one-time') {
                                    $set('frequency_amount', $state);
                                }
                            }),

                        Forms\Components\Select::make('frequency')
                            ->options([
                                'one-time' => 'One-time Payment',
                                'weekly' => 'Weekly',
                                'bi-weekly' => 'Bi-weekly',
                                'monthly' => 'Monthly',
                                'quarterly' => 'Quarterly',
                                'yearly' => 'Yearly',
                            ])
                            ->required()
                            ->default('one-time')
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Get $get) {
                                $totalAmount = $get('total_amount');
                                if ($state === 'one-time' && $totalAmount) {
                                    $set('frequency_amount', $totalAmount);
                                } else {
                                    $set('frequency_amount', null);
                                }
                            }),

                        Forms\Components\TextInput::make('frequency_amount')
                            ->label('Amount Per Payment (K)')
                            ->numeric()
                            ->prefix('K')
                            ->step(0.01)
                            ->minValue(0)
                            ->disabled(fn (Get $get) => $get('frequency') === 'one-time')
                            ->required(fn (Get $get) => $get('frequency') !== 'one-time')
                            ->helperText('Amount to be paid each frequency period'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Timeline & Status')
                    ->schema([
                        Forms\Components\DatePicker::make('pledge_date')
                            ->required()
                            ->default(today()),

                        Forms\Components\DatePicker::make('target_completion_date')
                            ->after('pledge_date')
                            ->helperText('Expected completion date (optional)'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'completed' => 'Completed',
                                'defaulted' => 'Defaulted',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('active')
                            ->required(),

                        Forms\Components\TextInput::make('received_amount')
                            ->label('Amount Received (K)')
                            ->numeric()
                            ->prefix('K')
                            ->step(0.01)
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Calculated from income records'),
                    ])
                    ->columns(2),

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
                Tables\Columns\TextColumn::make('pledger_name')
                    ->label('Pledger')
                    ->searchable(['name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('General Pledge'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Pledged')
                    ->money('ZMW')
                    ->sortable(),

                Tables\Columns\TextColumn::make('received_amount')
                    ->label('Received')
                    ->money('ZMW')
                    ->sortable(),

                Tables\Columns\TextColumn::make('completion_percentage')
                    ->label('Progress')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->color(fn ($state) => match(true) {
                        $state >= 100 => 'success',
                        $state >= 75 => 'info',
                        $state >= 50 => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'primary' => 'completed',
                        'danger' => 'defaulted',
                        'gray' => 'cancelled',
                    ]),

                Tables\Columns\TextColumn::make('frequency')
                    ->badge()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_overdue')
                    ->label('Overdue')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('pledge_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->options(Branch::pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(Project::pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'defaulted' => 'Defaulted',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('frequency')
                    ->options([
                        'one-time' => 'One-time',
                        'weekly' => 'Weekly',
                        'bi-weekly' => 'Bi-weekly',
                        'monthly' => 'Monthly',
                        'quarterly' => 'Quarterly',
                        'yearly' => 'Yearly',
                    ]),

                Tables\Filters\Filter::make('overdue')
                    ->label('Overdue Pledges')
                    ->query(fn ($query) => $query->where('target_completion_date', '<', now())
                                                 ->where('status', 'active')
                                                 ->whereColumn('received_amount', '<', 'total_amount')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_completed')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Pledge $record) => $record->status === 'active' && $record->remaining_amount <= 0)
                    ->requiresConfirmation()
                    ->action(function (Pledge $record) {
                        $record->update(['status' => 'completed']);
                    }),
                Tables\Actions\Action::make('mark_defaulted')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Pledge $record) => $record->status === 'active')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Reason for defaulting')
                            ->required(),
                    ])
                    ->action(function (Pledge $record, array $data) {
                        $record->markAsDefaulted($data['reason']);
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
                Infolists\Components\Section::make('Pledger Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('pledger_name')
                            ->label('Pledger Name'),
                        Infolists\Components\TextEntry::make('pledger_phone')
                            ->label('Phone Number'),
                        Infolists\Components\TextEntry::make('branch.name')
                            ->label('Branch'),
                        Infolists\Components\TextEntry::make('member.full_name')
                            ->label('Church Member')
                            ->placeholder('Non-member'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Pledge Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('project.name')
                            ->label('Project')
                            ->placeholder('General Pledge'),
                        Infolists\Components\TextEntry::make('total_amount')
                            ->label('Total Pledged')
                            ->money('ZMW'),
                        Infolists\Components\TextEntry::make('frequency')
                            ->badge(),
                        Infolists\Components\TextEntry::make('frequency_amount')
                            ->label('Amount Per Payment')
                            ->money('ZMW'),
                        Infolists\Components\TextEntry::make('expected_payments_count')
                            ->label('Expected Payments'),
                        Infolists\Components\TextEntry::make('actual_payments_count')
                            ->label('Actual Payments'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Progress & Status')
                    ->schema([
                        Infolists\Components\TextEntry::make('received_amount')
                            ->label('Amount Received')
                            ->money('ZMW'),
                        Infolists\Components\TextEntry::make('remaining_amount')
                            ->label('Remaining Amount')
                            ->money('ZMW'),
                        Infolists\Components\TextEntry::make('completion_percentage')
                            ->label('Progress')
                            ->formatStateUsing(fn ($state) => $state . '%'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'completed' => 'primary',
                                'defaulted' => 'danger',
                                'cancelled' => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('last_payment_date')
                            ->label('Last Payment')
                            ->date(),
                        Infolists\Components\TextEntry::make('next_payment_due_date')
                            ->label('Next Payment Due')
                            ->date(),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Timeline')
                    ->schema([
                        Infolists\Components\TextEntry::make('pledge_date')
                            ->date(),
                        Infolists\Components\TextEntry::make('target_completion_date')
                            ->date()
                            ->placeholder('No target date'),
                        Infolists\Components\TextEntry::make('is_overdue')
                            ->label('Overdue')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                            ->color(fn ($state) => $state ? 'danger' : 'success'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->collapsed()
                    ->visible(fn (Pledge $record) => $record->notes),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPledges::route('/'),
            'create' => Pages\CreatePledge::route('/create'),
            //'view' => Pages\ViewPledge::route('/{record}'),
            'edit' => Pages\EditPledge::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::active()->count();
    }
}
