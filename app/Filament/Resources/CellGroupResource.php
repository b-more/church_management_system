<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CellGroupResource\Pages;

use App\Models\CellGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RelationManagers\MembersRelationManager;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;

class CellGroupResource extends Resource
{
    protected static ?string $model = CellGroup::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Church Management';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->description('Enter the basic details of the cell group')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter cell group name'),
                                Select::make('status')
                                    ->options([
                                        'Active' => 'Active',
                                        'Inactive' => 'Inactive',
                                        'On Hold' => 'On Hold',
                                    ])
                                    ->required()
                                    ->default('Active'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Select::make('branch_id')
                                    ->relationship('branch', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('leader_id')
                                    ->relationship('leader', 'first_name', function ($query) {
                                        return $query->where('membership_status', 'Leader')
                                            ->orWhere('membership_status', 'Worker');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('first_name')
                                            ->required(),
                                        Forms\Components\TextInput::make('last_name')
                                            ->required(),
                                        Forms\Components\TextInput::make('phone')
                                            ->tel()
                                            ->required(),
                                    ]),
                            ]),
                    ]),

                Section::make('Meeting Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('meeting_day')
                                    ->options([
                                        'Monday' => 'Monday',
                                        'Tuesday' => 'Tuesday',
                                        'Wednesday' => 'Wednesday',
                                        'Thursday' => 'Thursday',
                                        'Friday' => 'Friday',
                                        'Saturday' => 'Saturday',
                                        'Sunday' => 'Sunday',
                                    ]),
                                Forms\Components\TimePicker::make('meeting_time'),
                                Forms\Components\TextInput::make('meeting_location')
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Brief description of the cell group'),
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
                Tables\Columns\TextColumn::make('branch.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('leader.first_name')
                    ->label('Leader')
                    ->formatStateUsing(fn ($record) => $record->leader ? 
                        $record->leader->first_name . ' ' . $record->leader->last_name : '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('meeting_day')
                    ->sortable(),
                Tables\Columns\TextColumn::make('meeting_time')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('members_count')
                    ->counts('members')
                    ->label('Members'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'Inactive',
                        'warning' => 'On Hold',
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
                        'On Hold' => 'On Hold',
                    ]),
                Tables\Filters\SelectFilter::make('branch')
                    ->relationship('branch', 'name'),
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
            //RelationManagers\MembersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCellGroups::route('/'),
            'create' => Pages\CreateCellGroup::route('/create'),
            'view' => Pages\ViewCellGroup::route('/{record}'),
            'edit' => Pages\EditCellGroup::route('/{record}/edit'),
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