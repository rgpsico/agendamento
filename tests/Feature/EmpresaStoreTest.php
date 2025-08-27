<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Empresa;
use App\Models\Professor;
use App\Models\EmpresaEndereco;
use App\Models\Modalidade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmpresaStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_criar_empresa_com_endereco_professor_e_upload()
    {
        Storage::fake(); // simula o disco para upload

        // cria dados auxiliares
        $user = User::factory()->create();
        $modalidade = Modalidade::factory()->create();
        $professor = Professor::factory()->create([
            'usuario_id' => $user->id,
        ]);

        // dados do request
        $payload = [
            'nome'          => 'Empresa Teste',
            'email'         => 'novoemail@example.com',
            'descricao'     => 'Uma descrição',
            'telefone'      => '11999999999',
            'cnpj'          => '12.345.678/0001-99',
            'valor_aula_de' => 100,
            'valor_aula_ate'=> 200,
            'modalidade_id' => $modalidade->id,
            'user_id'       => $user->id,
            'cep'           => '01001-000',
            'endereco'      => 'Rua Teste',
            'cidade'        => 'São Paulo',
            'estado'        => 'SP',
            'uf'            => 'SP',
            'pais'          => 'Brasil',
            'avatar'        => UploadedFile::fake()->image('avatar.jpg'),
            'banner'        => UploadedFile::fake()->image('banner.jpg'),
        ];

        // executa a requisição
        $response = $this->post(route('empresa.store'), $payload);

        // garante redirecionamento
        $response->assertRedirect(route('empresa.pagamento.boleto'));

        // verifica empresa criada
        $this->assertDatabaseHas('empresa', [
            'nome' => 'Empresa Teste',
            'cnpj' => '12.345.678/0001-99',
        ]);

        // verifica email atualizado
        $this->assertDatabaseHas('usuarios', [
            'id'    => $user->id,
            'email' => 'novoemail@example.com',
        ]);

        // verifica professor vinculado
        $this->assertDatabaseHas('professores', [
            'usuario_id' => $user->id,
            'empresa_id' => Empresa::first()->id,
        ]);

        // verifica endereço salvo
        $this->assertDatabaseHas('empresa_enderecos', [
            'empresa_id' => Empresa::first()->id,
            'cep'        => '01001-000',
        ]);

        // verifica upload (fake)
        Storage::disk()->assertExists('avatar/' . Empresa::first()->avatar);
        Storage::disk()->assertExists('banner/' . Empresa::first()->banners);
    }
}
