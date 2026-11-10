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

        // Artigos publicados deste site
        $artigos = $site
            ? SiteArtigo::where('site_id', $site->id)
                ->where('status', 'publicado')
                ->latest('publicado_em')
                ->get(['slug', 'titulo', 'publicado_em', 'updated_at'])
            : collect();

        // Site desligado (ativo = false) não vai para o Google: sitemap vazio
        // (as páginas dele também saem com noindex).
        $static = [];
        if (!$site || $site->ativo) {
            // Âncoras (#sobre, #contato...) não são páginas: o Google ignora, então só a raiz
            $static[] = ['loc' => $base . '/', 'priority' => '1.0', 'changefreq' => 'weekly'];

            if ($artigos->isNotEmpty()) {
                $static[] = ['loc' => $base . '/blog', 'priority' => '0.9', 'changefreq' => 'daily'];
            }
        } else {
            $artigos = collect();
        }

        $xml = $this->buildXml($static, $artigos, $base);

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('X-Robots-Tag', 'noindex'); // o sitemap em si não precisa ser indexado
    }

    /**
     * robots.txt dinâmico — cada domínio aponta para o próprio sitemap.
     * Site desligado (ativo = false) bloqueia tudo.
     * Acesso: GET /robots.txt
     */
    public function robots(Request $request): Response
    {
        $site = EmpresaSite::resolveByHost($request->getHost());

        if ($site && !$site->ativo) {
            $txt = "User-agent: *\nDisallow: /\n";
        } else {
            $txt = "User-agent: *\n"
                 . "Allow: /\n"
                 . "Disallow: /admin\n"
                 . "Disallow: /super-admin\n"
                 . "Disallow: /login\n"
                 . "Disallow: /register\n"
                 . "Disallow: /api\n\n"
                 . "Sitemap: https://" . $request->getHost() . "/sitemap.xml\n";
        }

        return response($txt, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
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
