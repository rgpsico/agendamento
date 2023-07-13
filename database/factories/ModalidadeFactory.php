<?php

namespace Database\Factories;

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
class ModalidadeFactory extends Factory
{

    protected $model = Esporte::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->randomElement(['Futevolei', 'Bodyboard', 'Suf', 'Personal', 'Corrida']),
        ];
    }
}
