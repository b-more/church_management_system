<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'hkc@ontech.co.zm',
            'username' => 'hkc@ontech.co.zm',
            'password' => Hash::make('Admin.1234'),
            'email_verified_at' => now(),
            'status' => 'active'
        ])->assignRole('super-admin');

        // Create Branch Admin
        User::create([
            'name' => 'Branch Admin',
            'email' => 'branch@ontech.co.zm',
            'username' => 'branchadmin',
            'password' => Hash::make('Admin.1234'),
            'email_verified_at' => now(),
            'status' => 'active'
        ])->assignRole('branch-admin');

        // Create Department Head
        User::create([
            'name' => 'Department Head',
            'email' => 'department@ontech.co.zm',
            'username' => 'department',
            'password' => Hash::make('Admin.1234'),
            'email_verified_at' => now(),
            'status' => 'active'
        ])->assignRole('department-head');

        // Create Cell Leader
        User::create([
            'name' => 'Cell Leader',
            'email' => 'cellleader@ontech.co.zm',
            'username' => 'cellleader',
            'password' => Hash::make('Admin'),
            'email_verified_at' => now(),
            'status' => 'active'
        ])->assignRole('cell-leader');

        // Create Finance Admin
        User::create([
            'name' => 'Finance Admin',
            'email' => 'finance@ontech.co.zm',
            'username' => 'finance',
            'password' => Hash::make('Admin.1234'),
            'email_verified_at' => now(),
            'status' => 'active'
        ])->assignRole('finance-admin');

        // You can also create some regular users if needed
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Regular User $i",
                'email' => "user$i@ontech.co.zm",
                'username' => "user$i",
                'password' => Hash::make('Admin.1234'),
                'email_verified_at' => now(),
                'status' => 'active'
            ])->assignRole('user');
        }
    }
}