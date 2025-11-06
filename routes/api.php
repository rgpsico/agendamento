<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\AlunosController;
use App\Http\Controllers\Api\AgendamentoControllerApi;
use App\Http\Controllers\Api\AcessoControllerApi;
use App\Http\Controllers\Api\AlunosControllerApi;
use App\Http\Controllers\Api\AsaasController;
use App\Http\Controllers\Api\AulasControllerApi;
use App\Http\Controllers\Api\AuthControllerApi;
use App\Http\Controllers\Api\DespesaRecorrenteApiController;
use App\Http\Controllers\Api\DiaDaSemanaControllerApi;
use App\Http\Controllers\Api\DisponibilidadeControllerApi;

use App\Http\Controllers\Api\EmpresaControllerApi;
use App\Http\Controllers\Api\ProfessoresControllerApi;
use App\Http\Controllers\Api\ServicoControllerApi;
use App\Http\Controllers\Api\UserControllerApi;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\DisponibilidadeServicoController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\ModalidadeController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TwilioController;
use App\Http\Controllers\AsaasWalletController;
use App\Http\Controllers\ProfessoresAsaasController;
use App\Http\Controllers\Api\PixQrController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\SiteCliqueWhatsappController;
use App\Http\Controllers\SiteVisualizacaoController;
use App\Http\Controllers\Api\GoogleADSController;
use App\Http\Controllers\Api\PermissionApiController;
use App\Http\Controllers\SiteVisitanteController;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\EmpresaController;

Route::post('pagamento/presencial', [PagamentoController::class, 'criarPagamentoPresencial'])->name('empresa.pagamento.prensencial');



Route::post('empresa/gerar-boleto', [BoletoController::class, 'gerarBoleto'])->name('boleto.asaas');
Route::get('empresa/userexisterasaas/{customerId}', [BoletoController::class, 'customerExistsInAsaas'])->name('asaas.user.existirs');
Route::get('empresa/baixarboletoasaas/{boletoId}', [BoletoController::class, 'downloadBoleto'])->name('asaas.user.downloadBoleto');

Route::post('enviar-boleto', [BoletoController::class, 'sendBoletoToClient'])->name('asaas.user.sendBoletoToClient');




Route::post('/events', [GoogleCalendarController::class, 'createEvent']);
Route::post('/pagarComCartao', [PagamentoController::class, 'pagarComCartao'])->name('empresa.pagamento.cartao');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::delete('/gallery/{id}', [EmpresaController::class, 'galleryDestroy'])->name('gallery.destroy');
Route::prefix('permissions')->group(function () {
    Route::get('/', [PermissionApiController::class, 'index']);
    Route::get('/{id}', [PermissionApiController::class, 'show']);
    Route::post('/store', [PermissionApiController::class, 'store']);
    Route::put('/update/{id}', [PermissionApiController::class, 'update']);
    Route::post('/delete/{id}', [PermissionApiController::class, 'destroy']);
});


Route::post('/login', [AuthControllerApi::class, 'login']);

Route::post('acesso/verificar', [AcessoControllerApi::class, 'verificar'])->name('acesso.verificar');

Route::resource('agendamento', AgendamentoControllerApi::class);

Route::get('professor/{id}/aulas', [ProfessoresControllerApi::class, 'aulas']);
Route::resource('professor', ProfessoresControllerApi::class);

Route::resource('empresas', EmpresaControllerApi::class);
Route::get('search/empresa', [EmpresaControllerApi::class, 'search'])->name('empresa.search');
Route::middleware('auth:sanctum')->post('/empresa/update', [EmpresaControllerApi::class, 'update']);
Route::get('professores/{id}/alunos', [EmpresaControllerApi::class, 'getAlunoByIdProfessor']);

Route::get('empresa/verificarstatus/{empresaId}',  [EmpresaControllerApi::class, 'verificarStatus'])->name('empresa.verificarStatus');


Route::resource('servicos', ServicoControllerApi::class);

