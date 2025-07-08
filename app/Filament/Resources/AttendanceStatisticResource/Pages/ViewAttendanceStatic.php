<?php

namespace App\Filament\Resources\AttendanceStatisticResource\Pages;

use App\Filament\Resources\AttendanceStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAttendanceStatistic extends ViewRecord
{
    protected static string $resource = AttendanceStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    // Optional: Add any custom methods or properties here
    protected function getHeaderWidgets(): array
    {
        return [
            // Add any widgets specific to this page if needed
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Add any footer widgets if needed
        ];
    }
}
