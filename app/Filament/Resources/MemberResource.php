<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use App\Models\Branch;
use App\Models\CellGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $recordTitleAttribute = 'full_name';
    protected static ?string $navigationGroup = 'Church Management';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        Forms\Components\Hidden::make('registration_number')
                        ->default(function () {
                            $latestMember = Member::latest()->first();
                            $nextId = $latestMember ? $latestMember->id + 1 : 1;
                            return 'HKC-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
                        }),
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
                            ]),
                        Grid::make(2)
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
                            ]),
                    ]),

                Section::make('Contact Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->prefix('+26')
                                    ->placeholder('09XXXXXXXX')
                                    ->length(10)
                                    ->telRegex('/^[0-9]{10}$/'),
                                Forms\Components\TextInput::make('alternative_phone')
                                    ->tel()
                                    ->prefix('+26')
                                    ->placeholder('09XXXXXXXX')
                                    ->length(10)
                                    ->telRegex('/^[0-9]{10}$/'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->unique(ignoreRecord: true),
                            ]),
                        Forms\Components\Textarea::make('address')
                            ->required()
                            ->rows(3),
                    ]),

                Section::make('Church Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('branch_id')
                                    ->relationship('branch', 'name')
                                    ->required()
                                    ->searchable(),
                                Forms\Components\TextInput::make('registration_number')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->prefix('REG NO.')
                                    ->disabled()
                                    ->default(function () {
                                        return 'HKC-' . str_pad(Member::max('id') + 1, 6, '0', STR_PAD_LEFT);
                                    }),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Select::make('membership_status')
                                    ->options([
                                        'First Timer' => 'First Timer',
                                        'New Convert' => 'New Convert',
                                        'Regular Member' => 'Regular Member',
                                        'Kingdom Worker' => 'Kingdom Worker', // Added case
                                        'Leader' => 'Leader',
                                        'Pastor' => 'Pastor',
                                        //'Overseer' => 'Overseer',
                                    ])
                                    ->required(),
                                Forms\Components\DatePicker::make('membership_date')
                                    ->required()
                                    ->maxDate(now()),
                                Select::make('cell_group_id')
                                    ->relationship('cellGroup', 'name')
                                    ->searchable(),
                            ]),
                    ]),

                Section::make('Spiritual Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('salvation_date')
                                    ->maxDate(now()),
                                Forms\Components\DatePicker::make('baptism_date')
                                    ->maxDate(now()),
                                Select::make('baptism_type')
                                    ->options([
                                        'Immersion' => 'Immersion',
                                        'Sprinkle' => 'Sprinkle',
                                        'Both' => 'Both',
                                    ]),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Select::make('membership_class_status')
                                    ->options([
                                        'Not Started' => 'Not Started',
                                        'In Progress' => 'In Progress',
                                        'Completed' => 'Completed',
                                    ])
                                    ->required(),
                                Select::make('foundation_class_status')
                                    ->options([
                                        'Not Started' => 'Not Started',
                                        'In Progress' => 'In Progress',
                                        'Completed' => 'Completed',
                                    ])
                                    ->required(),
                                Select::make('leadership_class_status')
                                    ->options([
                                        'Not Started' => 'Not Started',
                                        'In Progress' => 'In Progress',
                                        'Completed' => 'Completed',
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('marital_status')
                                    ->options([
                                        'Single' => 'Single',
                                        'Married' => 'Married',
                                        'Divorced' => 'Divorced',
                                        'Widowed' => 'Widowed',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('occupation'),
                                Forms\Components\TextInput::make('employer'),
                            ]),
                    ]),

                Section::make('Emergency Contact')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('emergency_contact_name'),
                                Forms\Components\TextInput::make('emergency_contact_phone')
                                    ->tel()
                                    ->prefix('+260')
                                    ->placeholder('09XXXXXXXX')
                                    ->length(10),
                            ]),
                    ]),

                Section::make('Previous Church Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('previous_church'),
                                Forms\Components\TextInput::make('previous_church_pastor'),
                            ]),
                    ]),

                Section::make('Skills & Interests')
                    ->schema([
                        Forms\Components\Textarea::make('skills_talents')
                            ->rows(3),
                        Forms\Components\Textarea::make('interests')
                            ->rows(3),
                        Forms\Components\Textarea::make('special_needs')
                            ->rows(3),
                    ]),

                Section::make('Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->required()
                                    ->default(true)
                                    ->reactive(),
                                Forms\Components\TextInput::make('deactivation_reason')
                                    ->visible(fn (Forms\Get $get) => !$get('is_active')),
                            ]),
                        Forms\Components\Textarea::make('notes')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('membership_status')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'First Timer' => 'gray',
                        'New Convert' => 'info',
                        'Regular Member' => 'success',
                        'Overseer' => 'success',
                        'Pastor' => 'success',
                        'Kingdom Worker' => 'warning', // Added case
                        'Leader' => 'danger',
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cellGroup.name')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('branch')
                    ->relationship('branch', 'name'),
                Tables\Filters\SelectFilter::make('membership_status')
                    ->options([
                        'First Timer' => 'First Timer',
                        'New Convert' => 'New Convert',
                        'Regular Member' => 'Regular Member',
                        'Kingdom Worker' => 'warning', // Added case
                        'Leader' => 'Leader',
                    ]),
                Tables\Filters\SelectFilter::make('cell_group')
                    ->relationship('cellGroup', 'name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->before(function (array $data) {
                        return $data;
                    }),
                Tables\Actions\AttachAction::make()
                    ->recordSelect(fn ($select) => $select
                        ->placeholder('Select a member')
                        ->optionsLimit(50)
                        ->searchable(['first_name', 'last_name', 'registration_number'])
                        ->getOptionLabelFromRecordUsing(fn($record) =>
                            "{$record->first_name} {$record->last_name} ({$record->registration_number})")
                    )
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
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
