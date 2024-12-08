<?php

namespace App\Filament\Resources\GrowthTrackRecordResource\Pages;

use App\Filament\Resources\GrowthTrackRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGrowthTrackRecords extends ListRecords
{
    protected static string $resource = GrowthTrackRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
