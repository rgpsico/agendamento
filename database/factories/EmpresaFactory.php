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
                if (Usuario::count() > 0) {
                    return Usuario::all()->random()->id;
                } else {
                    // Create a new user
                    $user = Usuario::create([
                        // ... specify necessary data for a new user
                    ]);
                    return $user->id;
                }
            },
            'avatar' => $this->faker->image('public/avatar', 290, 200, null, false),
            'nome' => $this->faker->company,
            'descricao' => $this->faker->text(200),
            'telefone' => $this->faker->phoneNumber,
            'modalidade_id' => Modalidade::inRandomOrder()->first()->id,
            'cnpj' => $this->faker->randomNumber(8) . '/' . $this->faker->randomNumber(4) . '-' . $this->faker->randomNumber(2),
            'valor_aula_de' => $this->faker->randomFloat(2, 0, 1000),
            'valor_aula_ate' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
