<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\Branch;
use App\Models\CellGroup;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'registration_number' => 'HKC-000001',
                'title' => 'Mr',
                'first_name' => 'Blessmore',
                'last_name' => 'Mulenga',
                'date_of_birth' => '1993-12-10',
                'gender' => 'Male',
                'phone' => '260975020473',
                'alternative_phone' => '260969893182',
                'email' => 'mulengablessmore@gmail.com',
                'address' => 'A355 Kwamwena Valley, Mutumbi, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2020-01-01',
                'cell_group_id' => 1,
                'salvation_date' => '2009-09-09',
                'baptism_date' => '2009-09-09',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'Completed',
                'leadership_class_status' => 'Completed',
                'marital_status' => 'Married',
                'occupation' => 'Police Officer',
                'employer' => 'Ministry of Home Affairs',
                'emergency_contact_name' => 'Peter Mulenga',
                'emergency_contact_phone' => '260977597100',
                'previous_church' => 'Church of God - Dominion Worship Center',
                'previous_church_pastor' => 'Bishop Walishimba',
                'skills_talents' => 'Coding, Fraud investigation',
                'interests' => 'Travelling, Outdoor Activities',
                'special_needs' => 'Spiritual Growth through teaching',
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Needs alot of incubation in the word'
            ],
            [
                'registration_number' => 'HKC-000002',
                'title' => 'Mrs.',
                'first_name' => 'Yvonne',
                'last_name' => 'Mulenga',
                'date_of_birth' => '1995-02-02',
                'gender' => 'Female',
                'phone' => '260972959023',
                'alternative_phone' => null,
                'email' => 'yvonnemudenda@gmail.com',
                'address' => 'A355 Kwamwena Valley, Mutumbi, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Leader',
                'membership_date' => '2020-01-01',
                'cell_group_id' => 1,
                'salvation_date' => '2000-01-01',
                'baptism_date' => '2000-06-01',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'Completed',
                'leadership_class_status' => 'Completed',
                'marital_status' => 'Married',
                'occupation' => 'PR Assistant Officer',
                'employer' => 'Ministry of Eduction',
                'emergency_contact_name' => 'Blessmore Mulenga',
                'emergency_contact_phone' => '260975020473',
                'previous_church' => 'Kingdom Hall',
                'previous_church_pastor' => 'Elder Mwaba',
                'skills_talents' => 'Teaching, Counseling',
                'interests' => 'Women Ministry, Children Ministry',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Passionate about growing the Women Ministry'
            ]
        ];

        foreach ($members as $member) {
            Member::create($member);
        }
    }
}