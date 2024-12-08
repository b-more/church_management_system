<?php

namespace App\Filament\Resources\CellGroupMeetingResource\Pages;

use App\Filament\Resources\CellGroupMeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCellGroupMeetings extends ListRecords
{
    protected static string $resource = CellGroupMeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
