<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Administrador',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => '$2y$12$Bv5COe6HCU.VaCLNaLpZY.PA2wtUs2V5qErKXxdM/6/.0mJ1z/0aG',
            'role' => 'Administrador',
        ]);

        \App\Models\User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'Agente',
        ]);
    }
}
