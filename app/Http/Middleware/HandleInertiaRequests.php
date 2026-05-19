<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     * Todos os componentes Vue têm acesso a `$page.props.tenant` e `$page.props.site`.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $site    = app()->has('currentSite')   ? app('currentSite')   : null;
        $empresa = app()->has('currentTenant') ? app('currentTenant') : null;

        return array_merge(parent::share($request), [

            // Dados do tenant (empresa) — disponível em todos os componentes Vue
            'tenant' => $empresa ? [
                'id'         => $empresa->id,
                'nome'       => $empresa->nome,
                'modalidade' => $empresa->modalidade?->nome,
                'avatar'     => $empresa->avatar,
            ] : null,

            // Dados de branding do site — cores, logo, título
            'site' => $site ? [
                'titulo' => $site->titulo,
                'slug'   => $site->slug,
                'logo'   => $site->logo,
                'cores'  => $site->cores,  // ['primaria' => '#1E90FF', 'secundaria' => '#FFD700']
            ] : null,

        ]);
    }
}
