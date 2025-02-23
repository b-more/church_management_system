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

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $branches = Branch::all();
        $members = Member::all();
        $users = User::all();
        $categories = TransactionCategory::all();

        // Get tithe and offering categories
        $titheCategory = TransactionCategory::where('name', 'Monthly Tithes')->first();
        $offeringCategory = TransactionCategory::where('name', 'Sunday Service Offering')->first();
        $expenseCategories = TransactionCategory::where('type', 'expense')->get();

        // Generate transactions for the last 6 months
        for ($i = 0; $i < 180; $i++) {
            $transactionDate = Carbon::now()->subDays(rand(1, 180));
            $type = rand(1, 100);

            if ($type <= 40) { // 40% Tithes
                Transaction::create([
                    'branch_id' => $branches->random()->id,
                    'member_id' => $members->random()->id,
                    'transaction_type' => 'tithe',
                    'amount' => rand(100, 1000) * 10,
                    'payment_method' => collect(['cash', 'mobile_money', 'bank_transfer'])->random(),
                    'payment_reference' => 'REF' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
                    'transaction_date' => $transactionDate,
                    'description' => 'Monthly Tithe Payment',
                    'category_id' => $titheCategory->id,
                    'recorded_by' => $users->random()->id,
                    'status' => 'completed',
                ]);
            } elseif ($type <= 80) { // 40% Offerings
                Transaction::create([
                    'branch_id' => $branches->random()->id,
                    'member_id' => $members->random()->id,
                    'transaction_type' => 'offering',
                    'amount' => rand(10, 100) * 10,
                    'payment_method' => collect(['cash', 'mobile_money'])->random(),
                    'payment_reference' => 'REF' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
                    'transaction_date' => $transactionDate,
                    'description' => 'Sunday Service Offering',
                    'category_id' => $offeringCategory->id,
                    'recorded_by' => $users->random()->id,
                    'status' => 'completed',
                ]);
            } else { // 20% Expenses
                Transaction::create([
                    'branch_id' => $branches->random()->id,
                    'transaction_type' => 'expense',
                    'amount' => rand(50, 500) * 10,
                    'payment_method' => collect(['cash', 'bank_transfer'])->random(),
                    'payment_reference' => 'EXP' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
                    'transaction_date' => $transactionDate,
                    'description' => 'Operating Expense',
                    'category_id' => $expenseCategories->random()->id,
                    'recorded_by' => $users->random()->id,
                    'status' => 'completed',
                ]);
            }
        }
    }
}

