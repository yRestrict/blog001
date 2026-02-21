<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Container\Attributes\Auth;
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
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'role' => UserRole::SuperAdmin,
            'status' => UserStatus::Active,
        ]);
    }
}
