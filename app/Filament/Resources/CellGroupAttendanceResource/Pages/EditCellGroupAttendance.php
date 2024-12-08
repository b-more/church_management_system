<?php

namespace App\Filament\Resources\CellGroupAttendanceResource\Pages;

use App\Filament\Resources\CellGroupAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCellGroupAttendance extends EditRecord
{
    protected static string $resource = CellGroupAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
