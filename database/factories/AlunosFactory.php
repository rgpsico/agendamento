<?php

namespace Database\Factories;

use App\Models\Alunos;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class AlunosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'usuario_id' => Usuario::factory(),
            // outros campos aqui
        ];
    }
}
