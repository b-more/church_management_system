<?php

namespace App\Filament\Resources\IncomeResource\Pages;

use App\Filament\Resources\IncomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncomes extends ListRecords
{
    protected static string $resource = IncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Record Income')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\IncomeStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\IncomeChart::class,
            \App\Filament\Widgets\RecentIncomeTable::class,
        ];
    }

    public function getTitle(): string
    {
        return 'Income Management';
    }

    public function getSubheading(): string
    {
        return 'Track and manage all church income transactions';
    }
}
