<?php

namespace App\Filament\Resources\DutyRosterResource\Pages;

use App\Filament\Resources\DutyRosterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDutyRoster extends EditRecord
{
    protected static string $resource = DutyRosterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
