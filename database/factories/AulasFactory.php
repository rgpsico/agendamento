<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Aulas;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Foundation\Auth\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aulas>
 */
class AulasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => rand(1, 90),
            'professor_id' => Usuario::inRandomOrder()->first()->id, // Você pode querer substituir isso por um professor_id existente.
            'data_hora' => $this->faker->dateTime,
            'dia_id' => rand(0, 6),
            'local' => $this->faker->sentence,
            'capacidade' => $this->faker->numberBetween(1, 50),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
