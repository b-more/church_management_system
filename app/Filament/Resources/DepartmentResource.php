<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?int $navigationSort = 25;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')->schema([
                Grid::make(3)->schema([
                    Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required(),
                    Forms\Components\TextInput::make('name')
                        ->required(),
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->unique(ignoreRecord: true),
                ]),
                Forms\Components\Textarea::make('description')
                    ->rows(3),
            ]),

            Section::make('Leadership')->schema([
                Grid::make(3)->schema([
                    Select::make('head_id')
                        ->relationship('head', 'first_name')
                        ->searchable(),
                    Select::make('assistant_head_id')
                        ->relationship('assistantHead', 'first_name')
                        ->searchable(),
                    Select::make('reports_to')
                        ->relationship('superiorDepartment', 'name'),
                ]),
            ]),

            Section::make('Classification')->schema([
                Grid::make(2)->schema([
                    Select::make('type')
                        ->options([
                            'Ministry' => 'Ministry',
                            'Administrative' => 'Administrative',
                            'Service' => 'Service',
                        ])
                        ->required(),
                    Select::make('category')
                        ->options([
                            'Worship' => 'Worship',
                            'Ushering' => 'Ushering',
                            'Protocol' => 'Protocol',
                            'Children' => 'Children',
                            'Youth' => 'Youth',
                            'Men' => 'Men',
                            'Women' => 'Women',
                            'Media' => 'Media',
                            'Finance' => 'Finance',
                            'Other' => 'Other',
                        ])
                        ->required(),
                ]),
            ]),

            Section::make('Operations')->schema([
                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('meeting_schedule'),
                    Forms\Components\TextInput::make('budget_allocation')
                        ->numeric()
                        ->prefix('ZMW'),
                ]),
                Forms\Components\Textarea::make('responsibilities')
                    ->rows(3),
                Forms\Components\Textarea::make('requirements')
                    ->rows(3),
            ]),

            Section::make('Status')->schema([
                Grid::make(2)->schema([
                    Select::make('status')
                        ->options([
                            'Active' => 'Active',
                            'Inactive' => 'Inactive',
                        ])
                        ->required(),
                    Forms\Components\Textarea::make('notes')
                        ->rows(3),
                ]),
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
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('head.first_name')
                    ->label('Department Head'),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('category')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('budget_allocation')
                    ->money('zmw')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('branch')
                    ->relationship('branch', 'name'),
                Tables\Filters\SelectFilter::make('type'),
                Tables\Filters\SelectFilter::make('category'),
                Tables\Filters\SelectFilter::make('status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            //'view' => Pages\ViewDepartment::route('/{record}'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
