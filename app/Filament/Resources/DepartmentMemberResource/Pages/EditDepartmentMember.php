<?php

namespace App\Filament\Resources\DepartmentMemberResource\Pages;

use App\Filament\Resources\DepartmentMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepartmentMember extends EditRecord
{
    protected static string $resource = DepartmentMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
