<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderWidgets(): array
{
    return [
        \App\Filament\Widgets\MinistryStatsWidget::class,
    ];
}
}
