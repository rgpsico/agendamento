<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Empresa;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaEnderecoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'empresa_id' => function () {
                // Primeiro, verifica se já existe alguma empresa. Se existir, retorna o id de uma empresa aleatória.
                if (\App\Models\Empresa::all()->count()) {
                    return \App\Models\Empresa::all()->random()->id;
                }
                // Se não existir nenhuma empresa, cria uma nova e retorna o seu id.
                else {
                    return \App\Models\Empresa::factory()->create()->id;
                }
            },
            'endereco' => $this->faker->streetAddress,
            'cidade' => $this->faker->city,
            'estado' => $this->faker->state,
            'cep' => $this->faker->postcode,
            'uf' => $this->faker->state,
            'pais' => $this->faker->country,
        ];
    }
}
