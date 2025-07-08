<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OfferingType;

class OfferingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offeringTypes = [
            [
                'name' => 'Tithe',
                'description' => 'A tenth of income given as an act of worship and obedience to God',
                'is_active' => true
            ],
            [
                'name' => 'Offering',
                'description' => 'General contributions given during church services as an act of worship',
                'is_active' => true
            ],
            [
                'name' => 'Projects',
                'description' => 'Contributions towards church building projects and infrastructure development',
                'is_active' => true
            ],
            [
                'name' => 'Financial Partnership',
                'description' => 'Voluntary partnerships where members support church ministries financially on a monthly basis',
                'is_active' => true
            ],
            [
                'name' => 'Funeral Contributions',
                'description' => 'Support for bereaved families and funeral expenses',
                'is_active' => false
            ],
            [
                'name' => 'Event Payments',
                'description' => 'Payments for church events, conferences, and special programs',
                'is_active' => false
            ],
            [
                'name' => 'Missions',
                'description' => 'Support for missionary work and evangelism activities',
                'is_active' => false
            ],
            [
                'name' => 'First Fruits',
                'description' => 'First earnings or increase offered to God',
                'is_active' => false
            ],
            [
                'name' => 'Thanksgiving',
                'description' => 'Special offerings given in gratitude to God',
                'is_active' => false
            ],
            [
                'name' => 'Welfare',
                'description' => 'Support for church members in need and community outreach programs',
                'is_active' => false
            ],
            [
                'name' => 'Special Seeds',
                'description' => 'Special offerings given during specific occasions or as led by faith',
                'is_active' => false
            ]
        ];

        foreach ($offeringTypes as $type) {
            OfferingType::create($type);
        }
    }
}
