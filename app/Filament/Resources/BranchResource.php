<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Filament\Resources\BranchResource\RelationManagers;
use App\Models\Branch;
use App\Models\Member;
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
use Filament\Forms\Components\Repeater;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?int $navigationSort = 20;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->description('Enter the basic details of the branch')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('branch_code')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(50),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Select::make('branch_type')
                                    ->options([
                                        'Main' => 'Main Church',
                                        'Satellite' => 'Satellite Church',
                                        'Campus' => 'Campus Church',
                                    ])
                                    ->required(),
                                Select::make('status')
                                    ->options([
                                        'Active' => 'Active',
                                        'Inactive' => 'Inactive',
                                        'Under Construction' => 'Under Construction',
                                    ])
                                    ->required()
                                    ->default('Active'),
                            ]),
                    ]),

                Section::make('Location Details')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->required()
                            ->rows(3),
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('city')
                                    ->required(),
                                Forms\Components\TextInput::make('country')
                                    ->required()
                                    ->default('Nigeria'),
                                Forms\Components\TextInput::make('gps_coordinates')
                                    ->placeholder('e.g., 6.5244° N, 3.3792° E'),
                            ]),
                    ]),

                Section::make('Contact Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->prefix('+234')
                                    ->telRegex('/^[0-9]{10}$/'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->unique(ignoreRecord: true),
                            ]),
                    ]),

                Section::make('Leadership')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('senior_pastor_id')
                                    ->label('Senior Pastor')
                                    ->relationship('seniorPastor', 'first_name', function ($query) {
                                        return $query->where('membership_status', 'Leader');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('first_name')
                                            ->required(),
                                        Forms\Components\TextInput::make('last_name')
                                            ->required(),
                                    ]),
                                Select::make('district_pastor_id')
                                    ->label('District Pastor')
                                    ->relationship('districtPastor', 'first_name', function ($query) {
                                        return $query->where('membership_status', 'Leader');
                                    })
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),

                Section::make('Church Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('founding_date')
                                    ->maxDate(now()),
                                Forms\Components\TextInput::make('seating_capacity')
                                    ->numeric()
                                    ->minValue(0),
                            ]),
                        Repeater::make('service_times')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Select::make('day')
                                            ->options([
                                                'Sunday' => 'Sunday',
                                                'Monday' => 'Monday',
                                                'Tuesday' => 'Tuesday',
                                                'Wednesday' => 'Wednesday',
                                                'Thursday' => 'Thursday',
                                                'Friday' => 'Friday',
                                                'Saturday' => 'Saturday',
                                            ])
                                            ->required(),
                                        Forms\Components\TimePicker::make('start_time')
                                            ->required(),
                                        Forms\Components\TimePicker::make('end_time')
                                            ->required(),
                                    ]),
                                Forms\Components\TextInput::make('service_name')
                                    ->required(),
                            ])
                            ->defaultItems(1)
                            ->reorderable(false),
                    ]),

                Section::make('Vision & Mission')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Textarea::make('vision')
                            ->rows(3),
                        Forms\Components\Textarea::make('mission')
                            ->rows(3),
                    ]),

                Section::make('Additional Information')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch_type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->sortable(),
                Tables\Columns\TextColumn::make('seniorPastor.first_name')
                    ->label('Senior Pastor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('members_count')
                    ->counts('members')
                    ->label('Members'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'Inactive',
                        'warning' => 'Under Construction',
                        'success' => 'Active',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Under Construction' => 'Under Construction',
                    ]),
                Tables\Filters\SelectFilter::make('branch_type')
                    ->options([
                        'Main' => 'Main Church',
                        'Satellite' => 'Satellite Church',
                        'Campus' => 'Campus Church',
                    ]),
                Tables\Filters\TrashedFilter::make(),
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
            // RelationManagers\MembersRelationManager::class,
            // RelationManagers\CellGroupsRelationManager::class,
            // RelationManagers\DepartmentsRelationManager::class,
            // RelationManagers\ServicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            //'view' => Pages\ViewBranch::route('/{record}'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
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
