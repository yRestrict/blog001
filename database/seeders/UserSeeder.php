<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use App\UserRole;
use App\UserStatus;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // OWNER
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'role' => UserRole::Owner,
            'status' => UserStatus::Active,
        ]);

        // AUTHOR 1
        User::create([
            'name' => 'Author 1',
            'email' => 'author1@test.com',
            'username' => 'author1',
            'password' => Hash::make('123456'),
            'role' => UserRole::Author,
            'status' => UserStatus::Active,
        ]);

        // AUTHOR 2
        User::create([
            'name' => 'Author 2',
            'email' => 'author2@test.com',
            'username' => 'author2',
            'password' => Hash::make('123456'),
            'role' => UserRole::Author,
            'status' => UserStatus::Active,
        ]);
    }
}
