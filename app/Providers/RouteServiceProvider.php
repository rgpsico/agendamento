<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/teste';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */

    protected function mapPublicWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web_public.php'));
    }

    protected function mapPrivateWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web_private.php'));
    }



    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Rotas publicas que gastam IA (cada chamada custa dinheiro)
        RateLimiter::for('ia', fn (Request $request) => [
            Limit::perMinute(12)->by('ia|' . $request->ip()),
            Limit::perDay(300)->by('ia-dia|' . $request->ip()),
        ]);

        // Chat do widget do bot: por bot (token) e por visitante
        RateLimiter::for('bot', fn (Request $request) => [
            Limit::perMinute(20)->by('bot|' . $request->route('token') . '|' . $request->ip()),
            Limit::perDay(600)->by('bot-dia|' . $request->ip()),
        ]);

        // Login: freia tentativa de senha em massa
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(10)->by('login|' . $request->ip()));

        // Cadastro publico (usuario/aluno): freia criacao de contas em massa
        RateLimiter::for('cadastro', fn (Request $request) => Limit::perMinute(6)->by('cadastro|' . $request->ip()));

        // Formularios de contato/lead: freia spam
        RateLimiter::for('contato', fn (Request $request) => [
            Limit::perMinute(5)->by('contato|' . $request->ip()),
            Limit::perDay(40)->by('contato-dia|' . $request->ip()),
        ]);

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        $this->mapPublicWebRoutes();

        $this->mapPrivateWebRoutes();
    }
}
