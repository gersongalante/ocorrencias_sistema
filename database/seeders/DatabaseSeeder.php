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

        $esquadra = \App\Models\Esquadra::create([
            'nome' => '1ª Esquadra',
            'provincia' => 'Huambo',
            'municipio' => 'Huambo',
            'bairro' => 'São João',
            'rua' => 'K',
            'telefone' => '923465418',
            'email' => 'priesq@gmail.com',
            'responsavel' => 'Comandante Bimba',
            'observacoes' => 'Fica na Cidade',
            'ativa' => true,
        ]);

        \App\Models\User::create([
            'name' => 'Administrador',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => '$2y$12$Bv5COe6HCU.VaCLNaLpZY.PA2wtUs2V5qErKXxdM/6/.0mJ1z/0aG',
            'role' => 'Administrador',
            'esquadra_id' => $esquadra->id,
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
