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
            // Surf — Arpoador
            'Aula de surf no Arpoador para iniciantes: como é e quanto custa',
            'Por que o Arpoador é o melhor lugar para aprender a surfar no Rio',
            'Arpoador surf: guia completo para quem quer pegar a primeira onda',
            'Como escolher uma escola de surf no Arpoador',
            'Aula de surf no Arpoador para adultos: nunca é tarde para começar',
            'Surf no Arpoador: os melhores horários e condições para iniciantes',
            'O que levar na primeira aula de surf no Arpoador',
            'Aula de surf no Arpoador em família: como aproveitar com crianças',

            // Surf — Ipanema e Zona Sul
            'Aula de surf em Ipanema: o que esperar da sua primeira aula',
            'Surf em Ipanema: melhores pontos e dicas para iniciantes',
            'Por que aprender a surfar em Ipanema é uma experiência única',
            'As melhores praias da Zona Sul do Rio para aprender a surfar',
            'Surf na Zona Sul do Rio: guia completo de praias e escolas',

            // Bodyboard — Arpoador e Praia do Diabo
            'Aula de bodyboard no Arpoador: guia completo para iniciantes',
            'Por que fazer aula de bodyboard no Arpoador',
            'Bodyboard no Arpoador: como são as ondas e o que esperar',
            'Aula de bodyboard na Praia do Diabo: ondas perfeitas para iniciantes',
            'Por que a Praia do Diabo é ideal para aprender bodyboard',
            'Praia do Diabo: o point de bodyboard mais famoso do Rio',
            'Diferença entre surf e bodyboard: qual escolher para começar',

            // Bodyboard infantil
            'Aula de bodyboard infantil no Arpoador: a partir de que idade começar',
            'Bodyboard para crianças no Rio: benefícios e cuidados nas aulas',
            'Como introduzir seu filho ao bodyboard de forma segura e divertida',
            'Esportes aquáticos para crianças na Zona Sul do Rio de Janeiro',
            'Bodyboard infantil: por que é o esporte ideal para crianças no Rio',

            // Passeios e experiências no Rio
            'Passeios de stand up paddle no Rio de Janeiro: onde e como fazer',
            'Passeio de caiaque em Ipanema: uma experiência única no Rio',
            'Os melhores passeios aquáticos para fazer no Rio de Janeiro',
            'Passeio de barco pela orla carioca: o que esperar e como reservar',
            'Mergulho no Rio de Janeiro: onde praticar e o que ver',
            'Pesca esportiva no Rio: guia para iniciantes na Zona Sul',
            'Snorkeling no Rio de Janeiro: praias e pontos recomendados',

            // Esportes de praia — Rio de Janeiro
            'Esportes de praia em Ipanema: opções para todos os perfis',
            'Os melhores esportes para praticar na Praia do Arpoador',
            'Esportes aquáticos no Rio de Janeiro: guia completo para iniciantes',
            'Beach tennis no Rio: onde aprender e como são as aulas',
            'Vôlei de praia em Ipanema: onde jogar e como encontrar parceiros',
            'Frescobol em Ipanema: a tradição carioca que todo turista deve conhecer',
            'Esportes de praia para a saúde mental: por que o mar transforma vidas',
            'Como os esportes de praia ajudam no controle da ansiedade e do estresse',
            'Esportes de praia para toda a família no Rio de Janeiro',
            'Atividades na praia para fazer no Rio de Janeiro além de tomar sol',

            // Técnica e evolução
            'Como evoluir no surf mais rápido: dicas de instrutores do Arpoador',
            'Como ler as ondas do Arpoador: guia para iniciantes',
            'Equipamentos essenciais para começar no surf ou bodyboard no Rio',
            'Segurança no mar: o que todo iniciante precisa saber antes de surfar',
            'Como escolher a prancha certa para o seu nível e peso',
            'Preparo físico para surf e bodyboard: exercícios fora da água',
            'Etiqueta no line-up do Arpoador: regras que todo surfista deve saber',

            // Surf, saúde e estilo de vida carioca
            'Surf como terapia: como as ondas do Arpoador transformam vidas',
            'Por que surfar faz bem para a mente, o corpo e a alma',
            'Surf e saúde mental: o que acontece no seu cérebro quando você pega uma onda',
            'O estilo de vida carioca e a cultura do surf na Zona Sul',
            'Por que o Rio de Janeiro é um dos melhores lugares do mundo para surfar',
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
            'Esportes de praia em Ipanema: guia completo',
            'Os melhores esportes aquáticos para praticar no Rio de Janeiro',
            'Como escolher o esporte de praia certo para o seu perfil',
            'Esportes de praia para toda a família no Rio de Janeiro',
            'Passeios e atividades aquáticas imperdíveis no Rio de Janeiro',
            'Atividades ao ar livre na Zona Sul do Rio: um guia completo',
        ],

        'geral' => [
            'Como escolher uma escola de esportes aquáticos de qualidade no Rio',
            'O que fazer na Praia do Arpoador além de tomar sol',
            'Ipanema e Arpoador: o guia definitivo de atividades esportivas',
            'Turismo esportivo no Rio de Janeiro: experiências imperdíveis',
            'Zona Sul do Rio de Janeiro: o paraíso dos esportes de praia',
        ],

        // Temas em inglês — SEO para turistas internacionais
        'surf_en' => [
            // Surf lessons — Rio de Janeiro
            'Surf lessons in Rio de Janeiro: the complete guide for beginners',
            'Best surf lessons in Rio de Janeiro: what to expect and how to book',
            'Surf lessons Rio de Janeiro: tips from local instructors',
            'Learning to surf in Rio de Janeiro: everything you need to know',
            'Rio de Janeiro surf lessons for adults: it is never too late to start',
            'Surf lessons in Rio de Janeiro for kids: safe, fun and unforgettable',
            'How much do surf lessons cost in Rio de Janeiro',
            'Best beaches in Rio de Janeiro to learn how to surf',
            'Surf school in Rio de Janeiro: how to choose the right one',
            'Surf lessons in Rio de Janeiro: a complete tourist guide',

            // Surf lessons — Arpoador
            'Surf lessons at Praia do Arpoador: the ultimate beginner guide',
            'Arpoador surf lessons: what makes this beach perfect for beginners',
            'Best surf school at Arpoador beach in Rio de Janeiro',
            'Surf lessons Arpoador Rio de Janeiro: prices, schedule and tips',
            'Learning to surf at Arpoador: a step-by-step guide',
            'Arpoador beach surf experience: everything tourists should know',
            'Why Arpoador is the best place to take surf lessons in Rio',
            'Surf lessons near Ipanema and Arpoador: top schools and instructors',

            // Bodyboard lessons — English
            'Bodyboard lessons in Rio de Janeiro: the beginner guide',
            'Bodyboard lessons at Arpoador beach: what to expect',
            'Bodyboard vs surfing: which one should you try first in Rio',
            'Best bodyboard spots in Rio de Janeiro for beginners',
            'Bodyboard lessons for kids in Rio de Janeiro: age, safety and fun',

            // Experiences and activities
            'Top water sports to try in Rio de Janeiro',
            'Best beach activities in Ipanema and Arpoador for tourists',
            'Things to do at Arpoador beach in Rio de Janeiro',
            'Stand up paddle in Rio de Janeiro: where to rent and learn',
            'Water sports in Rio de Janeiro: the complete tourist guide',
            'Rio de Janeiro beach guide: sports, activities and hidden gems',
            'Why Rio de Janeiro is one of the best surf destinations in the world',
            'Surf culture in Rio de Janeiro: history, spots and local vibe',
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
                    ['role' => 'system', 'content' => $this->systemPrompt($site, $tema)],
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

    private function isEnglish(string $tema): bool
    {
        $englishWords = ['surf', 'lessons', 'learn', 'beach', 'guide', 'best', 'how', 'what', 'why', 'tips', 'school', 'beginners', 'complete', 'ultimate', 'water', 'sports', 'things', 'try'];
        $lower = strtolower($tema);
        $matches = 0;
        foreach ($englishWords as $word) {
            if (str_contains($lower, $word)) {
                $matches++;
            }
        }
        return $matches >= 2;
    }

    private function systemPrompt(EmpresaSite $site, string $tema = ''): string
    {
        $empresa = $site->nome_empresa ?? 'nossa escola';
        $nicho   = $site->segmento ?? $site->nicho ?? 'esportes';

        if ($this->isEnglish($tema)) {
            return "You are an SEO content writer specialized in surf, bodyboard and beach sports in Rio de Janeiro, Brazil.
Write for the school '{$empresa}'.
Your goal is to create articles that rank on Google for English-speaking tourists visiting Rio de Janeiro.
Write ALWAYS in English. Use a friendly, informative and enthusiastic tone.
Mandatory response format:
TÍTULO: [article title with main keyword]
RESUMO: [meta description with 150-160 characters, natural and persuasive]
CONTEUDO: [complete article in semantic HTML using <h2>, <h3>, <p>, <ul>, <li>, <strong>. Minimum 900 words.]";
        }

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

        if ($this->isEnglish($tema)) {
            return "Write a complete SEO article about: \"{$tema}\"

Context: for the school '{$empresa}' located in {$cidade}, Brazil.

Mandatory SEO requirements:
- TÍTULO: use the keyword \"{$tema}\" at the beginning of the title
- RESUMO: meta description with 150-160 characters including the main keyword
- The keyword \"{$tema}\" must appear in the first paragraph and at least 2 subheadings
- Article with at least 900 words in semantic HTML
- Use <h2> and <h3> to structure the content
- Include <ul>/<li> lists where appropriate
- Mention '{$empresa}' naturally 2-3 times throughout the text
- Include practical info: lesson duration, what to bring, age range, location
- When mentioning prices, use the range of R\$ 280 to R\$ 600 per lesson package
- End with a call-to-action inviting the reader to book a lesson at '{$empresa}'
- Do NOT use markdown, use HTML only
- Write in English, friendly and informative tone";
        }

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
- Inclua dados práticos: duração das aulas, o que levar, faixa etária
- Ao mencionar valores, use a faixa de R$ 280 a R$ 600 por pacote de aulas — NUNCA cite valores abaixo disso
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
            ->filter(fn($_, $key) => $key !== 'surf_en' && str_contains($nicho, $key))
            ->flatten();

        if ($temas->isEmpty()) {
            $temas = collect($this->temasPorNicho['geral']);
        }

        // Sites de surf também recebem temas em inglês (50% de chance)
        if (str_contains($nicho, 'surf') && isset($this->temasPorNicho['surf_en'])) {
            $temas = $temas->merge($this->temasPorNicho['surf_en']);
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
