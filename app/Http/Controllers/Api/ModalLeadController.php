<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\ModalCaptura;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModalLeadController extends Controller
{
    public function submit(Request $request, string $token): JsonResponse
    {
        $modal = ModalCaptura::where('token', $token)->where('ativo', true)->first();

        if (! $modal) {
            return response()->json(['error' => 'Widget não encontrado.'], 404);
        }

        $rules = [];
        if ($modal->campo_nome)     $rules['nome']     = 'required|string|max:255';
        if ($modal->campo_email)    $rules['email']    = 'required|email|max:255';
        if ($modal->campo_telefone) $rules['telefone'] = 'required|string|max:30';

        $validated = $request->validate($rules);

        Lead::create([
            'tenant_id'   => $modal->tenant_id,
            'nome'        => $validated['nome']     ?? ($validated['email'] ?? 'Lead'),
            'email'       => $validated['email']    ?? null,
            'telefone'    => $validated['telefone'] ?? null,
            'origem'      => $modal->origem_lead,
            'campanha_id' => $modal->campanha_id,
            'status'      => 'novo',
            'pipeline_status' => 'novo_lead',
            'token'       => Str::uuid(),
        ]);

        return response()->json(['message' => $modal->mensagem_sucesso]);
    }

    public function config(string $token): JsonResponse
    {
        $modal = ModalCaptura::where('token', $token)->where('ativo', true)->first();

        if (! $modal) {
            return response()->json(['error' => 'Widget não encontrado.'], 404);
        }

        return response()->json([
            'titulo'           => $modal->titulo,
            'descricao'        => $modal->descricao,
            'botao_texto'      => $modal->botao_texto,
            'cor_primaria'     => $modal->cor_primaria,
            'campo_nome'       => $modal->campo_nome,
            'campo_email'      => $modal->campo_email,
            'campo_telefone'   => $modal->campo_telefone,
            'mensagem_sucesso' => $modal->mensagem_sucesso,
            'submit_url'       => url('/api/widget/' . $token . '/lead'),
        ]);
    }
}
