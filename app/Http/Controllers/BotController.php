<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotLog;
use App\Models\BotService;
use App\Models\Conversation;
use App\Models\Servicos;
use App\Models\TokenUsage;
use App\Services\DeepSeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DeepSeek\Tokenizer\Tokenizer;
class BotController extends Controller
{

    protected $deepSeekService;

    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }
    
   public function dashboard()
    {
       
        // Bots ativos
        $botsAtivos = Bot::where('status', true)->count();

        // Tokens consumidos
        $totalTokens = TokenUsage::sum('tokens_usados');

        // Custo estimado (exemplo: R$ 0,002 por 1k tokens)
        $custoEstimado = ($totalTokens / 1000) * 0.002;

            // Conversas de hoje
        $conversasHoje = Conversation::whereDate('created_at', today())->count();

        // Serviços cadastrados
        $services = BotService::all();

        // Consumo nos últimos 7 dias
        $labels = [];
        $dataTokens = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format('d/m');
            $dataTokens[] = TokenUsage::whereDate('created_at', $day)->sum('tokens_usados');
        }

        return view('admin.bot.dashboard', compact(
            'botsAtivos',
            'totalTokens',
            'custoEstimado',
            'conversasHoje',
            'services',
            'labels',
            'dataTokens'
        ));
    }


    public function index() {
        $bots = Bot::all();
        return view('admin.bot.index', compact('bots'));
    }

    public function create() {
        $services = Servicos::all(); // exemplo: professores, horários
        return view('admin.bot.create', compact('services'));
    }

    public function store(Request $request) {
        $bot = Bot::create($request->all());
        return redirect()->route('admin.bot.index')->with('success', 'Bot criado com sucesso!');
    }

        public function tokens() {
            $usage = TokenUsage::with('bot')->get();         
            return view('admin.bot.tokens', compact('usage'));
        }

        public function logs() {
            $logs = BotLog::with('bot')->get();

            
            return view('admin.bot.log', compact('logs'));
        }


          // Mostrar formulário de edição
    public function edit($id)
    {
        $bot = Bot::with('services')->findOrFail($id);
        $services = Servicos::all();
        return view('admin.bot.edit', compact('bot', 'services'));
    }

    // Mostrar formulário de edição
        public function show(Bot $bot)
        {
            return view('admin.bot.show', compact('bot'));
        }


    // Atualizar bot
    public function update(Request $request, $id)
    {
        $bot = Bot::findOrFail($id);
        $bot->update($request->only(['nome','segmento','tom','token_deepseek','status']));

        // Atualizar serviços
        if ($request->has('services')) {
            $bot->services()->sync($request->services); // many-to-many se tiver pivot, ou ajustar se for hasMany
        }

        return redirect()->route('admin.bot.index')->with('success', 'Bot atualizado com sucesso!');
    }

    // Excluir bot
    public function destroy(Request $request)
    {
        $bot = Bot::findOrFail($request->id);
        $bot->delete();
        return redirect()->route('admin.bot.index')->with('success', 'Bot excluído com sucesso!');
    }


    private function getDeepSeekResponse(string $question): string
    {
        // Força o modelo a responder em português
        $prompt = "Responda em português: " . $question;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('DEEP_SEEK_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model' => 'deepseek-chat',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000 // Aumentado para permitir respostas mais longas e complexas
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'] ?? 'Sem resposta';
        } else {
            return 'Erro: ' . $response->body();
        }
    }


    public function interact(Request $request, $botId)
    {
        $bot = Bot::findOrFail($botId);
        $question = $request->input('question');
        $empresa_id = $request->empresa_id;
    
        if (!$bot->status) {
            return response()->json(['error' => 'Bot está inativo'], 403);
        }

        $response = $this->deepSeekService->getDeepSeekResponse($bot, $question, $empresa_id);

        return response()->json(['response' => $response]);
    }

   public function chat(Request $request, Bot $bot)
{
    $request->validate([
        'message' => 'required|string',
        'empresa_id' => 'required|integer'
    ]);

    $empresa_id = $request->input('empresa_id');
    $userMessage = $request->input('message');

    try {
        $reply = $this->deepSeekService->getDeepSeekResponse($bot, $userMessage, $empresa_id);

        return response()->json([
            'reply' => $reply
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'reply' => '⚠️ Erro ao processar resposta do bot: ' . $e->getMessage()
        ], 500);
    }
}


