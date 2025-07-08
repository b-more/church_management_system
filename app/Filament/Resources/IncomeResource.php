<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomeResource\Pages;
use App\Models\Income;
use App\Models\Member;
use App\Models\Branch;
use App\Models\OfferingType;
use App\Models\Project;
use App\Models\Pledge;
use App\Models\Partnership;
use App\Services\SmsService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use App\Filament\Widgets\IncomeStatsOverview;
use App\Filament\Widgets\IncomeChart;
use App\Filament\Widgets\RecentIncomeTable;

class IncomeResource extends Resource
{
    protected static ?string $model = Income::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Income Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Income Details')
                    ->schema([
                        Forms\Components\Select::make('branch_id')
                            ->label('Branch')
                            ->options(Branch::pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('offering_type_id')
                            ->label('Offering Type')
                            ->options(OfferingType::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                // Reset dependent fields when offering type changes
                                $set('project_id', null);
                                $set('pledge_id', null);
                                $set('partnership_id', null);
                            }),

                        Forms\Components\TextInput::make('amount')
                            ->label('Amount (K)')
                            ->numeric()
                            ->required()
                            ->prefix('K')
                            ->step(0.01)
                            ->minValue(0),

                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->default(today()),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Contributor Information')
                    ->schema([
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
                                } else {
                                    $set('name', null);
                                    $set('phone_number', null);
                                }
                            })
                            ->helperText('Leave blank for anonymous contributions'),

                        Forms\Components\TextInput::make('name')
                            ->maxLength(255)
                            ->disabled(fn (Get $get) => !empty($get('member_id')))
                            ->helperText('Auto-filled if member selected, or enter for non-members'),

                        Forms\Components\TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(20)
                            ->disabled(fn (Get $get) => !empty($get('member_id'))),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Linked Records')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label('Project')
                            ->options(function (Get $get) {
                                $offeringType = OfferingType::find($get('offering_type_id'));
                                if ($offeringType && $offeringType->name === 'Projects') {
                                    return Project::active()->pluck('name', 'id');
                                }
                                return [];
                            })
                            ->searchable()
                            ->visible(function (Get $get) {
                                $offeringType = OfferingType::find($get('offering_type_id'));
                                return $offeringType && $offeringType->name === 'Projects';
                            }),

                        Forms\Components\Select::make('pledge_id')
                            ->label('Pledge')
                            ->options(function (Get $get) {
                                $memberId = $get('member_id');
                                $query = Pledge::active();

                                if ($memberId) {
                                    $query->where('member_id', $memberId);
                                }

                                return $query->get()->map(function ($pledge) {
                                    return [
                                        'id' => $pledge->id,
                                        'label' => $pledge->pledger_name . ' - K' . number_format($pledge->total_amount, 2) . ' (' . ucfirst($pledge->frequency) . ')'
                                    ];
                                })->pluck('label', 'id');
                            })
                            ->searchable()
                            ->helperText('Link to existing pledge'),

                        Forms\Components\Select::make('partnership_id')
                            ->label('Partnership')
                            ->options(function (Get $get) {
                                $offeringType = OfferingType::find($get('offering_type_id'));
                                $memberId = $get('member_id');

                                if ($offeringType && $offeringType->name === 'Financial Partnership') {
                                    $query = Partnership::active();

                                    if ($memberId) {
                                        $query->where('member_id', $memberId);
                                    }

                                    return $query->get()->map(function ($partnership) {
                                        return [
                                            'id' => $partnership->id,
                                            'label' => $partnership->contributor_name . ' - K' . number_format($partnership->monthly_amount, 2) . '/month'
                                        ];
                                    })->pluck('label', 'id');
                                }
                                return [];
                            })
                            ->searchable()
                            ->visible(function (Get $get) {
                                $offeringType = OfferingType::find($get('offering_type_id'));
                                return $offeringType && $offeringType->name === 'Financial Partnership';
                            }),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Payment Information')
                    ->schema([
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'cash' => 'Cash',
                                'bank_transfer' => 'Bank Transfer',
                                'mobile_money' => 'Mobile Money',
                                'check' => 'Check',
                                'card' => 'Card Payment',
                                'other' => 'Other',
                            ])
                            ->default('cash'),

                        Forms\Components\TextInput::make('reference_number')
                            ->label('Reference Number')
                            ->maxLength(255)
                            ->helperText('Transaction reference, check number, etc.'),

                        Forms\Components\Textarea::make('narration')
                            ->rows(2)
                            ->maxLength(500)
                            ->helperText('Additional notes about this income'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Notification')
                    ->schema([
                        Forms\Components\Toggle::make('send_sms')
                            ->label('Send SMS Notification')
                            ->default(false)
                            ->helperText('Send SMS confirmation to contributor')
                            ->visible(fn (Get $get) => !empty($get('phone_number'))),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contributor_name')
                    ->label('Contributor')
                    ->searchable(['name'])
                    ->sortable()
                    ->placeholder('Anonymous'),

                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('offeringType.name')
                    ->label('Type')
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('amount')
                    ->money('ZMW')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()
                        ->money('ZMW')),

                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->toggleable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('recordedBy.name')
                    ->label('Recorded By')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recorded At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->options(Branch::pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('offering_type_id')
                    ->label('Offering Type')
                    ->options(OfferingType::pluck('name', 'id')),

                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('to')
                            ->label('To Date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($query, $date) => $query->whereDate('date', '>=', $date))
                            ->when($data['to'], fn ($query, $date) => $query->whereDate('date', '<=', $date));
                    }),

                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'bank_transfer' => 'Bank Transfer',
                        'mobile_money' => 'Mobile Money',
                        'check' => 'Check',
                        'card' => 'Card Payment',
                        'other' => 'Other',
                    ]),

                Tables\Filters\Filter::make('anonymous')
                    ->label('Anonymous Contributions')
                    ->query(fn ($query) => $query->whereNull('member_id')->whereNull('name')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('send_sms')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->color('info')
                    ->visible(fn (Income $record) => $record->contributor_phone)
                    ->requiresConfirmation()
                    ->modalHeading('Send SMS Receipt')
                    ->modalDescription('Send SMS receipt confirmation to contributor?')
                    ->action(function (Income $record) {
                        $message = "Thank you for your contribution of K" . number_format($record->amount, 2) .
                                  " to " . $record->offeringType->name .
                                  " on " . $record->date->format('d/m/Y') .
                                  ". God bless you! - " . $record->branch->name;

                        if (SmsService::send($message, $record->contributor_phone)) {
                            Notification::make()
                                ->title('SMS sent successfully')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Failed to send SMS')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_pdf')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('info')
                        ->action(function ($records) {
                            // PDF export logic will be implemented
                            Notification::make()
                                ->title('PDF export feature coming soon')
                                ->info()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Income Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('amount')
                            ->money('ZMW'),
                        Infolists\Components\TextEntry::make('date')
                            ->date(),
                        Infolists\Components\TextEntry::make('branch.name')
                            ->label('Branch'),
                        Infolists\Components\TextEntry::make('offeringType.name')
                            ->label('Offering Type')
                            ->badge(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Contributor Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('contributor_name')
                            ->label('Contributor')
                            ->placeholder('Anonymous'),
                        Infolists\Components\TextEntry::make('contributor_phone')
                            ->label('Phone Number')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('member.full_name')
                            ->label('Church Member')
                            ->placeholder('Non-member/Anonymous'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Linked Records')
                    ->schema([
                        Infolists\Components\TextEntry::make('project.name')
                            ->label('Project')
                            ->placeholder('Not linked to project'),
                        Infolists\Components\TextEntry::make('pledge.id')
                            ->label('Pledge Reference')
                            ->formatStateUsing(fn ($state) => $state ? "Pledge #$state" : null)
                            ->placeholder('Not linked to pledge'),
                        Infolists\Components\TextEntry::make('partnership.id')
                            ->label('Partnership Reference')
                            ->formatStateUsing(fn ($state) => $state ? "Partnership #$state" : null)
                            ->placeholder('Not linked to partnership'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Payment Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->badge(),
                        Infolists\Components\TextEntry::make('reference_number')
                            ->label('Reference Number')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('narration')
                            ->label('Notes')
                            ->placeholder('No additional notes'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Record Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('recordedBy.name')
                            ->label('Recorded By'),
                        Infolists\Components\TextEntry::make('recorded_at')
                            ->label('Recorded At')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('week_number')
                            ->label('Week Number'),
                        Infolists\Components\TextEntry::make('month')
                            ->label('Month'),
                        Infolists\Components\TextEntry::make('year')
                            ->label('Year'),
                    ])
                    ->columns(3)
                    ->collapsed(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIncomes::route('/'),
            'create' => Pages\CreateIncome::route('/create'),
            'view' => Pages\ViewIncome::route('/{record}'),
            'edit' => Pages\EditIncome::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\IncomeStatsOverview::class,
            \App\Filament\Widgets\IncomeChart::class,
            \App\Filament\Widgets\RecentIncomeTable::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getModel()::sum('amount'));
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Total Income: K' . number_format(static::getModel()::sum('amount'), 2);
    }
}
