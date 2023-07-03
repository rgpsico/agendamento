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
class Aluno_GaleriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'usuario_id' => 1,
            'image' => $this->faker->image('public/aluno_galeria', 640, 480, null, false),
        ];
    }
}
