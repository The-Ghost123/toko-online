<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@fashionstore.com'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@fashionstore.com',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner@fashionstore.com'],
            [
                'name'     => 'Owner',
                'email'    => 'owner@fashionstore.com',
                'password' => Hash::make('owner123'),
                'role'     => 'owner',
            ]
        );
    }
}