// private function getDeepSeekResponse(string $question): string
//     {
//         // Força o modelo a responder em português
//         $prompt = "Responda em português: " . $question;

//         $response = Http::withHeaders([
//             'Authorization' => 'Bearer ' . env('DEEP_SEEK_API_KEY'),
//             'Content-Type' => 'application/json',
//         ])->post('https://api.deepseek.com/v1/chat/completions', [
//             'model' => 'deepseek-chat',
//             'messages' => [
//                 ['role' => 'user', 'content' => $prompt]
//             ],
//             'temperature' => 0.7,
//             'max_tokens' => 2000 // Aumentado para permitir respostas mais longas e complexas
//         ]);

//         if ($response->successful()) {
//             return $response->json()['choices'][0]['message']['content'] ?? 'Sem resposta';
//         } else {
//             return 'Erro: ' . $response->body();
//         }
//     }

    /**
     * Endpoint API para preencher automaticamente os campos do site usando IA DeepSeek.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fillSiteFields(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:500', // Validação básica para o prompt
        ]);

        $userPrompt = $request->input('prompt');

        // Prompt detalhado para instruir a IA a gerar um JSON com todos os campos relevantes
        $aiPrompt = <<<EOT
Gere um JSON estruturado com valores para preencher automaticamente os campos de configuração de um site com base no prompt do usuário: '$userPrompt'.

O JSON deve seguir exatamente esta estrutura, com valores relevantes e criativos ao tema:

{
  "titulo": "Título principal do site",
  "whatsapp": "Número de WhatsApp no formato +55 XX XXXXX-XXXX",
  "descricao": "Descrição geral do site (100-200 caracteres)",
  "atendimento_com_whatsapp": 1, // 1 para ativado, 0 para desativado
  "atendimento_com_ia": 1, // 1 para ativado, 0 para desativado
  "cores": {
    "primaria": "#hexadecimal da cor primária (ex: #0ea5e9)",
    "secundaria": "#hexadecimal da cor secundária (ex: #38b2ac)"
  },
  "sobre_titulo": "Título da seção Sobre Nós",
  "sobre_descricao": "Descrição detalhada da seção Sobre Nós (200-400 caracteres)",
  "sobre_itens": [
    {
      "icone": "Classe do ícone Font Awesome (ex: fas fa-heart)",
      "titulo": "Título do item",
      "descricao": "Descrição do item (50-100 caracteres)"
    },
    // Gere pelo menos 1 itens
    // ...
  ],

}

Importante:
- Todos os textos devem estar em português.
- Gere valores fictícios mas realistas e coerentes com o tema do prompt.
- Para arrays, gere o mínimo especificado, mas pode gerar mais se fizer sentido.
- Não inclua campos para arquivos (como logo, capa, imagens), pois a IA não gera arquivos.
- Responda APENAS com o JSON válido, sem qualquer texto adicional, markdown, explicações ou aspas extras.
EOT;

        // Consome a API DeepSeek
        $aiResponse = $this->getDeepSeekResponse($aiPrompt);

        // Tenta decodificar o JSON da resposta da IA
        $data = json_decode($aiResponse, true);
       
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Se não for JSON válido, retorna erro
            return response()->json([
                'error' => 'Falha ao gerar dados da IA',
                'details' => $aiResponse,
            ], 500);
        }

        // Retorna o JSON gerado pela IA
        return response()->json($data);
    }




}

