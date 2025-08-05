<?php

use Google\Ads\GoogleAds\Lib\V16\GoogleAdsClientBuilder;
use Google\Auth\OAuth2;
use App\Models\Usuario;

class GoogleAdsService
{

    public function getClientForUser(Usuario $user)
    {
        $oAuth2Credential = (new OAuth2())
            ->setClientId(env('GOOGLE_CLIENT_ID'))
            ->setClientSecret(env('GOOGLE_CLIENT_SECRET'))
            ->setRefreshToken($user->google_refresh_token);

        $client = (new GoogleAdsClientBuilder())
            ->withOAuth2Credential($oAuth2Credential)
            ->withDeveloperToken(env('GOOGLE_DEVELOPER_TOKEN')) // da sua conta MCC
            ->build();

        return $client;
    }

    public function listarCampanhas(Usuario $user, string $customerId)
    {
        $client = $this->getClientForUser($user);
        $googleAdsService = $client->getGoogleAdsServiceClient();

        $query = 'SELECT campaign.id, campaign.name FROM campaign ORDER BY campaign.id';

        $response = $googleAdsService->search($customerId, $query);

        $campanhas = [];
        foreach ($response->iterateAllElements() as $row) {
            $campanhas[] = [
                'id' => $row->getCampaign()->getId(),
                'nome' => $row->getCampaign()->getName(),
            ];
        }

        return $campanhas;
    }
}
