<?php

namespace Database\Seeders;

use App\Models\CellGroup;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class CellGroupSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        
        foreach ($branches as $branch) {
            // Create cell groups for each branch
            $cellGroups = [
                [
                    'name' => 'Phase 1 & 2',
                    'branch_id' => $branch->id,
                    'meeting_day' => 'Tuesday',
                    'meeting_time' => '16:00',
                    'meeting_location' => 'Mutumbi Phase 1, ' . $branch->city,
                    'description' => 'A warm and welcoming cell group focused on building strong relationships.',
                    'status' => 'Active',
                ],
                [
                    'name' => 'Phase 3 & 4',
                    'branch_id' => $branch->id,
                    'meeting_day' => 'Wednesday',
                    'meeting_time' => '16:00',
                    'meeting_location' => 'Mutumbi Phase 3, ' . $branch->city,
                    'description' => 'A dynamic cell group dedicated to building strong faith and spiritual growth.',
                    'status' => 'Active',
                ],
                [
                    'name' => 'Chelstone',
                    'branch_id' => $branch->id,
                    'meeting_day' => 'Thursday',
                    'meeting_time' => '17:00',
                    'meeting_location' => 'Chelstone Area, ' . $branch->city,
                    'description' => 'A cell group focused on understanding and experiencing God\'s grace.',
                    'status' => 'Active',
                ],
                [
                    'name' => 'Chalala',
                    'branch_id' => $branch->id,
                    'meeting_day' => 'Friday',
                    'meeting_time' => '18:00',
                    'meeting_location' => 'Chalala Area, ' . $branch->city,
                    'description' => 'A vibrant cell group for young people to grow in faith together.',
                    'status' => 'Active',
                ],
                [
                    'name' => 'Kamanga',
                    'branch_id' => $branch->id,
                    'meeting_day' => 'Saturday',
                    'meeting_time' => '16:00',
                    'meeting_location' => 'Kamanga Area, ' . $branch->city,
                    'description' => 'A family-oriented cell group focusing on building godly homes.',
                    'status' => 'Active',
                ],
            ];

            foreach ($cellGroups as $group) {
                CellGroup::create($group);
            }
        }
    }
}