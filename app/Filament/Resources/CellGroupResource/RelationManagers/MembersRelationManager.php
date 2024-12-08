<?php

namespace App\Filament\Resources\CellGroupResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use App\Models\Member;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';
    protected static ?string $title = 'Cell Group Members';
    protected static ?string $recordTitleAttribute = 'first_name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('registration_number')
                ->label('Registration Number')
                ->disabled()
                ->dehydrated(false) // Don't include in form submission
                ->default(function () {
                    $latestMember = Member::withTrashed()->latest('id')->first();
                    $nextId = $latestMember ? $latestMember->id + 1 : 1;
                    return 'HKC-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
                })
                ->helperText('Automatically generated upon saving'),
                Grid::make(3)
                    ->schema([
                        Select::make('title')
                            ->options([
                                'Mr.' => 'Mr.',
                                'Mrs.' => 'Mrs.',
                                'Miss' => 'Miss',
                                'Dr.' => 'Dr.',
                                'Rev.' => 'Rev.',
                                'Pastor' => 'Pastor',
                                'Apostle' => 'Apostle',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(100),
                        Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),
                Grid::make(3)
                    ->schema([
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->required()
                            ->maxDate(now()),
                        Select::make('gender')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                                'Other' => 'Other',
                            ])
                            ->required(),
                        Select::make('marital_status')
                            ->options([
                                'Single' => 'Single',
                                'Married' => 'Married',
                                'Divorced' => 'Divorced',
                                'Widowed' => 'Widowed',
                            ])
                            ->required(),
                    ]),
                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email(),
                    ]),
                Forms\Components\Textarea::make('address')
                    ->required()
                    ->columnSpanFull(),
                Grid::make(3)
                    ->schema([
                        Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('membership_status')
                            ->options([
                                'First Timer' => 'First Timer',
                                'New Convert' => 'New Convert',
                                'Regular Member' => 'Regular Member',
                                'Worker' => 'Worker',
                                'Leader' => 'Leader',
                            ])
                            ->required(),
                        Forms\Components\DatePicker::make('membership_date')
                            ->required(),
                    ]),
                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('occupation'),
                        Forms\Components\TextInput::make('employer'),
                    ]),
                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
{
    return $table
        ->recordTitleAttribute('first_name')
        ->columns([
            Tables\Columns\TextColumn::make('registration_number')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('full_name')
                ->searchable(['first_name', 'last_name'])
                ->sortable()
                ->formatStateUsing(fn ($record) => 
                    $record->title . ' ' . $record->first_name . ' ' . $record->last_name),
            Tables\Columns\TextColumn::make('branch.name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('phone')
                ->searchable(),
            Tables\Columns\TextColumn::make('membership_status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'First Timer' => 'gray',
                    'New Convert' => 'info',
                    'Regular Member' => 'success',
                    'Worker' => 'warning',
                    'Leader' => 'danger',
                }),
            Tables\Columns\IconColumn::make('is_active')
                ->boolean(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('branch')
                ->relationship('branch', 'name'),
            Tables\Filters\SelectFilter::make('membership_status')
                ->options([
                    'First Timer' => 'First Timer',
                    'New Convert' => 'New Convert',
                    'Regular Member' => 'Regular Member',
                    'Worker' => 'Worker',
                    'Leader' => 'Leader',
                ]),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make()
                ->before(function (array $data) {
                    return $data;
                }),
            Tables\Actions\AttachAction::make(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DetachAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DetachBulkAction::make(),
            ]),
        ]);
}
}