<?php

namespace Database\Factories;


use App\Models\Alunos;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Empresa;
use App\Models\Modalidade;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Foundation\Auth\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'aluno_id' => function () {
                return Alunos::inRandomOrder()->first()->id;
            },
            'modalidade_id' => function () {
                return Modalidade::inRandomOrder()->first()->id;
            },
            'professor_id' => function () {
                return Professor::inRandomOrder()->first()->id;
            },
            'status' => $this->faker->randomElement(['Espera', 'Feita', 'Cancelada', 'Adiada']),
            'data_agendamento' => $this->faker->dateTimeBetween('now', '+2 years'),
        ];
    }
}
