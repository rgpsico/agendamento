<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Campanha;
use App\Models\Lead;
use App\Models\Tarefa;
use App\Policies\CampanhaPolicy;
use App\Policies\LeadPolicy;
use App\Policies\TarefaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Lead::class => LeadPolicy::class,
        Campanha::class => CampanhaPolicy::class,
        Tarefa::class => TarefaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
