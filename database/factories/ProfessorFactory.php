<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Empresa;
use App\Models\Modalidade;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class ProfessorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'usuario_id' => Usuario::inRandomOrder()->first()->id,
            'modalidade_id' => Modalidade::inRandomOrder()->first()->id,
            'sobre' => $this->faker->paragraph,
            'avatar' => 'avatardefault.jpg',
        ];
    }
}
