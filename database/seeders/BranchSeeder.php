<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'International Prayer Center',
                'branch_code' => 'HKC-001',
                'address' => 'Mutumbi 123 Main Street Along the kwamwena police post road from Cheers mall',
                'city' => 'Lusaka',
                'country' => 'Zambia',
                'phone' => '+260975020473',
                'email' => 'info@hkc.co.zm',
                'founding_date' => '2001-01-01',
                'status' => 'Active',
                'branch_type' => 'Main',
                'seating_capacity' => 1000,
                'service_times' => [
                    [
                        'day' => 'Sunday',
                        'start_time' => '08:30',
                        'end_time' => '12:00',
                        'service_name' => 'Sunday Main Service'
                    ],
                    [
                        'day' => 'Sunday',
                        'start_time' => '11:30',
                        'end_time' => '13:30',
                        'service_name' => 'Second Service'
                    ],
                    [
                        'day' => 'Wednesday',
                        'start_time' => '17:30',
                        'end_time' => '19:00',
                        'service_name' => 'Mid-week Service'
                    ]
                ],
            ],
            [
                'name' => 'Ngombe Branch',
                'branch_code' => 'HKC-002',
                'address' => 'Plot 1323 Ngombe opposite the main market',
                'city' => 'Lusaka',
                'country' => 'Zambia',
                'phone' => '+260969893182',
                'email' => 'ngombe@hkc.co.zm',
                'founding_date' => '2016-06-15',
                'status' => 'Active',
                'branch_type' => 'Subsidiary',
                'seating_capacity' => 500,
                'service_times' => [
                    [
                        'day' => 'Sunday',
                        'start_time' => '08:30',
                        'end_time' => '12:30',
                        'service_name' => 'Sunday Service'
                    ],
                    [
                        'day' => 'Friday',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'service_name' => 'Prayer Meeting'
                    ]
                ],
            ],
            [
                'name' => 'Chipata Compound',
                'branch_code' => 'HKC-003',
                'address' => '6752 Chipata Compound',
                'city' => 'Lusaka',
                'country' => 'Zambia',
                'phone' => '+260975632142',
                'email' => 'chipata@hkc.co.zm',
                'founding_date' => '2018-03-20',
                'status' => 'Active',
                'branch_type' => 'Subsidiary',
                'seating_capacity' => 300,
                'service_times' => [
                    [
                        'day' => 'Sunday',
                        'start_time' => '08:30',
                        'end_time' => '12:30',
                        'service_name' => 'Sunday Service'
                    ]
                ],
            ]
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}