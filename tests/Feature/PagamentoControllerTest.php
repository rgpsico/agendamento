<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PagamentoControllerTest extends TestCase
{
    use RefreshDatabase; // Isto irá limpar o banco de dados após cada teste

    /** @test */
    public function it_processes_payment_with_stripe()
    {
        // Suposições iniciais (por exemplo, um usuário logado, um aluno e um professor específico, etc.)

        $response = $this->post('/your_route_to_pagamentoStripe', [
            'stripeToken' => 'your_mocked_token',
            'aluno_id' => 'sample_aluno_id',

        ]);

        // Verificações
        // Por exemplo: verifique se o agendamento foi criado, se o usuário foi redirecionado para a página correta, etc.
        $response->assertRedirect('your_expected_route_after_successful_payment');
        $this->assertDatabaseHas('agendamentos', [
            'aluno_id' => 'sample_aluno_id',
            // ... outros dados a serem verificados
        ]);
    }
}
