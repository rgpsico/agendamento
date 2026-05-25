<?php

namespace App\Http\Controllers;

use App\Models\EmpresaSite;
use App\Models\SiteArtigo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Sitemap dinâmico — resolve o site pelo domínio da requisição.
     * Acesso: GET /sitemap.xml
     */
    public function index(Request $request): Response
    {
        $site = EmpresaSite::resolveByHost($request->getHost());

        $base = 'https://' . $request->getHost();

        // URLs estáticas da landing page
        $static = [
            ['loc' => $base . '/',          'priority' => '1.0',  'changefreq' => 'weekly'],
            ['loc' => $base . '/#sobre',     'priority' => '0.7',  'changefreq' => 'monthly'],
            ['loc' => $base . '/#aulas',     'priority' => '0.8',  'changefreq' => 'weekly'],
            ['loc' => $base . '/#precos',    'priority' => '0.8',  'changefreq' => 'weekly'],
            ['loc' => $base . '/#contato',   'priority' => '0.6',  'changefreq' => 'monthly'],
            ['loc' => $base . '/blog',       'priority' => '0.9',  'changefreq' => 'daily'],
        ];

        // Artigos publicados deste site
        $artigos = $site
            ? SiteArtigo::where('site_id', $site->id)
                ->where('status', 'publicado')
                ->latest('publicado_em')
                ->get(['slug', 'titulo', 'publicado_em', 'updated_at'])
            : collect();

        $xml = $this->buildXml($static, $artigos, $base);

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('X-Robots-Tag', 'noindex'); // o sitemap em si não precisa ser indexado
    }

    private function buildXml(array $static, $artigos, string $base): string
    {
        $urls = '';

        foreach ($static as $item) {
            $urls .= $this->urlTag(
                $item['loc'],
                now()->toAtomString(),
                $item['changefreq'],
                $item['priority']
            );
        }

        foreach ($artigos as $artigo) {
            $lastmod = ($artigo->updated_at ?? $artigo->publicado_em)?->toAtomString()
                     ?? now()->toAtomString();

            $urls .= $this->urlTag(
                $base . '/blog/' . $artigo->slug,
                $lastmod,
                'monthly',
                '0.8'
            );
        }

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
{$urls}</urlset>
XML;
    }

    private function urlTag(string $loc, string $lastmod, string $changefreq, string $priority): string
    {
        return "  <url>\n"
             . "    <loc>" . htmlspecialchars($loc, ENT_XML1) . "</loc>\n"
             . "    <lastmod>{$lastmod}</lastmod>\n"
             . "    <changefreq>{$changefreq}</changefreq>\n"
             . "    <priority>{$priority}</priority>\n"
             . "  </url>\n";
    }
}
