<?php

namespace App\Jobs;

use App\Models\EmpresaSite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AtualizarConfiguracoesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $site;

    public function __construct(array $data, EmpresaSite $site)
    {
        $this->data = $data;
        $this->site = $site;
    }

    public function handle()
    {
        // Aumenta o tempo máximo só no Job
        set_time_limit(900);

        // Atualiza o site
        $this->site->update($this->data);

        // Se marcou gerar VHost e domínio válido
        if (!empty($this->site->dominio_personalizado) && $this->site->gerar_vhost) {
            try {
                // Aqui você chama seu método de VHost
                app(\App\Http\Controllers\SiteController::class)
                    ->criarOuAtualizarVirtualHost($this->site->dominio_personalizado);
            } catch (\Exception $e) {
                // Aqui você pode logar o erro
                \Log::error('Erro ao criar Virtual Host: ' . $e->getMessage());
            }
        }
    }
}
