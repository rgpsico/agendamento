<?php

namespace App\Services;

use App\Models\SocialConnection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaGraphService
{
    private string $appId;
    private string $appSecret;
    private string $apiVersion = 'v19.0';
    private string $baseUrl;

    public function __construct()
    {
        $this->appId     = config('services.meta.app_id');
        $this->appSecret = config('services.meta.app_secret');
        $this->baseUrl   = "https://graph.facebook.com/{$this->apiVersion}";
    }

    /* ─────────────────────────────────────────────────────────
     |  OAuth — URL de autorização
     ──────────────────────────────────────────────────────── */
    public function getOAuthUrl(string $redirectUri): string
    {
        $scopes = implode(',', [
            'pages_manage_posts',
            'pages_read_engagement',
            'instagram_basic',
            'instagram_content_publish',
            'pages_show_list',
        ]);

        return "https://www.facebook.com/{$this->apiVersion}/dialog/oauth?"
            . http_build_query([
                'client_id'     => $this->appId,
                'redirect_uri'  => $redirectUri,
                'scope'         => $scopes,
                'response_type' => 'code',
            ]);
    }

    /* ─────────────────────────────────────────────────────────
     |  OAuth — Troca code por token de longa duração
     ──────────────────────────────────────────────────────── */
    public function exchangeCodeForToken(string $code, string $redirectUri): array
    {
        // 1. Token de curta duração
        $shortRes = Http::get("{$this->baseUrl}/oauth/access_token", [
            'client_id'     => $this->appId,
            'client_secret' => $this->appSecret,
            'redirect_uri'  => $redirectUri,
            'code'          => $code,
        ]);

        if (!$shortRes->successful()) {
            throw new \Exception('Erro ao obter token: ' . $shortRes->body());
        }

        $shortToken = $shortRes->json('access_token');

        // 2. Troca por token de longa duração (60 dias)
        $longRes = Http::get("{$this->baseUrl}/oauth/access_token", [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => $this->appId,
            'client_secret'     => $this->appSecret,
            'fb_exchange_token' => $shortToken,
        ]);

        if (!$longRes->successful()) {
            throw new \Exception('Erro ao renovar token: ' . $longRes->body());
        }

        return [
            'access_token' => $longRes->json('access_token'),
            'expires_in'   => $longRes->json('expires_in', 5184000), // 60 dias padrão
        ];
    }

    /* ─────────────────────────────────────────────────────────
     |  Busca as páginas que o usuário gerencia
     ──────────────────────────────────────────────────────── */
    public function getUserPages(string $userToken): array
    {
        $res = Http::get("{$this->baseUrl}/me/accounts", [
            'access_token' => $userToken,
            'fields'       => 'id,name,access_token,instagram_business_account',
        ]);

        if (!$res->successful()) {
            throw new \Exception('Erro ao buscar páginas: ' . $res->body());
        }

        return $res->json('data', []);
    }

    /* ─────────────────────────────────────────────────────────
     |  Busca username do Instagram
     ──────────────────────────────────────────────────────── */
    public function getInstagramUsername(string $igAccountId, string $pageToken): string
    {
        $res = Http::get("{$this->baseUrl}/{$igAccountId}", [
            'fields'       => 'username',
            'access_token' => $pageToken,
        ]);

        return $res->json('username', '');
    }

    /* ─────────────────────────────────────────────────────────
     |  Postar no Facebook Page
     ──────────────────────────────────────────────────────── */
    public function postToFacebook(SocialConnection $conn, string $mensagem, ?string $link = null): array
    {
        if (!$conn->hasFacebook()) {
            throw new \Exception('Facebook não conectado.');
        }

        $payload = [
            'message'      => $mensagem,
            'access_token' => $conn->facebook_page_token,
        ];

        if ($link) {
            $payload['link'] = $link;
        }

        $res = Http::post("{$this->baseUrl}/{$conn->facebook_page_id}/feed", $payload);

        if (!$res->successful()) {
            Log::error('MetaGraph: erro ao postar no Facebook', ['body' => $res->body()]);
            throw new \Exception('Erro ao postar no Facebook: ' . ($res->json('error.message') ?? $res->body()));
        }

        return ['post_id' => $res->json('id'), 'rede' => 'facebook'];
    }

    /* ─────────────────────────────────────────────────────────
     |  Postar no Instagram (requer imagem)
     ──────────────────────────────────────────────────────── */
    public function postToInstagram(SocialConnection $conn, string $legenda, string $imageUrl): array
    {
        if (!$conn->hasInstagram()) {
            throw new \Exception('Instagram não conectado.');
        }

        // Passo 1 — Criar container de mídia
        $containerRes = Http::post("{$this->baseUrl}/{$conn->instagram_account_id}/media", [
            'image_url'    => $imageUrl,
            'caption'      => $legenda,
            'access_token' => $conn->facebook_page_token,
        ]);

        if (!$containerRes->successful()) {
            Log::error('MetaGraph: erro ao criar container IG', ['body' => $containerRes->body()]);
            throw new \Exception('Erro ao criar mídia no Instagram: ' . ($containerRes->json('error.message') ?? $containerRes->body()));
        }

        $creationId = $containerRes->json('id');

        // Passo 2 — Publicar
        $publishRes = Http::post("{$this->baseUrl}/{$conn->instagram_account_id}/media_publish", [
            'creation_id'  => $creationId,
            'access_token' => $conn->facebook_page_token,
        ]);

        if (!$publishRes->successful()) {
            Log::error('MetaGraph: erro ao publicar no IG', ['body' => $publishRes->body()]);
            throw new \Exception('Erro ao publicar no Instagram: ' . ($publishRes->json('error.message') ?? $publishRes->body()));
        }

        return ['post_id' => $publishRes->json('id'), 'rede' => 'instagram'];
    }

    /* ─────────────────────────────────────────────────────────
     |  Monta texto do post a partir de um artigo
     ──────────────────────────────────────────────────────── */
    public function montarTextoArtigo(string $titulo, string $resumo, string $url): string
    {
        return "{$titulo}\n\n{$resumo}\n\n🔗 Leia o artigo completo: {$url}";
    }
}
