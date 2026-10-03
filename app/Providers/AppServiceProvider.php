<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

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
        if (str_starts_with(config('app.url', ''), 'https://')) {
            URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();

        // Páginas legais (termos, privacidade, LGPD): marca, domínio e público do nicho do domínio acessado.
        // O nicho original (pilates) mantém o texto de sempre.
        View::composer(['layouts.legal', 'legal.*'], function ($view) {
            $nicho = app()->has('currentNicho') ? app('currentNicho') : null;
            $proprio = $nicho && ! $nicho->usaMarcaGlobal();

            $view->with([
                'marca'        => $proprio ? $nicho->nome : 'PilatesGestão',
                'marcaDominio' => $proprio ? ($nicho->dominio ?: request()->getHost()) : 'pilatesgestao.com.br',
                'marcaPublico' => $proprio ? 'empresas e profissionais do segmento' : 'estúdios de pilates',
            ]);
        });

        Blade::if('masterUser', function () {
            return auth()->check() && auth()->user()->isMasterUser();
        });

        if (class_exists(\Laravel\Horizon\Horizon::class)) {
            \Laravel\Horizon\Horizon::auth(function ($request) {
                return auth()->check();
            });
        }

        // DICA: Se estiver testando e o APP_ENV for local,
        // você pode remover o 'if' temporariamente para testar:
        // URL::forceScheme('https'); 
    }
}
