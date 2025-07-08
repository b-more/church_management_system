<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectCategory;

class ProjectCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Building & Infrastructure',
                'description' => 'Church building construction, renovation, and infrastructure development projects',
                'is_active' => true
            ],
            [
                'name' => 'Equipment & Technology',
                'description' => 'Purchase of sound systems, instruments, computers, and other technological equipment',
                'is_active' => true
            ],
            [
                'name' => 'Community Outreach',
                'description' => 'Projects focused on serving the community and evangelism activities',
                'is_active' => true
            ],
            [
                'name' => 'Education & Training',
                'description' => 'Educational programs, training materials, and capacity building initiatives',
                'is_active' => true
            ],
            [
                'name' => 'Youth & Children Ministry',
                'description' => 'Projects specifically designed for youth and children programs',
                'is_active' => true
            ],
            [
                'name' => 'Missions & Evangelism',
                'description' => 'Support for missionary work, church planting, and evangelistic campaigns',
                'is_active' => true
            ],
            [
                'name' => 'Transportation',
                'description' => 'Church vehicles, transportation equipment, and mobility projects',
                'is_active' => true
            ],
            [
                'name' => 'Welfare & Support',
                'description' => 'Projects aimed at supporting members and community welfare needs',
                'is_active' => true
            ],
            [
                'name' => 'Special Events',
                'description' => 'Conferences, retreats, and special church events funding',
                'is_active' => false
            ],
            [
                'name' => 'Emergency Fund',
                'description' => 'Emergency projects and urgent church needs',
                'is_active' => false
            ]
        ];

        foreach ($categories as $category) {
            ProjectCategory::create($category);
        }
    }
}