Route::post('pagamentoApi', [PagamentoController::class, 'pagamentoStripe']);
Route::post('/admin/asaas/limpar-sandbox', [PagamentoController::class, 'deleteAllPayments']);
Route::post('/admin/asaas/getwallet', [PagamentoController::class, 'getCustomerWallet']);
Route::get('/asaas/customer/{customerId}', [PagamentoController::class, 'getCustomer']);
Route::post('/asaas/getOrCreateAsaasCustome', [PagamentoController::class, 'getOrCreateAsaasCustomer']);



Route::resource('users', UserControllerApi::class);
Route::post('transacao', [UserControllerApi::class, 'transacao']);
Route::post('googleAuth', [UserControllerApi::class, 'googleAuth']);


Route::resource('aulas', AulasControllerApi::class);

Route::resource('disponibilidade', DisponibilidadeControllerApi::class);
Route::get('disponibilidade', [DisponibilidadeControllerApi::class, 'disponibilidade']);

Route::resource('dias', DiaDaSemanaControllerApi::class);



Route::post('/boleto/web-hook', [BoletoController::class, 'handleAsaasWebhook']);
Route::post('/boleto/web-hook', [BoletoController::class, 'handleAsaasWebhook']);


Route::post('/web-hook', [WebhookController::class, 'handleAsaasWebhook']);


Route::post('/pix-qrcode', [PixQrController::class, 'generatePixQrCode']);
Route::post('/customers', [PixQrController::class, 'getOrCreateAsaasCustomer']);
Route::post('/pix-webhook', [PixQrController::class, 'handleWebhook']);
Route::post('/pay-pix', [PixQrController::class, 'payPixQrCode']);
Route::post('/pix-simulate', [PixQrController::class, 'simulatePixPayment']);
Route::get('/pix/keys', [PixQrController::class, 'listPixKeys']);
Route::post('/pix/create-key', [PixQrController::class, 'createPixKey']);
Route::post('/pix/key/delete', [PixQrController::class, 'deletePixKey']);
Route::post('/pix-status', [PixQrController::class, 'checkPixStatus']);
Route::post('/pix-payment', [PixQrController::class, 'createPixPayment']);
Route::post('/simulate', [PixQrController::class, 'simulatePixPayment']);
Route::post('/complete-flow', [PixQrController::class, 'completePixPaymentFlow']);
Route::get('/status', [PixQrController::class, 'checkPixStatus']);
// Route::post('/pix-simulate', [PixQrController::class, 'simulatePixPayment']);
Route::get('/pix/keys', [PixQrController::class, 'listPixKeys']);
Route::delete('/pix/keys/{pixKeyId}', [PixQrController::class, 'deletePixKey']);
Route::post('/pagamentos/presencial', [PagamentoController::class, 'criarPagamentoPresencial'])->name('empresa.pagamento.presencial');
Route::post('/gerar-pix', [PagamentoController::class, 'gerarPix'])->name('gerar.pix');
Route::get('/verificar-pix/{id}', [PagamentoController::class, 'verificarStatusPix'])->name('verificar.pix');


Route::post('/asaas/criarcliente', [AsaasController::class, 'createClient'])->name('asaas.createClient');
Route::post('/asaas/criarclienteautomatico', [AsaasController::class, 'criarCustomerAutomatico'])->name('asaas.createClientAuto');
Route::post('/asaas/criarChavePix', [AsaasController::class, 'criarChavePix'])->name('asaas.criarChavePix');
Route::post('/asaas/pagamentocc', [AsaasController::class, 'pagarComCartao'])->name('asaas.pagarComCartao');
Route::post('/asaas/criarsubconta', [AsaasController::class, 'createSubaccount'])->name('asaas.criarSubConta');




Route::get('/disponibilidadedia', [DisponibilidadeServicoController::class, 'getDisponibilidade']);
Route::post('/disponibilidade/update', [DisponibilidadeServicoController::class, 'updateDisponibilidade']);
Route::post('/disponibilidade/reservar', [DisponibilidadeServicoController::class, 'reservarVaga']);

Route::get('/professores/media-avaliacoes', [AvaliacaoController::class, 'mediaAvaliacoes']);

