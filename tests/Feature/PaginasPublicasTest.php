<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaginasPublicasTest extends TestCase
{
    use RefreshDatabase;

    public function test_cadastro_de_professor_abre_para_visitante(): void
    {
        // o cabecalho do admin usava Auth::user()->id e quebrava sem login
        $this->get('/create')->assertOk();
    }

    public function test_erro_de_pagamento_abre_para_visitante(): void
    {
        $this->get('/erro/pagamento')->assertOk();
    }

    public function test_escola_de_surf_redireciona_em_vez_de_dar_erro(): void
    {
        $this->get('/escola-de-surf')->assertRedirect(route('site.landing'));
    }

    public function test_treino_exige_login(): void
    {
        $this->get('/treino')->assertRedirect();
    }

    public function test_rotas_mortas_deixam_de_dar_erro_500(): void
    {
        $this->get('/registeraluno')->assertNotFound();
        $this->get('/docs.openapi')->assertNotFound();
        $this->get('/debug-host')->assertNotFound();
    }

    public function test_retorno_do_google_sem_codigo_volta_ao_login(): void
    {
        Http::fake(['oauth2.googleapis.com/*' => Http::response(['error' => 'invalid_request'], 400)]);

        foreach (['/google/callback', '/google/prof/callback', '/loginGoogle', '/auth/callback/google'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }
}
