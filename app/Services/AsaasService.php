<?php

namespace App\Services;


use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AsaasService
{
    // Defina a propriedade para armazenar o cabeçalho
    private $headers;
    public $url;

    public function __construct()
    {
        // Inicialize o cabeçalho com os valores padrão
        $this->headers = [
            'accept' => 'application/json',
            'access_token' => env('ASAAS_KEY'),
            'content-type' => 'application/json',
        ];

        $this->url = env('APP_ENV') === 'production' ? env('ASAAS_URL') : env('ASAAS_SANDBOX_URL');
    }

    public function tratarResposta($statusCode, $responseData)
    {
        // Se a resposta for 200 (sucesso)
        if ($statusCode == 200) {
            // Aqui você pode retornar os dados da cobrança
            return $responseData;
        }

        // Se a resposta for 400 (erro)
        if ($statusCode == 400) {
            // Aqui você pode retornar uma mensagem de erro ou lançar uma exceção
            throw new \Exception('Erro ao processar a solicitação: ' . $responseData['errorMessage']);
        }

        // Se a resposta for 401 (não autorizado)
        if ($statusCode == 401) {
            // Aqui você pode retornar uma mensagem de erro ou lançar uma exceção
            throw new \Exception('Não autorizado: ' . $responseData['errorMessage']);
        }

        // Se a resposta for de um código de status desconhecido, você pode retornar null ou lançar uma exceção
        throw new \Exception('Resposta desconhecida do servidor');
    }



    public function getCobrancas()
    {
        $client = new Client();

        try {
            $response = $client->request('GET', $this->url, [
                'headers' => $this->headers,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function cobranca($request)
    {
        $client = new Client();

        $jsonData = json_encode($request->all());

        try {
            $response = $client->request('POST', $this->url, [
                'body' => $jsonData,
                'headers' => $this->headers,
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody(), true);

            return $this->tratarResposta($statusCode, $responseData);
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
