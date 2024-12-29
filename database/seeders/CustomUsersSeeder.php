<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CustomUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'John Doe Member',
            'email' => 'member@example.com',
            'password' => bcrypt('member123'), // Known password
            'role' => '1', // 1 = member
            'status_delete' => '0', // Active
        ]);

        // Create 1 staff with a known password
        User::create([
            'name' => 'Jane Doe Staff',
            'email' => 'staff@example.com',
            'password' => bcrypt('staff123'), // Known password
            'role' => '0', // 0 = staff
            'status_delete' => '0', // Active
        ]);
    }
}
