<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Google\Ads\GoogleAds\V16\Services\CreateCustomerClientRequest;
use Google\Ads\GoogleAds\V16\Resources\Customer;

class GoogleADSController extends Controller
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;

    public function __construct()
    {
        $this->clientId = env('GOOGLE_CLIENT_ID');
        $this->clientSecret = env('GOOGLE_CLIENT_SECRET');
        $this->redirectUri = env('GOOGLE_REDIRECT_URL');
    }

    public function getAuthUrl()
    {
        $baseUrl = 'https://accounts.google.com/o/oauth2/v2/auth';

        $params = [
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'redirect_uri' => env('GOOGLE_REDIRECT_URL'),
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/adwords',
            'access_type' => 'offline',
            'prompt' => 'consent'
        ];

        $authUrl = $baseUrl . '?' . http_build_query($params);

        return response()->json(['auth_url' => $authUrl]);
    }


    public function handleCallback(Request $request)
    {
        $code = $request->get('code');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'authorization_code',
        ]);

        if ($response->successful()) {
            $tokens = $response->json();
            // Salve os tokens (access_token, refresh_token) no banco (por user ou conta Google Ads)
            return response()->json($tokens);
        }

        return response()->json(['error' => 'Erro ao obter tokens'], 400);
    }

    public function createCampaign(Request $request)
    {
        // Exemplo simplificado: normalmente usaria SDK oficial do Google Ads (gRPC via PHP)
        $accessToken = $request->get('access_token');
        $customerId = $request->get('customer_id');

        // Aqui você precisa usar a Google Ads API (gRPC ou REST) para criar campanha
        // Com PHP é recomendado usar o SDK oficial: https://github.com/googleads/google-ads-php

        return response()->json(['message' => 'Requisição de criação de campanha recebida.']);
    }


    public function createCustomerClient(Request $request)
    {
        $customer = new Customer([
            'descriptive_name' => 'Nome do cliente',
            'currency_code' => 'BRL',
            'time_zone' => 'America/Sao_Paulo',
        ]);

        $createCustomerClientRequest = new CreateCustomerClientRequest([
            'customer_id' => $mccId, // ID da conta MCC (conta master)
            'customer_client' => $customer,
        ]);

        $response = $googleAdsClient->getCustomerServiceClient()
            ->createCustomerClient($createCustomerClientRequest);

        $newCustomerId = $response->getResourceName();
    }
}
