<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Empresa;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

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
            'id' => Str::uuid(),
            'avatar' => 'avatardefault.png',
            'nome' => $this->faker->company,
            'descricao' => $this->faker->text(200),
            'telefone' => $this->faker->phoneNumber,
            'cnpj' => $this->faker->randomNumber(8) . '/' . $this->faker->randomNumber(4) . '-' . $this->faker->randomNumber(2), // isso é apenas um número aleatório, não um CNPJ real
        ];
    }
}
