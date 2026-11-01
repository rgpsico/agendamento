<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registra_tempo_de_carregamento_valido(): void
    {
        $this->postJson('/analytics/event', ['event_type' => 'page_load_time', 'value' => 1234])->assertOk();

        $this->assertDatabaseHas('analytics_events', ['event_type' => 'page_load_time', 'value' => 1234]);
    }

    /** @test */
    public function recusa_valor_negativo_ou_gigante_em_vez_de_estourar_o_banco(): void
    {
        $this->postJson('/analytics/event', ['event_type' => 'page_load_time', 'value' => -1793407845276])->assertStatus(422);
        $this->postJson('/analytics/event', ['event_type' => 'page_load_time', 'value' => 99999999999])->assertStatus(422);

        $this->assertDatabaseCount('analytics_events', 0);
    }

    /** @test */
    public function recusa_tipo_de_evento_desconhecido(): void
    {
        $this->postJson('/analytics/event', ['event_type' => 'qualquer-coisa'])->assertStatus(422);
    }
}
