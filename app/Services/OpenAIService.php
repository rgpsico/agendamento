<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected string $apiUrl;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('openai.api_url');
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function chat(array $messages, string $model = 'gpt-3.5-turbo')
    {
        if (!$this->apiKey) {
            throw new \RuntimeException('OpenAI API key not configured');
        }

        return Http::withToken($this->apiKey)->post($this->apiUrl, [
            'model' => $model,
            'messages' => $messages,
        ]);
    }
}
