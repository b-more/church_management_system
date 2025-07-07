<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class FinanceOverviewStats extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // Get today's transactions
        $todayStats = Transaction::whereDate('transaction_date', today())
            ->selectRaw('
                SUM(CASE WHEN transaction_type = "tithe" THEN amount ELSE 0 END) as tithe,
                SUM(CASE WHEN transaction_type = "offering" THEN amount ELSE 0 END) as offering,
                SUM(CASE WHEN transaction_type = "expense" THEN amount ELSE 0 END) as expenses,
                COUNT(*) as total_transactions
            ')
            ->first();

        // Get yesterday's transactions for comparison
        $yesterdayStats = Transaction::whereDate('transaction_date', today()->subDay())
            ->selectRaw('
                SUM(CASE WHEN transaction_type = "tithe" THEN amount ELSE 0 END) as tithe,
                SUM(CASE WHEN transaction_type = "offering" THEN amount ELSE 0 END) as offering,
                SUM(CASE WHEN transaction_type = "expense" THEN amount ELSE 0 END) as expenses
            ')
            ->first();

        // Calculate percentages
        $titheChange = $this->calculatePercentageChange(
            $yesterdayStats->tithe ?? 0,
            $todayStats->tithe ?? 0
        );

        $offeringChange = $this->calculatePercentageChange(
            $yesterdayStats->offering ?? 0,
            $todayStats->offering ?? 0
        );

        $expenseChange = $this->calculatePercentageChange(
            $yesterdayStats->expenses ?? 0,
            $todayStats->expenses ?? 0
        );

        return [
            Stat::make('Today\'s Tithe', 'ZMW ' . number_format($todayStats->tithe ?? 0, 2))
                ->description($titheChange > 0 ? "+{$titheChange}%" : "{$titheChange}%")
                ->descriptionIcon($titheChange > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($titheChange > 0 ? 'success' : 'danger')
                ->chart([7, 3, 4, 5, 6, 3, 5]),

            Stat::make('Today\'s Offering', 'ZMW ' . number_format($todayStats->offering ?? 0, 2))
                ->description($offeringChange > 0 ? "+{$offeringChange}%" : "{$offeringChange}%")
                ->descriptionIcon($offeringChange > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($offeringChange > 0 ? 'success' : 'danger')
                ->chart([3, 5, 4, 6, 3, 5, 4]),

            Stat::make('Today\'s Expenses', 'ZMW ' . number_format($todayStats->expenses ?? 0, 2))
                ->description($expenseChange > 0 ? "+{$expenseChange}%" : "{$expenseChange}%")
                ->descriptionIcon($expenseChange > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($expenseChange > 0 ? 'danger' : 'success')
                ->chart([4, 5, 3, 7, 4, 5, 3]),

            Stat::make('Total Transactions', $todayStats->total_transactions ?? 0)
                ->chart([3, 5, 4, 6, 3, 5, 4]),
        ];
    }

    private function calculatePercentageChange($oldValue, $newValue): float
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }

        return round((($newValue - $oldValue) / $oldValue) * 100, 1);
    }
}
