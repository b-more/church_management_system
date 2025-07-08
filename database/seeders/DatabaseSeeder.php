<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class, // Run this first to create roles
            UserSeeder::class,
            TransactionCategorySeeder::class,
            FinancialPeriodSeeder::class,
            ProjectCategorySeeder::class,

            BudgetSeeder::class,
            BranchSeeder::class,                // Then create users with those roles
            CellGroupSeeder::class,
            OfferingTypeSeeder::class,
            PaymentTypeSeeder::class,
            MemberSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
