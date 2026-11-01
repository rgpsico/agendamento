<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Professor;
use App\Models\EmpresaEndereco;
use App\Models\Modalidade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EmpresaStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_criar_empresa_com_endereco_professor_e_upload()
    {
        // cria dados auxiliares
        $user = Usuario::factory()->create();
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
            'avatar'        => UploadedFile::fake()->create('avatar.jpg', 10, 'image/jpeg'),
            'banner'        => UploadedFile::fake()->create('banner.jpg', 10, 'image/jpeg'),
        ];

        // a rota exige usuario autenticado
        $this->actingAs($user);

        // executa a requisição
        $response = $this->post(route('empresa.store'), $payload);

        // o cadastro nao pode ter caido no catch (que volta com erro)
        $response->assertSessionHasNoErrors();

        // garante redirecionamento
        $response->assertRedirect(route('empresa.pagamento.boleto'));

        // verifica empresa criada
        $this->assertDatabaseHas('empresa', [
            'nome' => 'Empresa Teste',
            'cnpj' => '12.345.678/0001-99',
        ]);

        // o e-mail do usuario nao e mais alterado pelo cadastro da empresa (trecho desativado no controller)

        // verifica professor vinculado
        $this->assertDatabaseHas('professores', [
            'usuario_id' => $user->id,
            'empresa_id' => Empresa::first()->id,
        ]);

        // verifica endereço salvo
        $this->assertDatabaseHas('empresa_endereco', [
            'empresa_id' => Empresa::first()->id,
            'cep'        => '01001-000',
        ]);

        // verifica upload: o helper move o arquivo direto para public/avatar e public/banner
        $empresa = Empresa::first();
        $avatar = public_path('avatar/' . $empresa->avatar);
        $banner = public_path('banner/' . $empresa->banners);
        $this->assertFileExists($avatar);
        $this->assertFileExists($banner);
        @unlink($avatar);
        @unlink($banner);
    }
}
