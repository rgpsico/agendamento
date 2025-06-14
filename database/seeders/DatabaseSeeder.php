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

        $this->call(FeriadosSeeder::class);
        //$this->call(MigrateRolesSeeder::class);
        // \App\Models\Usuario::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        // \App\Models\Usuario::factory(10)->create();
        // \App\Models\DiaDaSemana::factory(7)->create();
        // \App\Models\Modalidade::factory(5)->create();
        // \App\Models\Empresa::factory(10)->create();
        // \App\Models\EmpresaEndereco::factory(10)->create();

        // //  \App\Models\Aulas::factory(10)->create();
        // \App\Models\Professor::factory(10)->create();
        // \App\Models\Alunos::factory(10)->create();
        // \App\Models\Aluno_Galeria::factory(10)->create();

        // \App\Models\Servicos::factory(10)->create();


        // \App\Models\Disponibilidade::factory(7)->create();
    }
}
