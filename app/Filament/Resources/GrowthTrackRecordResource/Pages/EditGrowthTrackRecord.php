<?php

namespace App\Filament\Resources\GrowthTrackRecordResource\Pages;

use App\Filament\Resources\GrowthTrackRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGrowthTrackRecord extends EditRecord
{
    protected static string $resource = GrowthTrackRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