Route::post('/send-whatsapp', [TwilioController::class, 'sendWhatsApp']);
Route::post('/openai/test', [OpenAIController::class, 'testConnection']);
Route::post('/openai/consultar-horarios', [OpenAIController::class, 'consultarHorarios']);
Route::post('/openai/chat', [OpenAIController::class, 'chatComCliente']);
Route::post('/agendarHorario', [OpenAIController::class, 'agendarHorario']);
Route::post('/openai/explicar-sistema', [OpenAIController::class, 'explicarSistema']);

Route::post('/openai/chat-contexto', [OpenAIController::class, 'chatComContexto']);

Route::post('modalidade/{id}/update', [ModalidadeController::class, 'updateApi'])->name('modalidade.update');
Route::delete('modalidade/{id}/destroy', [ModalidadeController::class, 'destroy'])->name('modalidade.destroy');



Route::post('/aluno/store', [AlunosController::class, 'store']);
Route::post('/aluno/{id}/update', [AlunosControllerApi::class, 'update']);
Route::post('/aluno/{id}/update-endereco', [AlunosControllerApi::class, 'updateEndereco']);
Route::delete('/aluno/{id}/destroy/{professor_id}', [AlunosController::class, 'destroy']);
Route::post('/treino/email', [AlunosControllerApi::class, 'treinoEmail']);
Route::post('/treino/service', [AlunosControllerApi::class, 'service']);
Route::get('/treino/cobrancas', [AlunosControllerApi::class, 'getCobrancas']);



Route::prefix('empresa/pagamento')->group(function () {

    Route::get('/', [PagamentoController::class, 'index'])->name('empresa.pagamento.index');
    Route::get('/create', [PagamentoController::class, 'create'])->name('empresa.pagamento.create');
    Route::post('/store', [PagamentoController::class, 'store'])->name('empresa.pagamento.store');
    Route::get('/{id}/edit', [PagamentoController::class, 'edit'])->name('empresa.pagamento.edit');
    Route::put('/{id}', [PagamentoController::class, 'update'])->name('empresa.pagamento.update');
    Route::post('/asaas', [PagamentoController::class, 'pagamentoAsaas'])->name('empresa.pagamento.asaas');
});


use App\Http\Controllers\Api\SiteDepoimentoControllerApi;
use App\Http\Controllers\Api\SiteServicoController;
use App\Http\Controllers\Api\SiteServicoControllerApi;
use App\Http\Controllers\BotController;
use App\Http\Controllers\DespesaController;

Route::middleware('auth:sanctum')->prefix('site')->group(function () {
    Route::get('depoimentos', [SiteDepoimentoControllerApi::class, 'index']);
    Route::post('depoimentos', [SiteDepoimentoControllerApi::class, 'store']);
    Route::get('depoimentos/{depoimento}', [SiteDepoimentoControllerApi::class, 'show']);
    Route::put('depoimentos/{depoimento}', [SiteDepoimentoControllerApi::class, 'update']);
    Route::delete('depoimentos/{depoimento}', [SiteDepoimentoControllerApi::class, 'destroy']);
});

Route::get('/viacep/{cep}', [App\Http\Controllers\ViaCepController::class, 'getCep']);


Route::middleware('auth')->post('/subcontas', [ProfessoresAsaasController::class, 'createSubaccount'])->name('criar-subconta-professor');


use Illuminate\Support\Facades\Log;

use App\Models\AsaasWebhookLog;

Route::post('/webhook/sms', function (Request $request) {
    AsaasWebhookLog::create([
        'event'      => 'twilio_sms_received',
        'payload'    => $request->all(),
        'status'     => 'recebido',
        'message'    => $request->input('Body'), // corpo do SMS recebido
        'payment_id' => null, // ou alguma lógica, se quiser usar
        'empresa_id' => null, // ou o ID da empresa se tiver autenticação
    ]);

    return response('<Response><Message>Recebido e salvo!</Message></Response>', 200)
        ->header('Content-Type', 'text/xml');
});


Route::post('/test-twilio', function () {
    try {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $client = new Client($sid, $token);
        return "Twilio SDK está funcionando!";
    } catch (\Exception $e) {
        return "Erro: " . $e->getMessage();
    }
});


