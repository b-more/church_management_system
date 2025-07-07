<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.organized-dashboard';

    // Override to prevent default widgets from loading
    public function getWidgets(): array
    {
        return [];
    }

    public function getColumns(): int|string|array
    {
        return 1;
    }
}
