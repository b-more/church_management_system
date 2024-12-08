<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentMemberResource\Pages;
use App\Models\DepartmentMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;

class DepartmentMemberResource extends Resource
{
    protected static ?string $model = DepartmentMember::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Church Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Member Assignment')->schema([
                Grid::make(2)->schema([
                    Select::make('department_id')
                        ->relationship('department', 'name')
                        ->required(),
                        //->searchable(),
                    Select::make('member_id')
                        ->relationship('member', 'first_name')
                        ->required(),
                        //->searchable(),
                ]),
                Grid::make(2)->schema([
                    Select::make('role')
                        ->options([
                            'Member' => 'Member',
                            'Leader' => 'Leader',
                            'Assistant' => 'Assistant',
                            'Secretary' => 'Secretary',
                            'Treasurer' => 'Treasurer',
                        ])
                        ->required(),
                    Forms\Components\DatePicker::make('joined_date')
                        ->required()
                        ->maxDate(now()),
                ]),
                Grid::make(2)->schema([
                    Select::make('status')
                        ->options([
                            'Active' => 'Active',
                            'Inactive' => 'Inactive',
                            'On Leave' => 'On Leave',
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
                Tables\Columns\TextColumn::make('department.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('member.first_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge(),
                Tables\Columns\TextColumn::make('joined_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'danger',
                        'On Leave' => 'warning',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department')
                    ->relationship('department', 'name'),
                Tables\Filters\SelectFilter::make('role'),
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
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartmentMembers::route('/'),
            'create' => Pages\CreateDepartmentMember::route('/create'),
            //'view' => Pages\ViewDepartmentMember::route('/{record}'),
            'edit' => Pages\EditDepartmentMember::route('/{record}/edit'),
        ];
    }
}