use App\Http\Controllers\WhatsAppController;

Route::post('/whatsapp/send', [WhatsAppController::class, 'send']);
Route::post('/whatsapp/receive', [WhatsAppController::class, 'receive']);



Route::post('/contato/enviar', [ContatoController::class, 'enviar'])->name('contato.enviar');



Route::prefix('google-ads')->group(function () {
    Route::get('auth-url', [GoogleADSController::class, 'getAuthUrl']);
    Route::get('callback', [GoogleADSController::class, 'handleCallback']);
    Route::post('create-campaign', [GoogleADSController::class, 'createCampaign']);
    Route::post('create-cliente', [GoogleADSController::class, 'createCustomerClient']);
});


Route::post('email/teste', [EmailController::class, 'teste']);



Route::post('/clique-whatsapp', [SiteCliqueWhatsappController::class, 'store']);



Route::post('/visualizacao', [SiteVisualizacaoController::class, 'store']);


Route::post('/visitante', [SiteVisitanteController::class, 'store']);



use App\Http\Controllers\TwilioWebhookController;

Route::post('/webhook/twilio', [TwilioWebhookController::class, 'inbound']);
Route::post('/webhook/openai', [TwilioWebhookController::class, 'testOpenAI']);
Route::post('/webhook/deepseek', [TwilioWebhookController::class, 'askDeepSeek']);


Route::post('bot/{botId}/interact', [BotController::class, 'interact'])->name('bot.interact');
Route::post('/bots/{bot}/chat', [BotController::class, 'chat']);


Route::get('/conversations/{id}', [ConversationController::class, 'showapi']);

Route::post('/fill-site-fields', [BotController::class, 'fillSiteFields'])->name('fill.site.fields');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('siteservicos', SiteServicoControllerApi::class);
    Route::post('siteservicos/store', [SiteServicoControllerApi::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/despesas', [DespesaController::class, 'apiIndex']);
    Route::get('/despesas/resumo', [DespesaController::class, 'apiResumo']);
});


use App\Http\Controllers\DeepSeekController;

Route::post('/bots/{bot_id}/message', [DeepSeekController::class, 'sendMessage']);


use App\Http\Controllers\ChatController;
use App\Http\Controllers\EventController;

Route::patch('/conversations/{id}/human-control', [ChatController::class, 'toggleHumanControl']);

Route::post('/generate-image', [ChatController::class, 'generateImage']);



Route::post('/track-event', [EventController::class, 'track']);


use App\Http\Controllers\Api\ReceitaApiController;





use App\Http\Controllers\Api\DespesasApiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/despesas', [DespesasApiController::class, 'index']);
    Route::get('/despesas/{id}', [DespesasApiController::class, 'show']);
    Route::get('/despesas/resumo', [DespesasApiController::class, 'resumo']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/receitas', [ReceitaApiController::class, 'index']);
    Route::get('/receitas/{id}', [ReceitaApiController::class, 'show']);
    Route::get('/receita/resumo', [ReceitaApiController::class, 'resumo']);
});


Route::get('/despesas-recorrentes', [DespesaRecorrenteApiController::class, 'index']);
Route::get('/despesas-recorrentes/{id}', [DespesaRecorrenteApiController::class, 'show']);
Route::get('/despesas-recorrente/resumo', [DespesaRecorrenteApiController::class, 'resumo']);
Route::middleware('auth:sanctum')->group(function () {});



use App\Http\Controllers\Api\ReceitaRecorrenteApiController;
use App\Http\Controllers\FinanceiroDashboardController;

Route::get('/receitas-recorrente', [ReceitaRecorrenteApiController::class, 'index']);
Route::get('/receitas-recorrentes/{id}', [ReceitaRecorrenteApiController::class, 'show']);
Route::get('/receitas-recorren/resumo', [ReceitaRecorrenteApiController::class, 'resumo']);
Route::middleware('auth:sanctum')->group(function () {});



use App\Http\Controllers\PlanoAlunoController;

Route::apiResource('planos-alunos', PlanoAlunoController::class);




Route::post('/deepseek/image', [DeepSeekController::class, 'analyzeImage']);
