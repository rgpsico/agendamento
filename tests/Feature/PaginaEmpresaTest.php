<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginaEmpresaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function empresa_inexistente_devolve_404_e_nao_500(): void
    {
        $this->get('/999999/empresa')->assertNotFound();
    }

    /** @test */
    public function pagina_inicial_abre_em_host_sem_configuracao_de_nicho(): void
    {
        // o cabecalho usa $currentNicho; sem nicho para o host a variavel precisa existir como null
        $this->get('http://host-sem-nicho.test/')->assertOk();
    }
}
