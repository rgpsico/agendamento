<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidadorWebhookController extends Controller
{
    public function store(Request $request)
    {
        if ($request->header('X-Webhook-Secret') !== config('services.validador.webhook_secret')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'campaign_slug' => 'required|string',
            'campaign_titulo' => 'required|string',
            'lead_uuid' => 'required|string',
            'nome' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'email' => 'nullable|email',
            'score' => 'required|integer',
            'classificacao' => 'required|string',
            'respostas' => 'array',
            'respostas.*.pergunta' => 'string',
            'respostas.*.resposta' => 'nullable',
        ]);

        $observacoes = "Campanha: {$data['campaign_titulo']} ({$data['campaign_slug']})\n";
        $observacoes .= "Score: {$data['score']} | Classificação: {$data['classificacao']}\n\n";
        foreach ($data['respostas'] ?? [] as $r) {
            $resposta = is_array($r['resposta']) ? json_encode($r['resposta']) : $r['resposta'];
            $observacoes .= "- {$r['pergunta']}: {$resposta}\n";
        }

        $lead = Lead::updateOrCreate(
            ['token' => $data['lead_uuid']],
            [
                'tenant_id' => null,
                'nome' => $data['nome'] ?? 'Lead do validador',
                'email' => $data['email'] ?? null,
                'telefone' => $data['whatsapp'] ?? null,
                'origem' => 'validador',
                'status' => 'novo',
                'pipeline_status' => 'novo_lead',
                'interesse' => $data['classificacao'],
                'observacoes' => $observacoes,
            ]
        );

        Log::info('ValidadorWebhook: lead criado', ['lead_id' => $lead->id, 'campaign' => $data['campaign_slug']]);

        return response()->json(['id' => $lead->id], 201);
    }
}
