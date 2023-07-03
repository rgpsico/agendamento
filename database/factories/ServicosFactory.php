<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Foundation\Auth\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class ServicosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'empresa_id' => Empresa::factory(), // Assuming Empresa has a factory
            'titulo' => $this->faker->sentence(3),
            'imagem' => $this->faker->image('public/servico', 290, 200, null, false),
            'descricao' => $this->faker->paragraph(3),
            'preco' => $this->faker->randomFloat(2, 0, 1000),
            'tempo_de_aula' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}
