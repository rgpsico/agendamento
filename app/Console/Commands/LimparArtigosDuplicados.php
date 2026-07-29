<?php

namespace App\Console\Commands;

use App\Models\SiteArtigo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LimparArtigosDuplicados extends Command
{
    protected $signature = 'artigo:limpar-duplicados
                            {--site_id= : Limpar apenas este site}
                            {--dry-run  : Mostra o que seria removido sem deletar}';

    protected $description = 'Remove artigos duplicados mantendo o mais antigo por site';

    public function handle(): int
    {
        $query = SiteArtigo::query();

        if ($this->option('site_id')) {
            $query->where('site_id', (int) $this->option('site_id'));
        }

        // Agrupa por site_id + primeiros 50 chars do título
        $artigos = $query->orderBy('id')->get();

        $grupos  = [];
        $remover = [];

        foreach ($artigos as $artigo) {
            $chave = $artigo->site_id . '|' . strtolower(substr($artigo->titulo, 0, 50));

            if (isset($grupos[$chave])) {
                // Já existe um mais antigo — este é duplicado
                $remover[] = $artigo->id;
                $this->line("  Duplicado #{$artigo->id} — " . substr($artigo->titulo, 0, 60));
            } else {
                $grupos[$chave] = $artigo->id;
            }
        }

        if (empty($remover)) {
            $this->info('Nenhum artigo duplicado encontrado.');
            return self::SUCCESS;
        }

        $this->warn(count($remover) . ' artigo(s) duplicado(s) encontrado(s).');

        if ($this->option('dry-run')) {
            $this->info('Dry-run: nada foi removido.');
            return self::SUCCESS;
        }

        SiteArtigo::whereIn('id', $remover)->delete();

        $this->info(count($remover) . ' artigo(s) removido(s) com sucesso.');

        Log::info('LimparArtigosDuplicados: limpeza concluida', [
            'removidos' => count($remover),
            'ids'       => $remover,
        ]);

        return self::SUCCESS;
    }
}
