<?php

namespace Database\Factories;

use App\Models\DiaDaSemana;
use App\Models\Disponibilidade;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Empresa;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Foundation\Auth\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class DisponibilidadeFactory extends Factory
{

    protected $model = Disponibilidade::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_professor' => Professor::factory(),
            'id_dia' => DiaDaSemana::factory(),
            'hora_inicio' => $this->faker->time(),
            'hora_fim' => $this->faker->time(),
        ];
    }
}
