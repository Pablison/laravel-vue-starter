<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@teste.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Usuario 1',
            'email' => 'user1@teste.com',
            'password' => Hash::make('user1'),
            'role' => 'user',
        ]);

        User::factory()->create([
            'name' => 'Usuario 2',
            'email' => 'user2@teste.com',
            'password' => Hash::make('user2'),
            'role' => 'user',
        ]);
    }
}
