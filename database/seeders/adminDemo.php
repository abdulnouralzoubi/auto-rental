<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class adminDemo extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test Admin',
            'email' => 'test_admin@email.com',
            'password' => Hash::make('pass1234'),
            'role' => 'admin',
            'avatar' => '/images/avatars/avatar_2.jpg'
        ]);
        User::create([
            'name' => 'Test Lessor',
            'email' => 'test_lessor@email.com',
            'password' => Hash::make('pass1234'),
            'role' => 'lessor',
            'avatar' => '/images/avatars/avatar_6.jpg'
        ]);
        User::create([
            'name' => 'Test User',
            'email' => 'test_user@email.com',
            'password' => Hash::make('pass1234'),
            'role' => 'client',
            'avatar' => '/images/avatars/avatar_6.jpg'
        ]);
        User::create([
            'name' => 'Test User2',
            'email' => 'test_user2@email.com',
            'password' => Hash::make('pass1234'),
            'role' => 'client',
            'avatar' => '/images/avatars/avatar_6.jpg'
        ]);
        User::create([
            'name' => 'Test User3',
            'email' => 'test_user3@email.com',
            'password' => Hash::make('pass1234'),
            'role' => 'client',
            'avatar' => '/images/avatars/avatar_6.jpg'
        ]);
    }
}
