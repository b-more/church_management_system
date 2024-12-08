<?php

namespace App\Filament\Resources\AttendanceStatisticResource\Pages;

use App\Filament\Resources\AttendanceStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceStatistic extends EditRecord
{
    protected static string $resource = AttendanceStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
