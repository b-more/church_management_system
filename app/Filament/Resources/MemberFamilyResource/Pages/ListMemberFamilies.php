<?php

namespace App\Filament\Resources\MemberFamilyResource\Pages;

use App\Filament\Resources\MemberFamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberFamilies extends ListRecords
{
    protected static string $resource = MemberFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
