<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bot;
use App\Services\DeepSeekService;
use Illuminate\Support\Facades\Http;

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

    public function analyzeImage(Request $request)
    {

        $request->validate([
            'image' => 'required|image|max:10240', // até 10MB
        ]);

        // Converte a imagem para Base64
        $imageBase64 = base64_encode(file_get_contents($request->file('image')->getRealPath()));

        // Chamada à API do DeepSeek
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('DEEP_SEEK_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model' => 'deepseek-chat',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Você é um assistente que descreve imagens de forma detalhada e precisa.'
                ],
                [
                    'role' => 'user',
                    'content' => 'Descreva o que há nesta imagem:',
                    'image_data' => $imageBase64
                ],
            ],
            'temperature' => 0.7,
            'max_tokens' => 500,
        ]);


        if (!$response->successful()) {
            return response()->json([
                'error' => 'Erro na API DeepSeek',
                'details' => $response->body(),
            ], 500);
        }

        $data = $response->json();

        $description = $data['choices'][0]['message']['content'] ?? 'Sem descrição';

        return response()->json([
            'description' => $description
        ]);
    }
}
