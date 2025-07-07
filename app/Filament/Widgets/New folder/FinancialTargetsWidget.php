<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class FinancialTargetsWidget extends Widget
{
    protected static string $view = 'filament.widgets.financial-targets';

    protected int | string | array $columnSpan = 1;

    protected static ?string $pollingInterval = '60s';

    public function getFinancialTargets(): array
    {
        // Define monthly targets (you can make these configurable in settings)
        $monthlyTargets = [
            'tithe' => 50000,
            'offering' => 25000,
            'special_offering' => 15000,
            'building_fund' => 30000,
        ];

        // Get current month actual amounts
        $currentMonth = Transaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->selectRaw('
                SUM(CASE WHEN transaction_type = "tithe" THEN amount ELSE 0 END) as tithe,
                SUM(CASE WHEN transaction_type = "offering" THEN amount ELSE 0 END) as offering,
                SUM(CASE WHEN transaction_type = "special_offering" THEN amount ELSE 0 END) as special_offering,
                SUM(CASE WHEN transaction_type = "building_fund" THEN amount ELSE 0 END) as building_fund
            ')
            ->first();

        // Calculate progress for each category
        $targets = [];
        foreach ($monthlyTargets as $type => $target) {
            $actual = $currentMonth->{$type} ?? 0;
            $percentage = $target > 0 ? min(100, round(($actual / $target) * 100, 1)) : 0;

            $targets[] = [
                'type' => ucfirst(str_replace('_', ' ', $type)),
                'target' => $target,
                'actual' => $actual,
                'percentage' => $percentage,
                'status' => $this->getTargetStatus($percentage),
                'remaining' => max(0, $target - $actual),
            ];
        }

        // Calculate overall progress
        $totalTarget = array_sum($monthlyTargets);
        $totalActual = array_sum([
            $currentMonth->tithe ?? 0,
            $currentMonth->offering ?? 0,
            $currentMonth->special_offering ?? 0,
            $currentMonth->building_fund ?? 0,
        ]);
        $overallPercentage = $totalTarget > 0 ? round(($totalActual / $totalTarget) * 100, 1) : 0;

        return [
            'targets' => $targets,
            'overall' => [
                'target' => $totalTarget,
                'actual' => $totalActual,
                'percentage' => $overallPercentage,
                'status' => $this->getTargetStatus($overallPercentage),
            ],
            'month' => now()->format('F Y'),
        ];
    }

    private function getTargetStatus(float $percentage): array
    {
        if ($percentage >= 100) {
            return ['label' => 'Target Reached', 'color' => 'success'];
        } elseif ($percentage >= 80) {
            return ['label' => 'On Track', 'color' => 'success'];
        } elseif ($percentage >= 60) {
            return ['label' => 'Needs Attention', 'color' => 'warning'];
        } elseif ($percentage >= 40) {
            return ['label' => 'Behind Target', 'color' => 'danger'];
        } else {
            return ['label' => 'Critical', 'color' => 'danger'];
        }
    }
}
