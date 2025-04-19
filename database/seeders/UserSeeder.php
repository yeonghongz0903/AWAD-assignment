<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@chiikawa.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Create regular users
        User::create([
            'name' => 'Regular User',
            'email' => 'user@chiikawa.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Create more sample users
        User::factory(5)->create();
    }
}
