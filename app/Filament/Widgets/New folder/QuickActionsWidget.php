<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions';

    protected int | string | array $columnSpan = 1;

    public function getQuickActions(): array
    {
        return [
            [
                'label' => 'Add New Member',
                'icon' => 'heroicon-o-user-plus',
                'url' => route('filament.admin.resources.members.create'),
                'color' => 'success',
                'description' => 'Register a new church member',
            ],
            [
                'label' => 'Record Attendance',
                'icon' => 'heroicon-o-clipboard-document-check',
                'url' => route('filament.admin.resources.attendance-records.create'),
                'color' => 'info',
                'description' => 'Take service attendance',
            ],
            [
                'label' => 'Add Transaction',
                'icon' => 'heroicon-o-banknotes',
                'url' => route('filament.admin.resources.transactions.create'),
                'color' => 'warning',
                'description' => 'Record tithe/offering',
            ],
            [
                'label' => 'Create Event',
                'icon' => 'heroicon-o-calendar-plus',
                'url' => route('filament.admin.resources.events.create'),
                'color' => 'primary',
                'description' => 'Schedule new event',
            ],
            [
                'label' => 'View Reports',
                'icon' => 'heroicon-o-document-chart-bar',
                'url' => '#', // You can link to a reports page
                'color' => 'secondary',
                'description' => 'Generate reports',
            ],
            [
                'label' => 'Member Follow-up',
                'icon' => 'heroicon-o-phone',
                'url' => route('filament.admin.resources.attendance-records.index', ['tableFilters[follow_up_required][value]' => true]),
                'color' => 'danger',
                'description' => 'Pending follow-ups',
            ],
        ];
    }
}
