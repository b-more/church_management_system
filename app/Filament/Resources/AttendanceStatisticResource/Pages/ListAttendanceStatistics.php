<?php

namespace App\Filament\Resources\AttendanceStatisticResource\Pages;

use App\Filament\Resources\AttendanceStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceStatistics extends ListRecords
{
    protected static string $resource = AttendanceStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
