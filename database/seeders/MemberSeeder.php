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
            ],
            [
                'registration_number' => 'HKC-000003',
                'title' => 'Pastor',
                'first_name' => 'Derrick',
                'last_name' => 'Banda',
                'date_of_birth' => '1985-06-15',
                'gender' => 'Male',
                'phone' => '260960467909',
                'alternative_phone' => null,
                'email' => 'derrick.banda@hkc.co.zm',
                'address' => 'Plot 123, Meanwood Ndeke, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Leader',
                'membership_date' => '2018-01-01',
                'cell_group_id' => 1,
                'salvation_date' => '2000-03-15',
                'baptism_date' => '2000-09-20',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'Completed',
                'leadership_class_status' => 'Completed',
                'marital_status' => 'Married',
                'occupation' => 'Pastor',
                'employer' => 'His Kingdom Church',
                'emergency_contact_name' => 'Mary Banda',
                'emergency_contact_phone' => '260955123456',
                'previous_church' => 'Living Waters Church',
                'previous_church_pastor' => 'Pastor Ngosa',
                'skills_talents' => 'Preaching, Leadership, Counseling',
                'interests' => 'Bible Study, Youth Ministry',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Senior Pastor with strong leadership abilities'
            ],
            [
                'registration_number' => 'HKC-000004',
                'title' => 'Mr',
                'first_name' => 'Marshall',
                'last_name' => 'Chimba',
                'date_of_birth' => '1990-08-22',
                'gender' => 'Male',
                'phone' => '260976108031',
                'alternative_phone' => null,
                'email' => 'marshall.chimba@gmail.com',
                'address' => '45 Chalala, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2019-03-15',
                'cell_group_id' => 2,
                'salvation_date' => '2015-05-10',
                'baptism_date' => '2015-07-20',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'Completed',
                'leadership_class_status' => 'Not Started',
                'marital_status' => 'Single',
                'occupation' => 'Software Developer',
                'employer' => 'Tech Solutions Ltd',
                'emergency_contact_name' => 'Jane Chimba',
                'emergency_contact_phone' => '260966789012',
                'previous_church' => 'Bread of Life Church',
                'previous_church_pastor' => 'Pastor Mwanza',
                'skills_talents' => 'Programming, Music',
                'interests' => 'Technology, Worship',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Active in church tech team'
            ],
            [
                'registration_number' => 'HKC-000005',
                'title' => 'Mr',
                'first_name' => 'Wilson',
                'last_name' => 'Mashowo',
                'date_of_birth' => '1988-11-30',
                'gender' => 'Male',
                'phone' => '260978461545',
                'alternative_phone' => null,
                'email' => 'wilson.mashowo@yahoo.com',
                'address' => '78 Avondale, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2020-02-01',
                'cell_group_id' => 3,
                'salvation_date' => '2018-12-25',
                'baptism_date' => '2019-03-15',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'In Progress',
                'leadership_class_status' => 'Not Started',
                'marital_status' => 'Married',
                'occupation' => 'Accountant',
                'employer' => 'KPMG Zambia',
                'emergency_contact_name' => 'Grace Mashowo',
                'emergency_contact_phone' => '260977345678',
                'previous_church' => 'Reformed Church',
                'previous_church_pastor' => 'Rev Simpasa',
                'skills_talents' => 'Finance, Administration',
                'interests' => 'Financial Stewardship',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Helps with church finances'
            ],
            [
                'registration_number' => 'HKC-000006',
                'title' => 'Mr',
                'first_name' => 'Whiteson',
                'last_name' => 'Mulenga',
                'date_of_birth' => '1992-04-18',
                'gender' => 'Male',
                'phone' => '260977480162',
                'alternative_phone' => null,
                'email' => 'whiteson.mulenga@gmail.com',
                'address' => '156 Woodlands, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2021-06-01',
                'cell_group_id' => 2,
                'salvation_date' => '2019-08-10',
                'baptism_date' => '2019-10-15',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'Completed',
                'leadership_class_status' => 'In Progress',
                'marital_status' => 'Single',
                'occupation' => 'Teacher',
                'employer' => 'International School of Lusaka',
                'emergency_contact_name' => 'Peter Mulenga',
                'emergency_contact_phone' => '260979234567',
                'previous_church' => 'UCZ',
                'previous_church_pastor' => 'Rev Banda',
                'skills_talents' => 'Teaching, Youth Work',
                'interests' => 'Sunday School, Youth Ministry',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Active in youth ministry'
            ],
            [
                'registration_number' => 'HKC-000007',
                'title' => 'Mr',
                'first_name' => 'Nigel',
                'last_name' => 'Lewanika',
                'date_of_birth' => '1995-09-25',
                'gender' => 'Male',
                'phone' => '260974031860',
                'alternative_phone' => null,
                'email' => 'nigel.lewanika@hotmail.com',
                'address' => '34 Kabulonga, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2022-01-15',
                'cell_group_id' => 1,
                'salvation_date' => '2021-11-20',
                'baptism_date' => '2022-03-05',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'In Progress',
                'leadership_class_status' => 'Not Started',
                'marital_status' => 'Single',
                'occupation' => 'Marketing Executive',
                'employer' => 'MTN Zambia',
                'emergency_contact_name' => 'Sarah Lewanika',
                'emergency_contact_phone' => '260973456789',
                'previous_church' => 'Victory Church',
                'previous_church_pastor' => 'Pastor Chanda',
                'skills_talents' => 'Marketing, Media',
                'interests' => 'Church Media, Evangelism',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Helps with church social media'
            ],
            [
                'registration_number' => 'HKC-000008',
                'title' => 'Mr',
                'first_name' => 'John',
                'last_name' => 'Banda',
                'date_of_birth' => '1987-12-03',
                'gender' => 'Male',
                'phone' => '260973595860',
                'alternative_phone' => null,
                'email' => 'john.banda@gmail.com',
                'address' => '89 Millennium, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2020-09-01',
                'cell_group_id' => 3,
                'salvation_date' => '2019-05-15',
                'baptism_date' => '2019-08-20',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'Completed',
                'leadership_class_status' => 'Not Started',
                'marital_status' => 'Married',
                'occupation' => 'Business Owner',
                'employer' => 'Self Employed',
                'emergency_contact_name' => 'Ruth Banda',
                'emergency_contact_phone' => '260979876543',
                'previous_church' => 'Northmead Assembly',
                'previous_church_pastor' => 'Bishop Banda',
                'skills_talents' => 'Business, Leadership',
                'interests' => 'Men\'s Ministry, Business Fellowship',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Active in business ministry'
            ],
            [
                'registration_number' => 'HKC-000009',
                'title' => 'Mr',
                'first_name' => 'Timothy',
                'last_name' => 'Musenge',
                'date_of_birth' => '1991-07-14',
                'gender' => 'Male',
                'phone' => '260979082716',
                'alternative_phone' => null,
                'email' => 'timothy.musenge@gmail.com',
                'address' => '234 Chilenje South, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2021-03-15',
                'cell_group_id' => 2,
                'salvation_date' => '2019-12-25',
                'baptism_date' => '2020-02-14',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'Completed',
                'leadership_class_status' => 'In Progress',
                'marital_status' => 'Married',
                'occupation' => 'Civil Engineer',
                'employer' => 'Road Development Agency',
                'emergency_contact_name' => 'Martha Musenge',
                'emergency_contact_phone' => '260955987654',
                'previous_church' => 'Gospel Outreach',
                'previous_church_pastor' => 'Pastor Mumba',
                'skills_talents' => 'Project Management, Music',
                'interests' => 'Worship Team, Infrastructure',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Active in worship team'
            ],
            [
                'registration_number' => 'HKC-000010',
                'title' => 'Mr',
                'first_name' => 'Shadreck',
                'last_name' => 'Banda',
                'date_of_birth' => '1989-03-25',
                'gender' => 'Male',
                'phone' => '260966703953',
                'alternative_phone' => null,
                'email' => 'shadreck.banda@yahoo.com',
                'address' => '56 Kamwala South, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2020-08-01',
                'cell_group_id' => 3,
                'salvation_date' => '2018-06-15',
                'baptism_date' => '2018-09-20',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'Completed',
                'leadership_class_status' => 'Not Started',
                'marital_status' => 'Married',
                'occupation' => 'Banker',
                'employer' => 'Stanbic Bank',
                'emergency_contact_name' => 'Joyce Banda',
                'emergency_contact_phone' => '260977123456',
                'previous_church' => 'Mount Zion',
                'previous_church_pastor' => 'Pastor Phiri',
                'skills_talents' => 'Finance, Administration',
                'interests' => 'Finance Committee, Men\'s Fellowship',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Helps with church accounting'
            ],
            [
                'registration_number' => 'HKC-000011',
                'title' => 'Mr',
                'first_name' => 'David',
                'last_name' => 'Manyima',
                'date_of_birth' => '1993-11-08',
                'gender' => 'Male',
                'phone' => '260962753585',
                'alternative_phone' => null,
                'email' => 'david.manyima@gmail.com',
                'address' => '78 Garden House, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2021-01-15',
                'cell_group_id' => 1,
                'salvation_date' => '2020-03-10',
                'baptism_date' => '2020-06-20',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'In Progress',
                'leadership_class_status' => 'Not Started',
                'marital_status' => 'Single',
                'occupation' => 'Teacher',
                'employer' => 'Munali Secondary School',
                'emergency_contact_name' => 'Rachel Manyima',
                'emergency_contact_phone' => '260954233470',
                'previous_church' => 'Bible Gospel Church',
                'previous_church_pastor' => 'Pastor Tembo',
                'skills_talents' => 'Teaching, Youth Work',
                'interests' => 'Youth Ministry, Education',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Active in youth department'
            ],
            [
                'registration_number' => 'HKC-000012',
                'title' => 'Mr',
                'first_name' => 'Gift',
                'last_name' => 'Nyendwa',
                'date_of_birth' => '1994-05-17',
                'gender' => 'Male',
                'phone' => '260974734876',
                'alternative_phone' => null,
                'email' => 'gift.nyendwa@gmail.com',
                'address' => '112 Matero East, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2022-02-01',
                'cell_group_id' => 2,
                'salvation_date' => '2021-07-15',
                'baptism_date' => '2021-09-30',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'In Progress',
                'leadership_class_status' => 'Not Started',
                'marital_status' => 'Single',
                'occupation' => 'IT Specialist',
                'employer' => 'Airtel Zambia',
                'emergency_contact_name' => 'Mary Nyendwa',
                'emergency_contact_phone' => '260966789012',
                'previous_church' => 'Celebration Church',
                'previous_church_pastor' => 'Pastor Sichalwe',
                'skills_talents' => 'IT, Sound Engineering',
                'interests' => 'Media Team, Technology',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Serves in media department'
            ],
            [
                'registration_number' => 'HKC-000013',
                'title' => 'Ms',
                'first_name' => 'Mulima Sepiso',
                'last_name' => 'Mweemba',
                'date_of_birth' => '1992-09-23',
                'gender' => 'Female',
                'phone' => '260978124541',
                'alternative_phone' => null,
                'email' => 'mulima.mweemba@gmail.com',
                'address' => '45 Olympia Park, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2020-11-15',
                'cell_group_id' => 3,
                'salvation_date' => '2019-04-20',
                'baptism_date' => '2019-07-15',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'Completed',
                'leadership_class_status' => 'In Progress',
                'marital_status' => 'Single',
                'occupation' => 'Nurse',
                'employer' => 'UTH',
                'emergency_contact_name' => 'John Mweemba',
                'emergency_contact_phone' => '260977345678',
                'previous_church' => 'Harvest Church',
                'previous_church_pastor' => 'Pastor Kabwe',
                'skills_talents' => 'Healthcare, Counseling',
                'interests' => 'Medical Ministry, Women\'s Ministry',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Active in medical ministry'
            ],
            [
                'registration_number' => 'HKC-000014',
                'title' => 'Ms',
                'first_name' => 'Namukonda',
                'last_name' => 'Nonde',
                'date_of_birth' => '1995-01-30',
                'gender' => 'Female',
                'phone' => '260976001446',
                'alternative_phone' => null,
                'email' => 'namukonda.nonde@yahoo.com',
                'address' => '167 Libala South, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2021-09-01',
                'cell_group_id' => 1,
                'salvation_date' => '2020-12-25',
                'baptism_date' => '2021-02-14',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'Completed',
                'leadership_class_status' => 'Not Started',
                'marital_status' => 'Single',
                'occupation' => 'Accountant',
                'employer' => 'Deloitte',
                'emergency_contact_name' => 'James Nonde',
                'emergency_contact_phone' => '260979876543',
                'previous_church' => 'Bread of Life',
                'previous_church_pastor' => 'Pastor Mwale',
                'skills_talents' => 'Accounting, Administration',
                'interests' => 'Finance Team, Choir',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Serves in finance department'
            ],
            [
                'registration_number' => 'HKC-000015',
                'title' => 'Ms',
                'first_name' => 'Mwamba Faith',
                'last_name' => 'Zimba',
                'date_of_birth' => '1990-12-12',
                'gender' => 'Female',
                'phone' => '260964417960',
                'alternative_phone' => null,
                'email' => 'faith.zimba@gmail.com',
                'address' => '89 Chelstone, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2020-06-15',
                'cell_group_id' => 2,
                'salvation_date' => '2018-09-10',
                'baptism_date' => '2018-12-25',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'Completed',
                'leadership_class_status' => 'Completed',
                'marital_status' => 'Single',
                'occupation' => 'Teacher',
                'employer' => 'Roma Girls Secondary',
                'emergency_contact_name' => 'Grace Zimba',
                'emergency_contact_phone' => '260977234567',
                'previous_church' => 'Winners Chapel',
                'previous_church_pastor' => 'Pastor Lungu',
                'skills_talents' => 'Teaching, Music',
                'interests' => 'Children\'s Ministry, Worship',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Active in children\'s church'
            ],
            [
                'registration_number' => 'HKC-000016',
                'title' => 'Ms',
                'first_name' => 'Racheal',
                'last_name' => 'Manyima',
                'date_of_birth' => '1996-08-05',
                'gender' => 'Female',
                'phone' => '260954233470',
                'alternative_phone' => null,
                'email' => 'racheal.manyima@gmail.com',
                'address' => '78 Garden House, Lusaka',
                'branch_id' => 1,
                'membership_status' => 'Regular Member',
                'membership_date' => '2021-01-15',
                'cell_group_id' => 1,
                'salvation_date' => '2020-03-10',
                'baptism_date' => '2020-06-20',
                'baptism_type' => 'Immersion',
                'membership_class_status' => 'Completed',
                'foundation_class_status' => 'In Progress',
                'leadership_class_status' => 'Not Started',
                'marital_status' => 'Single',
                'occupation' => 'Student',
                'employer' => 'University of Zambia',
                'emergency_contact_name' => 'David Manyima',
                'emergency_contact_phone' => '260962753585',
                'previous_church' => 'Bible Gospel Church',
                'previous_church_pastor' => 'Pastor Tembo',
                'skills_talents' => 'Singing, Organization',
                'interests' => 'Youth Ministry, Choir',
                'special_needs' => null,
                'is_active' => true,
                'deactivation_reason' => null,
                'notes' => 'Active in choir ministry'
            ]
            

        ];

        foreach ($members as $member) {
            Member::create($member);
        }
    }
}