<?php

namespace App\Console\Commands;

use App\Models\EmpresaSite;
use App\Models\SiteArtigo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            // SEO local — Arpoador
            'Aula de surf no Arpoador: tudo o que você precisa saber antes de começar',
            'Por que o Arpoador é o melhor lugar para aprender a surfar no Rio',
            'Aula de surf no Arpoador para iniciantes: como é e quanto custa',
            'Arpoador surf: guia completo para quem quer pegar a primeira onda',
            'Como escolher uma escola de surf no Arpoador',

            // SEO local — Ipanema
            'Aula de surf em Ipanema: o que esperar da sua primeira aula',
            'Surf em Ipanema: melhores pontos e escolas para iniciantes',
            'Por que aprender a surfar em Ipanema é uma experiência única',
            'Aula de surf em Ipanema para adultos: nunca é tarde para começar',
            'Ipanema e o surf: história, cultura e onde aprender',

            // Bodyboard — Arpoador
            'Aula de bodyboard no Arpoador: guia completo para iniciantes',
            'Por que fazer aula de bodyboard no Arpoador: veja os motivos',
            'Bodyboard no Arpoador: como são as ondas e o que esperar das aulas',
            'Aula de bodyboard no Arpoador para adultos e crianças',
            'Diferença entre surf e bodyboard: qual escolher no Arpoador',

            // Bodyboard — Praia do Diabo
            'Aula de bodyboard na Praia do Diabo: ondas perfeitas para iniciantes',
            'Por que a Praia do Diabo é ideal para aprender bodyboard',
            'Praia do Diabo: tudo sobre o point de bodyboard mais famoso do Rio',
            'Bodyboard na Praia do Diabo: como se preparar para sua primeira aula',

            // Bodyboard infantil
            'Aula de bodyboard infantil: a partir de que idade a criança pode começar',
            'Bodyboard para crianças: benefícios e cuidados nas aulas',
            'Como introduzir seu filho ao bodyboard de forma segura e divertida',
            'Aula de bodyboard infantil no Rio: o que os pais precisam saber',
            'Esportes aquáticos para crianças: por que o bodyboard é uma ótima escolha',

            // Benefícios e saúde
            'Benefícios físicos e mentais de praticar surf regularmente',
            'Como o surf melhora a saúde mental e o equilíbrio emocional',
            'Surf como terapia: como as ondas transformam vidas',
            'Por que surfar faz bem para a mente, o corpo e a alma',
            'Surf e saúde: o que acontece com seu corpo quando você pega uma onda',

            // Esportes de praia — vida e estilo
            'Esportes de praia em Ipanema: opções para todos os perfis',
            'Esportes de praia para saúde mental e equilíbrio emocional',
            'Como os esportes de praia transformam sua qualidade de vida',
            'Esportes de praia para a vida: por que criar esse hábito ainda jovem',
            'Viver melhor com esportes de praia: guia para quem mora no Rio',
            'Esportes aquáticos no Rio de Janeiro: onde praticar e como começar',
            'Como os esportes de praia ajudam no controle da ansiedade',
            'Esportes de praia para toda a família: opções no Rio de Janeiro',

            // Técnica e evolução
            'Como evoluir do surf iniciante para intermediário mais rápido',
            'Etiqueta no line-up: regras não escritas do surf que todo iniciante deve saber',
            'Como ler as ondas: guia prático para surfistas iniciantes',
            'Equipamentos essenciais para quem está começando no surf ou bodyboard',
            'Segurança no mar: o que todo praticante de esportes aquáticos deve saber',
            'Como escolher a prancha certa para o seu nível e peso',
            'Preparo físico para surf e bodyboard: exercícios fora da água',
            'Surf em família: como envolver todos no esporte',
        ],

        'pilates' => [
            'Pilates para iniciantes: por onde começar',
            'Benefícios do pilates para a postura no dia a dia',
            'Pilates versus academia: qual escolher para o seu objetivo',
            'Como o pilates ajuda na recuperação de lesões',
            'Pilates na gravidez: benefícios e cuidados',
            'Frequência ideal de treinos de pilates por semana',
            'Diferenças entre pilates solo e no aparelho',
            'Pilates para atletas: como complementa o desempenho esportivo',
            'Por que o pilates melhora a saúde mental além do físico',
            'Pilates para idosos: benefícios e cuidados na terceira idade',
        ],

        'natacao' => [
            'Como aprender a nadar do zero: guia completo para adultos',
            'Benefícios da natação para todas as idades',
            'Estilos de natação e quando usar cada um',
            'Como melhorar o fôlego na natação',
            'Natação para crianças: quando começar e quais os benefícios',
            'Natação e saúde mental: por que nadar faz bem para a mente',
        ],

        'esportes' => [
            'Esportes de praia em Ipanema: guia completo para iniciantes',
            'Esportes de praia para saúde mental e equilíbrio emocional',
            'Como os esportes de praia transformam sua qualidade de vida',
            'Esportes de praia para a vida: por que criar esse hábito',
            'Como criar o hábito de praticar esportes aquáticos no Rio',
            'Benefícios dos esportes aquáticos para corpo e mente',
            'Como escolher o esporte de praia certo para o seu perfil',
            'Esportes de praia para toda a família no Rio de Janeiro',
        ],

        'geral' => [
            'Como escolher uma escola de esportes aquáticos de qualidade',
            'Benefícios de praticar esportes desde a infância',
            'Dicas para manter a consistência nos treinos esportivos',
            'Como a atividade física melhora a saúde mental e emocional',
            'Por que investir em aulas com instrutores profissionais',
            'Esportes aquáticos no Rio: onde praticar e como começar',
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

            // Busca imagem no Unsplash e salva como capa
            $imagemPath = $this->buscarImagemUnsplash($artigo['titulo'], $tema);
            if ($imagemPath) {
                $registro->update(['imagem_capa' => $imagemPath]);
                $this->line("  Imagem: {$imagemPath}");
            }

            $this->info("  Artigo #{$registro->id} salvo como '{$status}': {$artigo['titulo']}");

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
        $cidade  = $site->cidade ?? 'Rio de Janeiro';

        return "Escreva um artigo SEO completo sobre: \"{$tema}\"

Contexto: para a escola '{$empresa}' localizada em {$cidade}.

Requisitos SEO obrigatórios:
- TÍTULO: use a keyword \"{$tema}\" no início do título
- RESUMO: meta description de 150-160 caracteres com a keyword principal
- A keyword \"{$tema}\" deve aparecer no primeiro parágrafo e em pelo menos 2 subtítulos
- Artigo com pelo menos 900 palavras em HTML semântico
- Use <h2> e <h3> para estruturar o conteúdo
- Inclua listas <ul>/<li> onde fizer sentido
- Mencione '{$empresa}' naturalmente 2-3 vezes ao longo do texto
- Inclua dados práticos: quanto custa, duração das aulas, o que levar, faixa etária
- Termine com um call-to-action convidando o leitor a agendar uma aula em '{$empresa}'
- NÃO use markdown, use apenas HTML
- Escreva em português do Brasil, tom informativo e acolhedor";
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
        $nicho = strtolower($site->segmento ?? $site->nicho ?? '');

        $temas = collect($this->temasPorNicho)
            ->filter(fn($_, $key) => str_contains($nicho, $key))
            ->flatten();

        if ($temas->isEmpty()) {
            $temas = collect($this->temasPorNicho['geral']);
        }

        // Busca TODOS os títulos já gerados (sem limite de data)
        $titulosExistentes = SiteArtigo::where('site_id', $site->id)
            ->pluck('titulo')
            ->map(fn($t) => strtolower($t))
            ->toArray();

        $disponiveis = $temas->filter(function ($tema) use ($titulosExistentes) {
            $palavrasChave = $this->extrairPalavrasChave($tema);

            foreach ($titulosExistentes as $titulo) {
                $matches = 0;
                foreach ($palavrasChave as $palavra) {
                    if (str_contains($titulo, $palavra)) {
                        $matches++;
                    }
                }
                // Considera repetido se mais da metade das palavras-chave coincidir
                if ($matches >= ceil(count($palavrasChave) / 2)) {
                    return false;
                }
            }
            return true;
        });

        // Se todos os temas já foram usados, reinicia o ciclo
        if ($disponiveis->isEmpty()) {
            $this->warn('  Todos os temas já foram usados — reiniciando ciclo.');
            $disponiveis = $temas;
        }

        return $disponiveis->random();
    }

    private function buscarImagemUnsplash(string $titulo, string $tema): ?string
    {
        $accessKey = config('services.unsplash.access_key');
        if (!$accessKey) {
            $this->warn('  UNSPLASH_ACCESS_KEY nao configurada — sem imagem.');
            return null;
        }

        // Usa palavras-chave do tema para busca mais precisa
        $query = implode(' ', array_slice($this->extrairPalavrasChave($tema), 0, 3));

        try {
            $res = Http::withHeaders(['Accept-Version' => 'v1'])
                ->get('https://api.unsplash.com/search/photos', [
                    'client_id'   => $accessKey,
                    'query'       => $query,
                    'per_page'    => 10,
                    'orientation' => 'landscape',
                ]);

            if (!$res->successful()) {
                $this->warn('  Unsplash: erro na busca — ' . $res->status());
                return null;
            }

            $fotos = $res->json('results', []);
            if (empty($fotos)) {
                $this->warn("  Unsplash: nenhuma foto para '{$query}'.");
                return null;
            }

            // Pega uma foto aleatória entre os resultados para variar
            $foto    = $fotos[array_rand($fotos)];
            $url     = $foto['urls']['regular'] ?? null;
            if (!$url) return null;

            // Baixa e salva no storage
            $imagem   = Http::get($url)->body();
            $filename = 'artigos/' . uniqid('unsplash_') . '.jpg';
            Storage::disk('public')->put($filename, $imagem);

            // Notifica o Unsplash do download (obrigatório pelos termos de uso)
            Http::get($foto['links']['download_location'] ?? '', ['client_id' => $accessKey]);

            return $filename;

        } catch (\Throwable $e) {
            $this->warn('  Unsplash: excecao — ' . $e->getMessage());
            return null;
        }
    }

    private function extrairPalavrasChave(string $texto): array
    {
        $stopwords = ['como', 'para', 'que', 'uma', 'uns', 'dos', 'das', 'com', 'por', 'seu', 'sua', 'são', 'nao', 'mais', 'mas', 'isso', 'este', 'esta', 'cada', 'todo', 'toda', 'versus'];

        $palavras = explode(' ', strtolower($texto));

        return array_values(array_filter(
            $palavras,
            fn($p) => strlen($p) > 3 && !in_array($p, $stopwords)
        ));
    }
}
