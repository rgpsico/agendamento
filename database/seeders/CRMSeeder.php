<?php

namespace Database\Seeders;

use App\Models\Campanha;
use App\Models\Empresa;
use App\Models\Lead;
use App\Models\Tarefa;
use Illuminate\Database\Seeder;

class CRMSeeder extends Seeder
{
    public function run(): void
    {
        $empresa = Empresa::query()->first();

        if (! $empresa) {
            return;
        }

        $campanhas = collect([
            ['nome' => 'Instagram - Aula Experimental', 'canal' => 'instagram', 'custo' => 450],
            ['nome' => 'Google - Pilates perto de mim', 'canal' => 'google', 'custo' => 620],
            ['nome' => 'Indicacao de alunos', 'canal' => 'indicacao', 'custo' => 0],
        ])->map(fn ($data) => Campanha::firstOrCreate(
            ['tenant_id' => $empresa->id, 'nome' => $data['nome']],
            $data + ['tenant_id' => $empresa->id, 'ativo' => true]
        ));

        $leads = [
            ['nome' => 'Ana Martins', 'telefone' => '(11) 99999-1001', 'email' => 'ana@example.com', 'origem' => 'instagram', 'interesse' => 'Pilates solo', 'pipeline_status' => 'novo_lead', 'valor_estimado' => 280],
            ['nome' => 'Beatriz Lima', 'telefone' => '(11) 99999-1002', 'email' => 'bia@example.com', 'origem' => 'whatsapp', 'interesse' => 'Aula experimental', 'pipeline_status' => 'em_contato', 'valor_estimado' => 320],
            ['nome' => 'Carla Souza', 'telefone' => '(11) 99999-1003', 'email' => 'carla@example.com', 'origem' => 'google', 'interesse' => 'Pilates aparelho', 'pipeline_status' => 'aula_experimental', 'valor_estimado' => 450],
            ['nome' => 'Daniel Rocha', 'telefone' => '(11) 99999-1004', 'email' => 'daniel@example.com', 'origem' => 'indicacao', 'interesse' => 'Reabilitacao', 'pipeline_status' => 'matriculado', 'valor_estimado' => 520],
            ['nome' => 'Elisa Costa', 'telefone' => '(11) 99999-1005', 'email' => 'elisa@example.com', 'origem' => 'site', 'interesse' => 'Plano recorrente', 'pipeline_status' => 'recorrente', 'valor_estimado' => 680],
        ];

        foreach ($leads as $index => $data) {
            $lead = Lead::firstOrCreate(
                ['tenant_id' => $empresa->id, 'email' => $data['email']],
                $data + [
                    'tenant_id' => $empresa->id,
                    'status' => in_array($data['pipeline_status'], ['matriculado', 'recorrente'], true) ? 'convertido' : 'novo',
                    'campanha_id' => $campanhas[$index % $campanhas->count()]->id,
                ]
            );

            Tarefa::firstOrCreate(
                ['tenant_id' => $empresa->id, 'lead_id' => $lead->id, 'descricao' => 'Fazer follow-up comercial'],
                ['tipo' => 'follow_up', 'vencimento' => now()->addHours($index + 1), 'concluida' => false]
            );
        }
    }
}
