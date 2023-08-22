<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlunoControllerTest extends TestCase
{
    use RefreshDatabase;  // Isto é útil para resetar o banco de dados a cada teste.

    /** @test */
    public function it_can_create_a_usuario()
    {
        $usuarioData = [
            'nome' => 'Teste Usuario',
            'email' => 'teste@email.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => 'tipo_teste',
            'isAdmin' => null
        ];

        $usuario = Usuario::create($usuarioData);

        $this->assertDatabaseHas('usuarios', $usuarioData);
    }

    /** @test */
    public function it_can_update_a_usuario()
    {
        // 1. Criar um usuário usando a factory ou manualmente.
        $usuario = Usuario::factory()->create();  // Assumindo que você tenha uma factory para Usuario. Caso contrário, crie um manualmente como no teste anterior.

        // 2. Defina os dados que você quer atualizar.
        $updateData = [
            'nome' => 'Nome Atualizado',
            'email' => 'email.atualizado@email.com',
            'password' => bcrypt('senha_atualizada'),
            'tipo_usuario' => 'tipo_atualizado',
            'isAdmin' => null
        ];

        // 3. Atualize o usuário.
        $usuario->update($updateData);

        // 4. Verifique se o usuário foi atualizado no banco de dados.
        $this->assertDatabaseHas('usuarios', array_merge(['id' => $usuario->id], $updateData));
    }
}
