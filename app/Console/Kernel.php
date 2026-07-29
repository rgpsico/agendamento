<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Gera artigos SEO automaticamente (publica direto) — 7h e 19h
        $schedule->command('artigo:gerar --publicar')
                 ->twiceDaily(7, 19)
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/artigo-seo.log'));

        // Remove artigos duplicados diariamente às 2h
        $schedule->command('artigo:limpar-duplicados')
                 ->dailyAt('02:00')
                 ->appendOutputTo(storage_path('logs/artigo-seo.log'));

        // Regera o sitemap.xml: às 3h e logo após cada lote de artigos (07:05 e 19:05)
        $schedule->command('sitemap:gerar')->dailyAt('03:00');
        $schedule->command('sitemap:gerar')->dailyAt('07:05');
        $schedule->command('sitemap:gerar')->dailyAt('19:05');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
