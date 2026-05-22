<?php

namespace App\Console\Commands;

use App\Models\EmpresaSite;
use App\Models\SiteArtigo;
use Illuminate\Console\Command;

class GerarSitemap extends Command
{
    protected $signature   = 'sitemap:gerar {--site_id= : ID do site (opcional)}';
    protected $description = 'Gera public/sitemap.xml para cada site ativo';

    public function handle(): int
    {
        $sites = EmpresaSite::with('artigos')
            ->when($this->option('site_id'), fn($q) => $q->where('id', $this->option('site_id')))
            ->where('ativo', true)
            ->get();

        if ($sites->isEmpty()) {
            $this->warn('Nenhum site ativo encontrado.');
            return self::SUCCESS;
        }

        foreach ($sites as $site) {
            $this->gerarParaSite($site);
        }

        $this->info('Sitemap(s) gerado(s) com sucesso!');
        return self::SUCCESS;
    }

    private function gerarParaSite(EmpresaSite $site): void
    {
        // Usa domínio personalizado, senão slug no domínio base
        $base = $site->dominio_personalizado
            ? 'https://' . $site->dominio_personalizado
            : config('app.url') . '/site/' . $site->slug;

        $artigos = SiteArtigo::where('site_id', $site->id)
            ->where('status', 'publicado')
            ->latest('publicado_em')
            ->get(['slug', 'publicado_em', 'updated_at']);

        $static = [
            ['loc' => $base . '/',        'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => $base . '/#aulas',  'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => $base . '/#precos', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => $base . '/#sobre',  'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => $base . '/#contato','priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => $base . '/blog',    'priority' => '0.9', 'changefreq' => 'daily'],
        ];

        $urls = '';
        foreach ($static as $item) {
            $urls .= $this->tag($item['loc'], now()->toAtomString(), $item['changefreq'], $item['priority']);
        }
        foreach ($artigos as $a) {
            $lastmod = ($a->updated_at ?? $a->publicado_em)?->toAtomString() ?? now()->toAtomString();
            $urls .= $this->tag($base . '/blog/' . $a->slug, $lastmod, 'monthly', '0.8');
        }

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$urls}</urlset>
XML;

        // Salva em public/sitemap.xml  (para o site principal)
        // Para multi-tenant futuro: public/sitemaps/{slug}.xml
        $path = public_path('sitemap.xml');
        file_put_contents($path, $xml);

        $this->line("  ✓ {$base} → {$path} (" . $artigos->count() . " artigos)");
    }

    private function tag(string $loc, string $lastmod, string $changefreq, string $priority): string
    {
        $loc = htmlspecialchars($loc, ENT_XML1);
        return "  <url>\n    <loc>{$loc}</loc>\n    <lastmod>{$lastmod}</lastmod>\n    <changefreq>{$changefreq}</changefreq>\n    <priority>{$priority}</priority>\n  </url>\n";
    }
}
