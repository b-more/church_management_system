<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\TransactionCategory;
use App\Models\Transaction;
use App\Models\FinancialPeriod;
use App\Models\Budget;
use App\Models\Member;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FinancialPeriodSeeder extends Seeder
{
    public function run()
    {
        // Create Monthly Periods for Current Year
        $currentYear = Carbon::now()->year;

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($currentYear, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();

            FinancialPeriod::create([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'type' => 'monthly',
                'status' => $month < Carbon::now()->month ? 'closed' : 'open',
                'closed_at' => $month < Carbon::now()->month ? $endDate : null,
                'closed_by' => $month < Carbon::now()->month ? 1 : null,
            ]);
        }

        // Create Quarterly Periods
        for ($quarter = 1; $quarter <= 4; $quarter++) {
            $startDate = Carbon::create($currentYear, ($quarter - 1) * 3 + 1, 1);
            $endDate = $startDate->copy()->addMonths(3)->subDay();

            FinancialPeriod::create([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'type' => 'quarterly',
                'status' => $quarter < ceil(Carbon::now()->month / 3) ? 'closed' : 'open',
                'closed_at' => $quarter < ceil(Carbon::now()->month / 3) ? $endDate : null,
                'closed_by' => $quarter < ceil(Carbon::now()->month / 3) ? 1 : null,
            ]);
        }

        // Create Annual Period
        FinancialPeriod::create([
            'start_date' => Carbon::create($currentYear, 1, 1),
            'end_date' => Carbon::create($currentYear, 12, 31),
            'type' => 'annual',
            'status' => 'open',
        ]);
    }
}
