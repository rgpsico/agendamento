<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\Usuario::factory(10)->create();
        // \App\Models\DiaDaSemana::factory(10)->create();

        // \App\Models\EmpresaEndereco::factory(10)->create();
        \App\Models\Modalidade::factory(10)->create();
        // \App\Models\Aulas::factory(10)->create();
        // \App\Models\Professor::factory(10)->create();
        // \App\Models\Aluno_galeria::factory(10)->create();
        //\App\Models\Empresa::factory(10)->create();
        \App\Models\Servicos::factory(10)->create();
    }
}
