<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- 1. ADICIONE ISSO

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot()
    {
        // Força HTTPS se estiver em produção ou se você quiser forçar sempre
        if ($this->app->environment('production') || env('APP_ENV') === 'production') {
            URL::forceScheme('https'); // <--- 2. ADICIONE ISSO
        }
        
        // DICA: Se estiver testando e o APP_ENV for local,
        // você pode remover o 'if' temporariamente para testar:
        // URL::forceScheme('https'); 
    }
}
