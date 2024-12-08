<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CellGroupAttendanceResource\Pages;
use App\Filament\Resources\CellGroupAttendanceResource\RelationManagers;
use App\Models\CellGroupAttendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CellGroupAttendanceResource extends Resource
{
    protected static ?string $model = CellGroupAttendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCellGroupAttendances::route('/'),
            'create' => Pages\CreateCellGroupAttendance::route('/create'),
            'edit' => Pages\EditCellGroupAttendance::route('/{record}/edit'),
        ];
    }
}
