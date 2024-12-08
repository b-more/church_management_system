<?php

namespace App\Filament\Resources\CellGroupMeetingResource\Pages;

use App\Filament\Resources\CellGroupMeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCellGroupMeeting extends EditRecord
{
    protected static string $resource = CellGroupMeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
