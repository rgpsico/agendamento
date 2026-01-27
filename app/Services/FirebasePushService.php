<?php

namespace App\Services;

use App\Models\DeviceToken;
use Google_Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebasePushService
{
    private const SCOPE = 'https://www.googleapis.com/auth/firebase.messaging';
    private const CACHE_KEY = 'firebase_access_token';

    private string $serviceAccountPath;
    private string $projectId;

    public function __construct(?string $serviceAccountPath = null)
    {
        $this->serviceAccountPath = $serviceAccountPath ?: storage_path('app/firebase/service-account.json');

        if (!File::exists($this->serviceAccountPath)) {
            throw new \RuntimeException('Firebase service account file not found at: ' . $this->serviceAccountPath);
        }

        $serviceAccount = json_decode(File::get($this->serviceAccountPath), true);
        if (!is_array($serviceAccount) || empty($serviceAccount['project_id'])) {
            throw new \RuntimeException('Firebase service account file is invalid or missing project_id.');
        }

        $this->projectId = $serviceAccount['project_id'];
    }

    public function sendToToken(string $token, string $title, string $body, array $data = []): array
    {
        // Build FCM v1 payload with notification, data, and android priority.
        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $this->normalizeData($data),
                'android' => [
                    'priority' => 'HIGH',
                ],
            ],
        ];

        try {
            // Send request with OAuth2 access token and a short timeout.
            $response = Http::withToken($this->getAccessToken())
                ->acceptJson()
                ->timeout(10)
                ->post($this->getSendUrl(), $payload);
        } catch (\Throwable $e) {
            // Network or unexpected errors.
            return [
                'success' => false,
                'status' => 0,
                'error' => $e->getMessage(),
            ];
        }

        if ($response->successful()) {
            return [
                'success' => true,
                'status' => $response->status(),
                'response' => $response->json(),
            ];
        }

        return [
            'success' => false,
            'status' => $response->status(),
            'error' => $response->json() ?: $response->body(),
        ];
    }

    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): array
    {
        $results = [];
        foreach ($tokens as $token) {
            $results[$token] = $this->sendToToken($token, $title, $body, $data);
        }

        return $results;
    }

    private function getSendUrl(): string
    {
        return sprintf('https://fcm.googleapis.com/v1/projects/%s/messages:send', $this->projectId);
    }

    private function normalizeData(array $data): array
    {
        // FCM requires data payload values as strings.
        $normalized = [];
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $normalized[$key] = json_encode($value);
                continue;
            }

            if (is_bool($value)) {
                $normalized[$key] = $value ? 'true' : 'false';
                continue;
            }

            if ($value === null) {
                $normalized[$key] = '';
                continue;
            }

            $normalized[$key] = (string) $value;
        }

        return $normalized;
    }

     public function sendToUser(int $userId, string $title, string $body, array $data = []): array
    {
        $results = [
            'sent' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        $tokens = DeviceToken::where('user_id', $userId)->pluck('fcm_token');

        dd($tokens);
        if ($tokens->isEmpty()) {
            return $results;
        }

        foreach ($tokens as $token) {
            try {
                $response = $this->sendToToken($token, $title, $body, $data);
                $results['sent']++;

            } catch (\Throwable $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'token' => $token,
                    'error' => $e->getMessage(),
                ];

                // Se quiser, marque token como inválido aqui
                Log::warning('Push failed', [
                    'user_id' => $userId,
                    'token' => $token,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }

    private function getAccessToken(): string
    {
        // Reuse access token from cache to avoid extra OAuth calls.
        $cachedToken = Cache::get(self::CACHE_KEY);
        if ($cachedToken) {
            return $cachedToken;
        }

        // Build a Google OAuth2 client using the service account JSON.
        $client = new Google_Client();
        $client->setAuthConfig($this->serviceAccountPath);
        $client->setScopes([self::SCOPE]);

        $token = $client->fetchAccessTokenWithAssertion();
        if (isset($token['error'])) {
            throw new \RuntimeException('Failed to fetch Firebase access token: ' . ($token['error_description'] ?? $token['error']));
        }

        // Cache token slightly less than its TTL to avoid edge expiration.
        $expiresIn = (int) ($token['expires_in'] ?? 3600);
        $ttl = max(60, $expiresIn - 60);
        Cache::put(self::CACHE_KEY, $token['access_token'], now()->addSeconds($ttl));

        return $token['access_token'];
    }
}
