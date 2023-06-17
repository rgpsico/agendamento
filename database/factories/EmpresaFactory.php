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
            'user_id' => function () {
                return Usuario::all()->random(); // Aqui, um usuário aleatório é atribuído a cada empresa. Se essa não for a lógica desejada, substitua conforme necessário.
            },
            'avatar' => 'avatardefault.png',
            'nome' => $this->faker->company,
            'descricao' => $this->faker->text(200),
            'telefone' => $this->faker->phoneNumber,
            'cnpj' => $this->faker->randomNumber(8) . '/' . $this->faker->randomNumber(4) . '-' . $this->faker->randomNumber(2), // isso é apenas um número aleatório, não um CNPJ real
            'valor_aula_de' => $this->faker->randomFloat(2, 0, 1000), // Aqui, gera-se um valor aleatório com duas casas decimais entre 0 e 1000. Ajuste conforme necessário.
            'valor_aula_ate' => $this->faker->randomFloat(2, 0, 1000), // Aqui, gera-se um valor aleatório com duas casas decimais entre 0 e 1000. Ajuste conforme necessário.
        ];
    }
}
