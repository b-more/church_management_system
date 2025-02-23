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

class BudgetSeeder extends Seeder
{
    public function run()
    {
        $categories = TransactionCategory::all();
        $periods = FinancialPeriod::where('type', 'monthly')->get();

        foreach ($periods as $period) {
            foreach ($categories as $category) {
                $baseAmount = $category->type === 'income' ? rand(5000, 20000) : rand(3000, 15000);

                Budget::create([
                    'category_id' => $category->id,
                    'period_id' => $period->id,
                    'amount' => $baseAmount,
                    'notes' => "Budget allocation for {$category->name} - {$period->start_date->format('F Y')}"
                ]);
            }
        }
    }
}
