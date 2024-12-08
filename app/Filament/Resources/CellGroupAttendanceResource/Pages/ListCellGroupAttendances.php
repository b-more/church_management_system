<?php

namespace App\Filament\Resources\CellGroupAttendanceResource\Pages;

use App\Filament\Resources\CellGroupAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCellGroupAttendances extends ListRecords
{
    protected static string $resource = CellGroupAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
