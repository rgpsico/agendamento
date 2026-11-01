<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Painel administrativo nunca pode abrir (nem vazar dados) para visitante sem login.
 * Antes dessas rotas ganharem o middleware, o visitante via erro 500 ou ate resposta 200.
 */
class RotasProtegidasTest extends TestCase
{
    use RefreshDatabase;

    public static function paginasDoPainel(): array
    {
        return array_map(fn ($u) => [$u], [
            '/configuracoes/sistema', '/configuracoes/usuarios', '/configuracoes/permissoes',
            '/integracoes/asaas', '/integracoes/stripe', '/manager',
            '/planos', '/planos/create', '/site-templates', '/site-templates/create',
            '/virtualhosts', '/virtualhosts/create',
            '/admin/usuarios', '/admin/usuarios/1/permissions', '/admin/roles', '/admin/perfis', '/admin/planos',
            '/admin/financeiro/detalhes',
            '/financeiro/categorias', '/financeiro/despesas_recorrentes', '/financeiro/receitas_recorrentes',
            '/google-calendar/create-event', '/google-calendar/events', '/events',
            '/empresa/pagamento', '/empresa/pagamento/create', '/empresa/pagamento/boleto',
        ]);
    }

    /** @dataProvider paginasDoPainel */
    public function test_visitante_e_mandado_para_o_login(string $url): void
    {
        $this->get($url)->assertRedirect();
        $this->assertGuest();
    }

    public static function escritasPerigosas(): array
    {
        return [
            ['post', '/virtualhosts'], ['put', '/virtualhosts/x.conf'], ['delete', '/virtualhosts/x.conf'],
            ['post', '/configuracoes/seeds/run/surf'], ['post', '/admin/usuarios/1/permissions'], ['delete', '/admin/usuarios/1'],
            ['post', '/admin/roles'], ['delete', '/admin/perfis/1'],
            ['post', '/planos'], ['put', '/planos/1'], ['delete', '/planos/1'],
            ['post', '/site-templates'], ['delete', '/site-templates/1'],
            ['put', '/admin/site/1/configuracoes'], ['post', '/admin/site/1/dominio'],
        ];
    }

    /** @dataProvider escritasPerigosas */
    public function test_visitante_nao_consegue_escrever(string $metodo, string $url): void
    {
        $this->$metodo($url)->assertRedirect();
        $this->assertGuest();
    }

    public function test_usuario_comum_nao_acessa_o_que_e_so_do_master(): void
    {
        config(['master_users.emails' => ['dono@teste.com']]);
        $comum = Usuario::forceCreate(['nome' => 'Comum', 'email' => 'comum@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);

        foreach (['/virtualhosts', '/site-templates', '/admin/roles', '/admin/perfis'] as $url) {
            $this->actingAs($comum)->get($url)->assertForbidden();
        }
        $this->actingAs($comum)->delete('/virtualhosts/x.conf')->assertForbidden();
        $this->actingAs($comum)->post('/admin/usuarios/1/permissions')->assertForbidden();
    }

    public function test_paginas_publicas_continuam_abertas(): void
    {
        $this->get('/')->assertOk();
        $this->get('/login')->assertOk();
    }
}
