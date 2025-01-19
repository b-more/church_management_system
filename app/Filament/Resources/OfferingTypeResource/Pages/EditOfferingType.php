<?php

namespace App\Filament\Resources\OfferingTypeResource\Pages;

use App\Filament\Resources\OfferingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOfferingType extends EditRecord
{
    protected static string $resource = OfferingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
