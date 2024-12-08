<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CellGroupMeetingResource\Pages;
use App\Filament\Resources\CellGroupMeetingResource\RelationManagers;
use App\Models\CellGroupMeeting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CellGroupMeetingResource extends Resource
{
    protected static ?string $model = CellGroupMeeting::class;

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
            'index' => Pages\ListCellGroupMeetings::route('/'),
            'create' => Pages\CreateCellGroupMeeting::route('/create'),
            'edit' => Pages\EditCellGroupMeeting::route('/{record}/edit'),
        ];
    }
}
