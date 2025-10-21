<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
                User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password123'),
                'role' => 'ADMIN',
            ]
        );
            User::updateOrCreate(
            ['email' => 'comercial@admin.com'],
            [
                'name' => 'Comercial',
                'password' => bcrypt('password123'),
                'role' => 'COMERCIAL',
            ]
        );

        User::updateOrCreate(
            ['email' => 'cliente@admin.com'],
            [
                'name' => 'Cliente',
                'password' => bcrypt('password123'),
                'role' => 'USER',
            ]
        );
    }
}
