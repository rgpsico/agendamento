<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;

class OpenAIController extends Controller
{
    public function testConnection(Request $request, OpenAIService $openAIService)
    {
        try {
            $response = $openAIService->chat([
                ['role' => 'user', 'content' => 'Diga olá'],
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json([
            'error' => 'Failed to connect to OpenAI',
            'response' => $response->json(),
        ], $response->status());
    }
}
