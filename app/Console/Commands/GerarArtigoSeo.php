<?php

namespace App\Console\Commands;

use App\Models\EmpresaSite;
use App\Models\SiteArtigo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GerarArtigoSeo extends Command
{
    protected $signature = 'artigo:gerar
                            {--site_id=    : ID do site (padrão: todos os sites ativos)}
                            {--tema=       : Tema específico do artigo (opcional)}
                            {--publicar    : Publica imediatamente (padrão: salva como rascunho)}
                            {--dry-run     : Mostra o artigo sem salvar no banco}';

    protected $description = 'Gera artigos SEO automaticamente via DeepSeek AI';

    /* ──────────────────────────────────────────────────
     |  Temas por nicho — rotacionados automaticamente
     ─────────────────────────────────────────────────── */
    private array $temasPorNicho = [
        'surf' => [
            'Como escolher a prancha certa para o nível de surf',
            'Melhores praias para aprender a surfar no Brasil',
            'Dicas de aquecimento antes de entrar no mar',
            'Etiqueta no line-up: regras não escritas do surf',
            'Como ler as ondas: guia para iniciantes',
            'Benefícios físicos e mentais do surf',
            'Equipamentos essenciais para quem está começando no surf',
            'Como evoluir do surf iniciante para intermediário',
            'Surf em família: como introduzir crianças ao esporte',
            'Segurança no mar: o que todo surfista deve saber',
            'Cuidados com a prancha e o leash',
            'A importância do preparo físico para surfistas',
        ],
        'pilates' => [
            'Pilates para iniciantes: por onde começar',
            'Benefícios do pilates para a postura no dia a dia',
            'Pilates versus academia: qual escolher',
            'Como o pilates ajuda na recuperação de lesões',
            'Pilates na gravidez: benefícios e cuidados',
            'Frequência ideal de treinos de pilates por semana',
            'Diferenças entre pilates solo e aparelho',
        ],
        'natacao' => [
            'Como aprender a nadar do zero: guia completo',
            'Benefícios da natação para todas as idades',
            'Estilos de natação e quando usar cada um',
            'Como melhorar o fôlego na natação',
        ],
        'esportes' => [
            'Como criar o hábito de praticar esportes',
            'Benefícios dos esportes aquáticos para a saúde',
            'Como escolher o esporte certo para o seu perfil',
            'A importância do alongamento no esporte',
        ],
        'geral' => [
            'Como escolher uma escola de esportes de qualidade',
            'Benefícios de praticar esportes desde a infância',
            'Dicas para manter a consistência nos treinos',
            'Como a atividade física melhora a saúde mental',
            'Por que investir em aulas com professores profissionais',
        ],
    ];

    public function handle(): int
    {
        $apiKey = config('services.deepseek.key');
        if (!$apiKey) {
            $this->error('DEEP_SEEK_API_KEY não configurada no .env');
            return self::FAILURE;
        }

        // Sites alvo
        $query = EmpresaSite::where('ativo', true);
        if ($this->option('site_id')) {
            $query->where('id', (int) $this->option('site_id'));
        }
        $sites = $query->get();

        if ($sites->isEmpty()) {
            $this->warn('Nenhum site ativo encontrado.');
            return self::SUCCESS;
        }

        foreach ($sites as $site) {
            $this->gerarParaSite($site, $apiKey);
        }

        return self::SUCCESS;
    }

    private function gerarParaSite(EmpresaSite $site, string $apiKey): void
    {
        $this->info("Gerando artigo para: {$site->nome_empresa} (site #{$site->id})");

        // Escolhe tema
        $tema = $this->option('tema') ?: $this->escolherTema($site);
        $this->line("  Tema: {$tema}");

        // Monta prompt SEO
        $prompt = $this->montarPrompt($tema, $site);

        // Chama DeepSeek
        $this->line('  Chamando DeepSeek AI...');
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(120)->post('https://api.deepseek.com/v1/chat/completions', [
                'model'       => 'deepseek-chat',
                'temperature' => 0.7,
                'max_tokens'  => 3000,
                'messages'    => [
                    ['role' => 'system', 'content' => $this->systemPrompt($site)],
                    ['role' => 'user',   'content' => $prompt],
                ],
            ]);

            if (!$response->successful()) {
                $this->error("  Erro na API: " . $response->body());
                return;
            }

            $content = $response->json('choices.0.message.content') ?? '';
            if (empty($content)) {
                $this->error('  Resposta vazia da API.');
                return;
            }

            // Extrai título, resumo e conteúdo do retorno
            $artigo = $this->parsearResposta($content, $tema);

            if ($this->option('dry-run')) {
                $this->info("\n=== DRY RUN ===");
                $this->line("Título: {$artigo['titulo']}");
                $this->line("Resumo: {$artigo['resumo']}");
                $this->line("Conteúdo (" . strlen($artigo['conteudo']) . " chars):");
                $this->line(substr($artigo['conteudo'], 0, 300) . '...');
                return;
            }

            // Evita duplicata pelo título
            $jaExiste = SiteArtigo::where('site_id', $site->id)
                ->where('titulo', $artigo['titulo'])
                ->exists();

            if ($jaExiste) {
                $this->warn("  Artigo com esse título já existe — pulando.");
                return;
            }

            $status = $this->option('publicar')
                ? SiteArtigo::STATUS_PUBLICADO
                : SiteArtigo::STATUS_RASCUNHO;

            $registro = SiteArtigo::create([
                'site_id'  => $site->id,
                'titulo'   => $artigo['titulo'],
                'resumo'   => $artigo['resumo'],
                'conteudo' => $artigo['conteudo'],
                'status'   => $status,
            ]);

            $this->info("  ✓ Artigo #{$registro->id} salvo como '{$status}': {$artigo['titulo']}");

            Log::info('GerarArtigoSeo: artigo criado', [
                'site_id'    => $site->id,
                'artigo_id'  => $registro->id,
                'titulo'     => $artigo['titulo'],
                'status'     => $status,
            ]);

        } catch (\Exception $e) {
            $this->error("  Exceção: " . $e->getMessage());
            Log::error('GerarArtigoSeo: erro', ['site_id' => $site->id, 'erro' => $e->getMessage()]);
        }
    }

    private function systemPrompt(EmpresaSite $site): string
    {
        $empresa = $site->nome_empresa ?? 'nossa escola';
        $nicho   = $site->segmento ?? $site->nicho ?? 'esportes';

        return "Você é um redator especializado em SEO para o nicho de {$nicho}.
Escreva para a empresa '{$empresa}'.
Seu objetivo é criar artigos que ranqueiam no Google, com linguagem natural, informativa e envolvente.
Responda SEMPRE em português do Brasil.
Formato obrigatório da resposta:
TÍTULO: [título do artigo, com keyword principal]
RESUMO: [meta description com 150-160 caracteres, natural e persuasiva]
CONTEUDO: [artigo completo em HTML semântico usando <h2>, <h3>, <p>, <ul>, <li>, <strong>. Mínimo 800 palavras.]";
    }

    private function montarPrompt(string $tema, EmpresaSite $site): string
    {
        $empresa = $site->nome_empresa ?? 'nossa escola';
        $cidade  = $site->cidade ?? '';
        $locStr  = $cidade ? " em {$cidade}" : '';

        return "Escreva um artigo SEO completo sobre: \"{$tema}\"

Contexto: para a escola '{$empresa}'{$locStr}.

Requisitos:
- Título com a keyword principal no início
- Meta description de 150-160 caracteres
- Artigo com pelo menos 800 palavras em HTML semântico
- Use h2 e h3 para estruturar o conteúdo
- Inclua listas (ul/li) onde fizer sentido
- Mencione '{$empresa}' naturalmente 2-3 vezes
- Termine com um call-to-action para agendar uma aula
- NÃO use markdown, use apenas HTML";
    }

    private function parsearResposta(string $content, string $temaFallback): array
    {
        // Extrai TÍTULO:
        $titulo = $temaFallback;
        if (preg_match('/TÍTULO:\s*(.+)/i', $content, $m)) {
            $titulo = trim($m[1]);
        }

        // Extrai RESUMO:
        $resumo = '';
        if (preg_match('/RESUMO:\s*(.+)/i', $content, $m)) {
            $resumo = trim($m[1]);
        }

        // Extrai CONTEUDO: (tudo após a tag)
        $conteudo = '';
        if (preg_match('/CONTEUDO:\s*([\s\S]+)/i', $content, $m)) {
            $conteudo = trim($m[1]);
        } else {
            // Fallback: usa tudo que veio
            $conteudo = $content;
        }

        return compact('titulo', 'resumo', 'conteudo');
    }

    private function escolherTema(EmpresaSite $site): string
    {
        // Detecta nicho do site
        $nicho = strtolower($site->segmento ?? $site->nicho ?? '');

        $temas = collect($this->temasPorNicho)
            ->filter(fn($_, $key) => str_contains($nicho, $key))
            ->flatten();

        if ($temas->isEmpty()) {
            $temas = collect($this->temasPorNicho['geral']);
        }

        // Evita repetir temas já usados recentemente (últimos 30 dias)
        $usados = SiteArtigo::where('site_id', $site->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->pluck('titulo')
            ->map(fn($t) => strtolower($t))
            ->toArray();

        $disponiveis = $temas->filter(function ($tema) use ($usados) {
            foreach ($usados as $usado) {
                if (str_contains($usado, strtolower(substr($tema, 0, 20)))) {
                    return false;
                }
            }
            return true;
        });

        // Se todos foram usados, volta à lista completa
        if ($disponiveis->isEmpty()) {
            $disponiveis = $temas;
        }

        return $disponiveis->random();
    }
}
