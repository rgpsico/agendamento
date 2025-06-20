<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class DispararBoletosMensais extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:disparar-boletos-mensais';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Responsavel por disparar emails pra todos os assinates';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        $empresas = Empresa::with(['user', 'professores.usuario'])->get();

        $contador = 0;
        $emailsEnviados = [];

        foreach ($empresas as $empresa) {
            foreach ($empresa->professores as $professor) {
                if (!$professor->asaas_customer_id || !$professor->usuario || !$professor->usuario->email) {
                    continue; // pula se o professor não tiver asaas ou e-mail
                }

                $baseUrl = rtrim(env('ASAAS_ENV', 'sandbox') === 'production'
                    ? env('ASAAS_URL', 'https://api.asaas.com')
                    : env('ASAAS_SANDBOX_URL', 'https://api-sandbox.asaas.com/'), '/');

                $response = Http::withHeaders([
                    'accept' => 'application/json',
                    'access_token' => env('ASAAS_API_KEY'),
                ])->post("{$baseUrl}/v3/payments", [
                    'billingType' => 'BOLETO',
                    'customer' => $professor->asaas_customer_id,
                    'value' => 129.90,
                    'dueDate' => now()->addDays(5)->format('Y-m-d'),
                ]);

                if ($response->successful()) {
                    $link = $response->json()['bankSlipUrl'];

                    Mail::raw("Olá {$professor->usuario->nome}, seu boleto está disponível no link: $link", function ($message) use ($professor) {
                        $message->to($professor->usuario->email)
                            ->subject('Seu boleto foi gerado');
                    });

                    $contador++;
                    $emailsEnviados[] = $professor->usuario->email;
                }
            }
        }

        // Feedback no terminal
        $this->info("Boletos enviados com sucesso: $contador");

        if (!empty($emailsEnviados)) {
            $this->info("E-mails enviados:");
            foreach ($emailsEnviados as $email) {
                $this->line("- $email");
            }
        } else {
            $this->warn("Nenhum e-mail foi enviado.");
        }
    }
}
