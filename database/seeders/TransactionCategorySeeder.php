<?php

namespace Database\Seeders;

use App\Models\TransactionCategory;
use App\Models\Transaction;
use App\Models\FinancialPeriod;
use App\Models\Budget;
use App\Models\Member;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionCategorySeeder extends Seeder
{
    public function run()
    {
        // Income Categories
        $incomeCategories = [
            'Tithes' => [
                'Monthly Tithes',
                'Annual Tithes',
                'Special Tithes'
            ],
            'Offerings' => [
                'Sunday Service Offering',
                'Midweek Service Offering',
                'Special Service Offering',
                'Thanksgiving Offering'
            ],
            'Donations' => [
                'Building Fund',
                'Missions Fund',
                'Youth Ministry',
                'Children Ministry'
            ],
            'Other Income' => [
                'Bookstore Sales',
                'Event Registration',
                'Property Rental'
            ]
        ];

        // Expense Categories
        $expenseCategories = [
            'Administrative' => [
                'Office Supplies',
                'Utilities',
                'Staff Salaries',
                'Equipment Maintenance'
            ],
            'Ministry' => [
                'Youth Programs',
                'Children Programs',
                'Worship Ministry',
                'Evangelism'
            ],
            'Facilities' => [
                'Rent/Mortgage',
                'Building Maintenance',
                'Cleaning Services',
                'Security'
            ],
            'Outreach' => [
                'Missions',
                'Community Programs',
                'Charitable Giving',
                'Events'
            ]
        ];

        // Create Income Categories
        foreach ($incomeCategories as $mainCategory => $subCategories) {
            $parent = TransactionCategory::create([
                'name' => $mainCategory,
                'type' => 'income',
                'description' => "Main category for {$mainCategory}",
                'is_active' => true,
            ]);

            foreach ($subCategories as $subCategory) {
                TransactionCategory::create([
                    'name' => $subCategory,
                    'type' => 'income',
                    'description' => "Sub-category under {$mainCategory}",
                    'parent_id' => $parent->id,
                    'is_active' => true,
                ]);
            }
        }

        // Create Expense Categories
        foreach ($expenseCategories as $mainCategory => $subCategories) {
            $parent = TransactionCategory::create([
                'name' => $mainCategory,
                'type' => 'expense',
                'description' => "Main category for {$mainCategory}",
                'is_active' => true,
            ]);

            foreach ($subCategories as $subCategory) {
                TransactionCategory::create([
                    'name' => $subCategory,
                    'type' => 'expense',
                    'description' => "Sub-category under {$mainCategory}",
                    'parent_id' => $parent->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
