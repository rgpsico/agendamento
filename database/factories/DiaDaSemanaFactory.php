<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Aulas;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aulas>
 */
class DiaDaSemanaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $dia = $this->faker->unique()->numberBetween(1, 7);
        return [
            'dia' => $dia,
            'nome_dia' => $this->diaNome($dia),
        ];
    }

    /**
     * Returns the name of the day in Portuguese based on the given number.
     *
     * @param  int  $dia
     * @return string
     */
    private function diaNome(int $dia): string
    {
        $dias = [
            1 => 'Domingo',
            2 => 'Segunda-feira',
            3 => 'Terça-feira',
            4 => 'Quarta-feira',
            5 => 'Quinta-feira',
            6 => 'Sexta-feira',
            7 => 'Sábado',
        ];

        return $dias[$dia] ?? 'Desconhecido';
    }
}
