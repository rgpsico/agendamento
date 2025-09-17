<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bot;
use App\Services\DeepSeekService;

class DeepSeekController extends Controller
{
    protected $deepSeekService;

    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }

    // Endpoint para enviar mensagem ao DeepSeek
    public function sendMessage(Request $request, $bot_id)
    {


        $request->validate([
            'message' => 'required|string',
            'empresa_id' => 'required|integer',
        ]);


        $bot = Bot::findOrFail($bot_id);

        // Envia a mensagem para o serviço
        $response = $this->deepSeekService->getDeepSeekResponse(
            $bot,
            $request->message,
            $request->empresa_id
        );


        // Se a API retornar usage (tokens), podemos pegar aqui
        $tokensUsados = 0;
        if (isset($response['usage']['total_tokens'])) {
            $tokensUsados = $response['usage']['total_tokens'];
        }

        return response()->json([
            'response' => $response,
            'tokens_used' => $tokensUsados
        ]);
    }
}
