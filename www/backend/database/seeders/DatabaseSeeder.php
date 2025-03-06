<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin 1',
            'email' => 'admin1@admin.com',
            'password' => Hash::make('senha123'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Admin 2',
            'email' => 'admin2@admin.com',
            'password' => Hash::make('senha123'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Usuário 1',
            'email' => 'user1@user.com',
            'password' => Hash::make('senha123'),
            'role' => 'client'
        ]);

        User::factory()->create([
            'name' => 'Usuário 2',
            'email' => 'user2@user.com',
            'password' => Hash::make('senha123'),
            'role' => 'client'
        ]);
    }
}
