<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Aulas;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

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
            'professor_id' => '8b2f7bf6-8d86-4969-a6db-54e2efb73230', // Você pode querer substituir isso por um professor_id existente.
            'data_hora' => $this->faker->dateTime,
            'local' => $this->faker->sentence,
            'capacidade' => $this->faker->numberBetween(1, 50),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
