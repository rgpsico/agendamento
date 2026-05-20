<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SocialLiteController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\Api\PixQrController;
use Illuminate\Support\Facades\Route;
use Spatie\GoogleCalendar\Event;
use Inertia\Inertia;

use App\Http\Controllers\GoogleController;
use App\Http\Controllers\SiteContatoController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SiteServicoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Api\AsaasController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\BotServiceController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\ProfessoresAsaasController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\SiteDepoimentoController;
use App\Http\Controllers\ViaCepController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\SiteCliqueWhatsappController;
use App\Http\Controllers\SiteArtigoController;
use App\Http\Controllers\ProfessoresController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\DespesasRecorrenteController;
//   Route::get('/', [UserManagementController::class, 'index'])->name('register.aluno');

use App\Http\Controllers\FinanceiroCategoriaController;
use App\Http\Controllers\ReceitaRecorrenteController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadInteresseController;
use App\Http\Controllers\CRM\CampanhaController as CRMCampanhaController;
use App\Http\Controllers\CRM\DashboardCRMController;
use App\Http\Controllers\CRM\FormularioCampanhaController;
use App\Http\Controllers\CRM\LeadController as CRMLeadController;
use App\Http\Controllers\CRM\PipelineController;
use App\Http\Controllers\CRM\RelatorioController as CRMRelatorioController;
use App\Http\Controllers\CRM\TarefaController as CRMTarefaController;
use App\Http\Controllers\CRM\EmailTemplateController as CRMEmailTemplateController;
use App\Http\Controllers\CRM\EmailEnvioController as CRMEmailEnvioController;
use App\Http\Controllers\CRM\ModalCapturaController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\Api\ModalLeadController;



// Rota raiz: se vier de um domínio personalizado, exibe o site da empresa.
// Caso contrário, exibe a home normal da plataforma.
// ─────────────────────────────────────────────
// Domínios personalizados das escolas (SaaS multi-tenant)
// Cada domínio aponta para o site da empresa correspondente.
// Basta adicionar aqui + cadastrar dominio_personalizado no banco.
// ─────────────────────────────────────────────
Route::get('/', function (\Illuminate\Http\Request $request) {
    $host = $request->getHost();

    // Domínio do Surf SaaS → landing page de captação
    $dominiosSurf = ['surfgestao.com.br', 'www.surfgestao.com.br'];
    if (in_array($host, $dominiosSurf)) {
        return view('site/surf_landing');
    }

    // Tenant via domínio personalizado de escola cadastrada
    $site = app()->has('currentSite') ? app('currentSite') : null;
    if ($site) {
        return app(\App\Http\Controllers\SiteController::class)
            ->mostrarDominio($request);
    }

    // Home padrão da plataforma (pilates)
    return app(\App\Http\Controllers\HomeController::class)
        ->home($request);
})->name('home');

Route::get('/create', [UserManagementController::class, 'create'])->name('register.professor');

Route::post('/pagamentos/presencial', [PagamentoController::class, 'criarPagamentoPresencial'])->name('empresa.pagamento.presencial');

Route::prefix('admin/usuarios')->name('admin.usuarios.')->group(function () {
    Route::get('/', [UserManagementController::class, 'index'])->name('index');
    Route::get('/create', [UserManagementController::class, 'create'])->name('create');
    Route::post('/', [UserManagementController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');

    // Rotas para permissões
    Route::get('/{user}/permissions', [UserManagementController::class, 'getPermissions'])->name('permissions.get');
    Route::post('/{user}/permissions', [UserManagementController::class, 'updatePermissions'])->name('permissions.update');
});

Route::prefix('admin/perfis')->name('admin.perfis.')->group(function () {
    Route::get('/', [PerfilController::class, 'index'])->name('index');
    Route::get('/create', [PerfilController::class, 'create'])->name('create');
    Route::post('/', [PerfilController::class, 'store'])->name('store');
    Route::get('/{perfil}/edit', [PerfilController::class, 'edit'])->name('edit');
    Route::put('/{perfil}', [PerfilController::class, 'update'])->name('update');
    Route::delete('/{perfil}', [PerfilController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin/roles')->name('admin.roles.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/create', [RoleController::class, 'create'])->name('create');
    Route::post('/', [RoleController::class, 'store'])->name('store');
    Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
    Route::put('/{role}', [RoleController::class, 'update'])->name('update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
});





Route::get('/lead/rastrear/{token}', [LeadInteresseController::class, 'rastrear'])->name('lead.rastrear');
Route::get('/interesse/{token}', [LeadInteresseController::class, 'show'])->name('lead.interesse');
Route::post('/interesse/{token}', [LeadInteresseController::class, 'store'])->name('lead.interesse.store');
Route::post('/interesse/{token}/reenviar', [LeadInteresseController::class, 'reenviar'])->name('lead.interesse.reenviar');
Route::get('/campanha/{token}', [FormularioCampanhaController::class, 'show'])->name('public.campanhas.formulario.show');
Route::post('/campanha/{token}', [FormularioCampanhaController::class, 'store'])->name('public.campanhas.formulario.store');

Route::prefix('admin/crm/leads')->middleware(['auth'])->name('admin.leads.')->group(function () {
    Route::get('/', [LeadController::class, 'index'])->name('index');
    Route::get('/criar', [LeadController::class, 'create'])->name('create');
    Route::post('/', [LeadController::class, 'store'])->name('store');
    Route::get('/template-csv', [LeadController::class, 'templateCsv'])->name('template');
    Route::post('/importar', [LeadController::class, 'import'])->name('import');
    Route::post('/importar-texto', [LeadController::class, 'importText'])->name('import.text');
    Route::post('/enviar-emails', [LeadController::class, 'enviarEmails'])->name('enviar.emails');
    Route::get('/{lead}/whatsapp', [LeadController::class, 'whatsapp'])->name('whatsapp');
    Route::get('/{lead}', [LeadController::class, 'show'])->name('show');
    Route::get('/{lead}/editar', [LeadController::class, 'edit'])->name('edit');
    Route::put('/{lead}', [LeadController::class, 'update'])->name('update');
    Route::delete('/{lead}', [LeadController::class, 'destroy'])->name('destroy');
    Route::post('/{lead}/resetar', [LeadController::class, 'resetar'])->name('resetar');
});

Route::prefix('crm')->middleware(['auth', 'tenant'])->name('crm.')->group(function () {
    Route::get('/', DashboardCRMController::class)->name('dashboard');
    Route::get('/pipeline', [PipelineController::class, 'index'])->name('pipeline.index');
    Route::patch('/pipeline/{lead}/mover', [PipelineController::class, 'move'])->name('pipeline.move');

    Route::post('leads/{lead}/whatsapp', [CRMLeadController::class, 'whatsapp'])->name('leads.whatsapp');
    Route::resource('leads', CRMLeadController::class);
    Route::resource('campanhas', CRMCampanhaController::class)->only(['index', 'store', 'update']);
    Route::get('formularios', [FormularioCampanhaController::class, 'index'])->name('formularios.index');
    Route::patch('campanhas/{campanha}/formulario', [FormularioCampanhaController::class, 'update'])->name('campanhas.formulario.update');
    Route::post('tarefas', [CRMTarefaController::class, 'store'])->name('tarefas.store');
    Route::patch('tarefas/{tarefa}/concluir', [CRMTarefaController::class, 'concluir'])->name('tarefas.concluir');
    Route::get('relatorios', [CRMRelatorioController::class, 'index'])->name('relatorios.index');

    Route::resource('email-templates', CRMEmailTemplateController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::post('leads/{lead}/enviar-email', [CRMEmailEnvioController::class, 'enviar'])->name('leads.enviar-email');
    Route::post('leads/enviar-email-massa', [CRMEmailEnvioController::class, 'enviarMassa'])->name('leads.enviar-email-massa');

    Route::resource('modal-capturas', ModalCapturaController::class)->only(['index', 'store', 'update', 'destroy']);

    // Métricas / rastreamento
    Route::get('metricas',                        [\App\Http\Controllers\CRM\MetricaController::class, 'index'])->name('metricas.index');
    Route::post('metricas/sites',                 [\App\Http\Controllers\CRM\MetricaController::class, 'storeSite'])->name('metricas.sites.store');
    Route::put('metricas/sites/{widgetSite}',     [\App\Http\Controllers\CRM\MetricaController::class, 'updateSite'])->name('metricas.sites.update');
    Route::delete('metricas/sites/{widgetSite}',  [\App\Http\Controllers\CRM\MetricaController::class, 'destroySite'])->name('metricas.sites.destroy');

    // Automação com IA — Sequências de mensagens
    Route::prefix('sequencias')->name('sequencias.')->group(function () {
        Route::get('/',                                              [\App\Http\Controllers\CRM\SequenciaController::class, 'index'])->name('index');
        Route::post('/',                                             [\App\Http\Controllers\CRM\SequenciaController::class, 'store'])->name('store');
        Route::put('/{sequencia}',                                   [\App\Http\Controllers\CRM\SequenciaController::class, 'update'])->name('update');
        Route::delete('/{sequencia}',                                [\App\Http\Controllers\CRM\SequenciaController::class, 'destroy'])->name('destroy');
        Route::patch('/{sequencia}/toggle',                          [\App\Http\Controllers\CRM\SequenciaController::class, 'toggleAtivo'])->name('toggle');
        Route::get('/{sequencia}/envios',                            [\App\Http\Controllers\CRM\SequenciaController::class, 'envios'])->name('envios');
        Route::post('/{sequencia}/disparar',                         [\App\Http\Controllers\CRM\SequenciaController::class, 'dispararParaLead'])->name('disparar');
    });
});

// Widget JS — público, sem auth
Route::get('/widget/{token}.js',         [WidgetController::class, 'js'])->name('widget.js');
Route::get('/widget/bot/{token}.js',     [WidgetController::class, 'botJs'])->name('widget.bot.js');
Route::get('/widget/track/{token}.js',   [WidgetController::class, 'trackJs'])->name('widget.track.js');

// API pública do widget — sem auth, CORS liberado
Route::prefix('api/widget')->middleware('api')->group(function () {
    Route::post('{token}/lead', [ModalLeadController::class, 'submit'])->name('widget.lead.submit');
});

// API pública do bot widget — sem auth
Route::prefix('api/bot')->middleware('api')->group(function () {
    Route::post('{token}/chat', [\App\Http\Controllers\Api\BotWidgetController::class, 'chat'])->name('bot.widget.chat');
});

// API pública de rastreamento — sem auth, CORS explícito
Route::prefix('api/track')->middleware('api')->group(function () {
    Route::options('{token}/evento', [\App\Http\Controllers\Api\MetricaWidgetController::class, 'preflight']);
    Route::post('{token}/evento',    [\App\Http\Controllers\Api\MetricaWidgetController::class, 'evento'])->name('track.evento');
});



Route::get('/google/prof/redirect', [SocialLiteController::class, 'professorRedirectToGoogle'])->name('prof.login.google');
Route::get('/google/prof/callback', [SocialLiteController::class, 'professorGoogleCallback'])->name('prof.handle.google');


Route::get('/google/aluno/redirect', [SocialLiteController::class, 'alunoRedirectToGoogle'])->name('aluno.googleAuth.redirect');
Route::get('/google/callback', [SocialLiteController::class, 'alunoGoogleCallback'])->name('aluno.googleAuth.handle');
Route::get('/auth/callback/google', [SocialLiteController::class, 'alunoGoogleCallback'])->name('aluno.googleAuth.handle');


Route::get('/empresa/pagamento/boleto', [BoletoController::class, 'boleto'])->name('empresa.pagamento.boleto');

// Route::get('/google-calendar/auth', [GoogleCalendarController::class, 'authenticate'])->name('google.calendar.auth');
// Route::get('/google-calendar/events', [GoogleCalendarController::class, 'listEvents'])->name('google.calendar.events');



Route::middleware('auth')->post('/subcontas', [ProfessoresAsaasController::class, 'createSubaccount'])->name('criar-subconta-professor');





Route::prefix('admin')->group(function () {
    Route::get('/usuarios', [UserManagementController::class, 'index'])->name('admin.usuarios.index');
    Route::get('/usuarios/criar', [UserManagementController::class, 'create'])->name('admin.usuarios.create');
    Route::post('/usuarios', [UserManagementController::class, 'store'])->name('admin.usuarios.store');
    Route::get('/usuarios/{id}/edit', [UserManagementController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/usuarios/{id}', [UserManagementController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('/usuarios/{id}', [UserManagementController::class, 'destroy'])->name('admin.usuarios.destroy');
});

Route::get('/site/{slug}', [SiteController::class, 'mostrar'])->name('site.publico');

Route::get('/landing', [SiteController::class, 'landing'])->name('site.landing');
Route::post('/landing/lead', [SiteController::class, 'lead'])->name('site.landing.lead');


Route::prefix('admin/site/ssl')->middleware(['auth'])->name('admin.site.dominios.')->group(function () {
    Route::get('/', [SiteController::class, 'editarDominio'])->name('index');
    Route::post('/', [SiteController::class, 'atualizarDominio'])->name('update');
});
// routes/web.php
Route::post('/admin/site/{site}/dominio', [SiteController::class, 'atualizarDominio'])
    ->name('admin.site.dominios.update');

Route::put('/admin/site/{site}/configuracoes', [SiteController::class, 'atualizarConfiguracoes'])
    ->name('admin.site.configuracoes.update');

Route::prefix('admin/site')->middleware(['auth'])->group(function () {
    Route::get('lista', [SiteController::class, 'lista'])->name('admin.site.lista');
    Route::get('create', [SiteController::class, 'create'])->name('admin.site.create');
    Route::post('criar', [SiteController::class, 'store'])->name('admin.site.store');
    Route::get('edit/{idsite}', [SiteController::class, 'editSite'])->name('admin.site.edit');
    Route::delete('{idsite}', [SiteController::class, 'destroy'])->name('admin.site.destroy'); // New destroy route

    Route::get('configuracoes', [SiteController::class, 'edit'])->name('admin.site.configuracoes');
    Route::resource('servicos', SiteController::class)->names('admin.site.servicos');
    Route::resource('depoimentos', SiteController::class)->names('admin.site.depoimentos');
    Route::resource('contatos', SiteController::class)->names('admin.site.contatos');

    Route::get('dominios', [SiteController::class, 'dominios'])->name('admin.site.dominios')->middleware('can:admin');

    // Atualizar configurações do site
    Route::put('configuracoes/{site}', [SiteController::class, 'update'])->name('admin.site.configuracoes.update');
});

Route::prefix('admin/site/servicos')->middleware(['auth'])->name('admin.site.servicos.')->group(function () {
    Route::get('/', [SiteServicoController::class, 'index'])->name('index');
    Route::get('create', [SiteServicoController::class, 'create'])->name('create');
    Route::post('store', [SiteServicoController::class, 'store'])->name('store');
    Route::get('{servico}/edit', [SiteServicoController::class, 'edit'])->name('edit');
    Route::put('{servico}/update', [SiteServicoController::class, 'update'])->name('update');
    Route::any('{servico}/destroy', [SiteServicoController::class, 'destroy'])->name('destroy');
});


use App\Http\Controllers\TrackingCodesController;

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::post('site/{site_id}/tracking/store', [TrackingCodesController::class, 'store'])->name('tracking.store');
    Route::put('tracking/{id}/update', [TrackingCodesController::class, 'update'])->name('tracking.update');
    Route::post('tracking/{id}/destroy', [TrackingCodesController::class, 'destroy'])->name('tracking.destroy');
});


Route::prefix('admin/site/depoimentos')->middleware('auth')->name('admin.site.depoimentos.')->group(function () {
    Route::get('/', [SiteDepoimentoController::class, 'index'])->name('index');
    Route::get('create', [SiteDepoimentoController::class, 'create'])->name('create');
    Route::post('store', [SiteDepoimentoController::class, 'store'])->name('store');
    Route::get('{depoimento}/edit', [SiteDepoimentoController::class, 'edit'])->name('edit');
    Route::put('{depoimento}/update', [SiteDepoimentoController::class, 'update'])->name('update');
    Route::post('{depoimento}/destroy', [SiteDepoimentoController::class, 'destroy'])->name('destroy');
});


Route::prefix('admin/site/contatos')->middleware('auth')->name('admin.site.contatos.')->group(function () {
    Route::get('/', [SiteContatoController::class, 'index'])->name('index');
    Route::get('create', [SiteContatoController::class, 'create'])->name('create');
    Route::post('store', [SiteContatoController::class, 'store'])->name('store');
    Route::get('{contato}/edit', [SiteContatoController::class, 'edit'])->name('edit');
    Route::put('{contato}/update', [SiteContatoController::class, 'update'])->name('update');
    Route::delete('{contato}/destroy', [SiteContatoController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin/site/artigos')->middleware('auth')->name('admin.site.artigos.')->group(function () {
    Route::post('gerar-conteudo', [SiteArtigoController::class, 'generateContent'])->name('generate');
    Route::get('/', [SiteArtigoController::class, 'index'])->name('index');
    Route::get('create', [SiteArtigoController::class, 'create'])->name('create');
    Route::post('store', [SiteArtigoController::class, 'store'])->name('store');
    Route::get('{artigo}/preview', [SiteArtigoController::class, 'preview'])->name('preview');
    Route::get('{artigo}/edit', [SiteArtigoController::class, 'edit'])->name('edit');
    Route::put('{artigo}', [SiteArtigoController::class, 'update'])->name('update');
    Route::delete('{artigo}', [SiteArtigoController::class, 'destroy'])->name('destroy');
});


Route::get('/google-calendar/auth', [GoogleCalendarController::class, 'authenticate'])->name('google.calendar.auth');
Route::get('/google-calendar/callback', [GoogleCalendarController::class, 'authenticate']);
Route::get('/google-calendar/events', [GoogleCalendarController::class, 'listEvents'])->name('google.calendar.events');
Route::get('/google-calendar/create-event', [GoogleCalendarController::class, 'createEvent']);

Route::get('/events', [GoogleCalendarController::class, 'getAllEvents'])->name('google.calendar.events');
Route::get('/google-calendar/create-event', [GoogleCalendarController::class, 'createEvent']);



Route::prefix('admin')->middleware(['check_user_authenticated'])->group(function () {});

Route::post('/avaliacao/store', [AvaliacaoController::class, 'store'])->name('empresa.avaliacao.store');
Route::post('/avaliar-aula', [AvaliacaoController::class, 'storeAvaliacao'])->name('avaliacao.store');
Route::get('/avaliacao/{agendamento_id}', [AvaliacaoController::class, 'getAvaliacoes'])->name('avaliacao.getAvaliacoes');

Route::get('/check-payment-status/{cobranca_id}', [PagamentoController::class, 'checkPaymentStatus'])->name('check-payment-status');

Route::post('/asaas', [PixQrController::class, 'fazerAgendamentoPix'])->name('empresa.pagamento.asaas');
Route::post('/contato/enviar/{site}', [EmailController::class, 'enviar'])->name('site.email.enviar');


Route::get('/test', function () {
    return Inertia::render('Test');
});

// Rota temporária de debug — remover após confirmar o domínio
Route::get('/debug-host', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'host'            => $request->getHost(),
        'full_url'        => $request->fullUrl(),
        'header_host'     => $request->header('Host'),
        'header_x_forwarded_host' => $request->header('X-Forwarded-Host'),
        'in_dominios_surf' => in_array($request->getHost(), DOMINIOS_SURF),
    ]);
});

Route::get('/treino', [AgendaController::class, 'treino'])->name('treino');
Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/viacep/{cep}', [ViaCepController::class, 'getCep']);


Route::prefix('admin/bot')->name('admin.bot.')->middleware('auth')->group(function () {
    Route::get('dashboard', [BotController::class, 'dashboard'])->name('dashboard');
    Route::get('/', [BotController::class, 'index'])->name('index');
    Route::get('create', [BotController::class, 'create'])->name('create');
    Route::post('store', [BotController::class, 'store'])->name('store');
    Route::put('update/{id}', [BotController::class, 'update'])->name('update');
    Route::get('edit/{id}', [BotController::class, 'edit'])->name('edit');
    Route::delete('destroy/{id}', [BotController::class, 'destroy'])->name('destroy');;
    Route::get('tokens', [BotController::class, 'tokens'])->name('tokens');
    Route::get('logs', [BotController::class, 'logs'])->name('logs');

    Route::get('/{bot}', [BotController::class, 'show'])->name('show');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('conversas', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('conversas/{id}', [ConversationController::class, 'show'])->name('conversations.show');
});

Route::post('/admin/bot/{bot}/chat', [BotController::class, 'chat'])->name('admin.bot.chat');

Route::post('/chat/store', [ChatController::class, 'store'])->name('chat.store');

Route::post('/chat/enviarparabatepaposite', [ChatController::class, 'enviarparabatepaposite'])->name('chat.enviarparabatepaposite');


Route::get('/chat/{conversationId?}', [ChatController::class, 'chat'])->name('chat.index');

Route::post('/chat/update-control', [ChatController::class, 'updateControl'])
    ->name('chat.updateControl');

use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\VirtualHostController;

// ─── Super Admin ───────────────────────────────────────────
Route::prefix('super-admin')->name('super.admin.')->middleware(['auth', 'master'])->group(function () {
    Route::get('/',                               [SuperAdminController::class, 'index'])->name('index');
    Route::get('/clientes',                       [SuperAdminController::class, 'clientes'])->name('clientes');
    Route::get('/clientes/{empresa}',             [SuperAdminController::class, 'show'])->name('show');
    Route::patch('/clientes/{empresa}/toggle',    [SuperAdminController::class, 'toggleStatus'])->name('toggle');

    // Configuração de nichos
    Route::get('/nichos',                         [SuperAdminController::class, 'nichos'])->name('nichos');
    Route::get('/nichos/criar',                   [SuperAdminController::class, 'nichoCreate'])->name('nichos.create');
    Route::post('/nichos',                        [SuperAdminController::class, 'nichoStore'])->name('nichos.store');
    Route::get('/nichos/{nicho}/editar',          [SuperAdminController::class, 'nichoEdit'])->name('nichos.edit');
    Route::put('/nichos/{nicho}',                 [SuperAdminController::class, 'nichoUpdate'])->name('nichos.update');
    Route::delete('/nichos/{nicho}',              [SuperAdminController::class, 'nichoDestroy'])->name('nichos.destroy');
    // Modalidades por nicho
    Route::post('/nichos/{nicho}/modalidades',                        [SuperAdminController::class, 'nichoModalidadeStore'])->name('nichos.modalidades.store');
    Route::delete('/nichos/{nicho}/modalidades/{modalidade}',         [SuperAdminController::class, 'nichoModalidadeDestroy'])->name('nichos.modalidades.destroy');

    // CRM — leads das landing pages (SaaS)
    Route::get('/crm',                            [SuperAdminController::class, 'crmLeads'])->name('crm.leads');
    Route::get('/crm/pipeline',                   [SuperAdminController::class, 'crmPipeline'])->name('crm.pipeline');
    Route::patch('/crm/{lead}/mover',             [SuperAdminController::class, 'crmMover'])->name('crm.mover');
});

Route::resource('virtualhosts', VirtualHostController::class)->except(['show']);
Route::get('/virtualhosts', [VirtualHostController::class, 'index'])->name('virtualhosts.index');
Route::delete('/virtualhosts/{file}', [VirtualHostController::class, 'destroy'])->name('virtualhosts.destroy');
Route::get('/virtualhosts', [VirtualHostController::class, 'index'])->name('virtualhosts.index');
Route::get('/virtualhosts/{file}/json', [VirtualHostController::class, 'json'])->name('virtualhosts.json')->where('file', '.*');
Route::put('/virtualhosts/{file}', [VirtualHostController::class, 'update'])->name('virtualhosts.update')->where('file', '.*');
Route::delete('/virtualhosts/{file}', [VirtualHostController::class, 'destroy'])->name('virtualhosts.destroy');



Route::prefix('financeiro')->middleware(['auth'])->group(function () {

    // Dashboard financeiro


    // Receitas (Contas a Receber)
    Route::get('/receitas', [ReceitaController::class, 'index'])->name('financeiro.receitas.index');
    Route::get('/receitas/create', [ReceitaController::class, 'create'])->name('financeiro.receitas.create');
    Route::post('/receitas', [ReceitaController::class, 'store'])->name('financeiro.receitas.store');
    Route::get('/receitas/{id}/edit', [ReceitaController::class, 'edit'])->name('financeiro.receitas.edit');
    Route::put('/receitas/{id}', [ReceitaController::class, 'update'])->name('financeiro.receitas.update');
    Route::delete('/receitas/{id}', [ReceitaController::class, 'destroy'])->name('financeiro.receitas.destroy');


    // Route::get('/', [FinanceiroController::class, 'index'])->name('financeiro.index');
    //  Despesas (Contas a Pagar)


    Route::get('/despesas', [DespesaController::class, 'index'])->name('financeiro.despesas.index');
    Route::get('/despesas/create', [DespesaController::class, 'create'])->name('financeiro.despesas.create');
    Route::post('/despesas', [DespesaController::class, 'store'])->name('financeiro.despesas.store');
    Route::get('/despesas/{id}/edit', [DespesaController::class, 'edit'])->name('financeiro.despesas.edit');
    Route::put('/despesas/{id}', [DespesaController::class, 'update'])->name('financeiro.despesas.update');
    Route::delete('/despesas/{id}', [DespesaController::class, 'destroy'])->name('financeiro.despesas.destroy');

    // // Fluxo de Caixa
    // Route::get('/fluxo', [FluxoCaixaController::class, 'index'])->name('financeiro.fluxo.index');

    // // Relatórios
    // Route::get('/relatorios', [RelatorioFinanceiroController::class, 'index'])->name('financeiro.relatorios.index');

    // // Configurações
    // Route::get('/config', [FinanceiroConfigController::class, 'index'])->name('financeiro.config.index');
    // Route::post('/config', [FinanceiroConfigController::class, 'store'])->name('financeiro.config.store');

    Route::get('/dashboard', [\App\Http\Controllers\FinanceiroDashboardController::class, 'index'])
        ->name('admin.financeiro.dashboard');

    Route::get('despesas/resumo/ajax', [DespesaController::class, 'resumo'])->name('despesas.resumo');

    Route::get('despesas/buscar_despesas', [DespesaController::class, 'buscar_despesas'])->name('despesas.buscar_despesas');
});



Route::prefix('financeiro')->name('financeiro.')->group(function () {
    Route::resource('categorias', FinanceiroCategoriaController::class);
});



Route::get('/financeiro/despesas_recorrentes/index', [DespesasRecorrenteController::class, 'index'])->name('financeiro.despesas_recorrentes.index');



// Listar todas as despesas recorrentes
Route::get('financeiro/despesas_recorrentes', [DespesasRecorrenteController::class, 'index'])
    ->name('financeiro.despesas_recorrentes.index');

// Mostrar formulário para criar nova despesa recorrente
Route::get('financeiro/despesas_recorrentes/create', [DespesasRecorrenteController::class, 'create'])
    ->name('financeiro.despesas_recorrentes.create');

// Salvar nova despesa recorrente
Route::post('financeiro/despesas_recorrentes', [DespesasRecorrenteController::class, 'store'])
    ->name('financeiro.despesas_recorrentes.store');

// Mostrar formulário para editar despesa recorrente existente
Route::get('financeiro/despesas_recorrentes/{despesaRecorrente}/edit', [DespesasRecorrenteController::class, 'edit'])
    ->name('financeiro.despesas_recorrentes.edit');

// Atualizar despesa recorrente existente
Route::put('financeiro/despesas_recorrentes/{despesaRecorrente}', [DespesasRecorrenteController::class, 'update'])
    ->name('financeiro.despesas_recorrentes.update');

// Deletar despesa recorrente
Route::delete('financeiro/despesas_recorrentes/{despesaRecorrente}', [DespesasRecorrenteController::class, 'destroy'])
    ->name('financeiro.despesas_recorrentes.destroy');


Route::prefix('financeiro')->name('financeiro.')->group(function () {
    Route::get('receitas_recorrentes', [ReceitaRecorrenteController::class, 'index'])->name('receitas_recorrentes.index');
    Route::get('receitas_recorrentes/create', [ReceitaRecorrenteController::class, 'create'])->name('receitas_recorrentes.create');
    Route::post('receitas_recorrentes', [ReceitaRecorrenteController::class, 'store'])->name('receitas_recorrentes.store');
    Route::get('receitas_recorrentes/{receitaRecorrente}/edit', [ReceitaRecorrenteController::class, 'edit'])->name('receitas_recorrentes.edit');
    Route::put('receitas_recorrentes/{receitaRecorrente}', [ReceitaRecorrenteController::class, 'update'])->name('receitas_recorrentes.update');
    Route::delete('receitas_recorrentes/{receitaRecorrente}', [ReceitaRecorrenteController::class, 'destroy'])->name('receitas_recorrentes.destroy');
});


Route::prefix('admin/botservice')->name('admin.botservice.')->group(function () {
    Route::get('/', [BotServiceController::class, 'index'])->name('index');
    Route::get('/create', [BotServiceController::class, 'create'])->name('create');
    Route::post('/store', [BotServiceController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [BotServiceController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BotServiceController::class, 'update'])->name('update');
    Route::delete('/{id}', [BotServiceController::class, 'destroy'])->name('destroy');
});



use App\Http\Controllers\PlanoController;


Route::get('planos', [PlanoController::class, 'index'])->name('admin.planos.index');
Route::get('planos/create', [PlanoController::class, 'create'])->name('admin.planos.create');
Route::post('planos', [PlanoController::class, 'store'])->name('admin.planos.store');
Route::get('planos/{plano}', [PlanoController::class, 'show'])->name('admin.planos.show');
Route::get('planos/{plano}/edit', [PlanoController::class, 'edit'])->name('admin.planos.edit');
Route::put('planos/{plano}', [PlanoController::class, 'update'])->name('admin.planos.update');
Route::delete('planos/{plano}', [PlanoController::class, 'destroy'])->name('admin.planos.destroy');


use App\Http\Controllers\EmpresaPlanoController;
use App\Http\Controllers\FinanceiroDashboardController;

Route::prefix('admin/empresas/{empresa}')->group(function () {
    Route::get('planos', [EmpresaPlanoController::class, 'index'])->name('admin.empresas.planos.index');
    Route::post('planos', [EmpresaPlanoController::class, 'store'])->name('admin.empresas.planos.store');
});



Route::get('/admin/financeiro/detalhes', [FinanceiroDashboardController::class, 'detalhes'])->name('financeiro.detalhes');

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;
use Prometheus\Exception\MetricAlreadyExistsException;

Route::get('/metrics', function () {
    $registry = new CollectorRegistry(new InMemory());

    // Contador de requisições GET
    try {
        $counter = $registry->registerCounter(
            'app',               // namespace
            'requests_total',    // nome da métrica
            'Total de requisições', // descrição
            ['method']           // labels
        );
    } catch (MetricAlreadyExistsException $e) {
        $counter = $registry->getCounter('app', 'requests_total');
    }

    $counter->inc(['GET']); // incrementa a cada requisição

    // Tempo de execução simulando uma métrica de histogram
    try {
        $histogram = $registry->registerHistogram(
            'app',
            'response_time_seconds',
            'Tempo de resposta',
            ['route'],
            [0.1, 0.5, 1, 2, 5]
        );
    } catch (MetricAlreadyExistsException $e) {
        $histogram = $registry->getHistogram('app', 'response_time_seconds');
    }

    // Simula tempo de resposta aleatório entre 0.1 e 2s
    $histogram->observe(rand(1, 20) / 10, ['/metrics']);

    $renderer = new RenderTextFormat();
    return response($renderer->render($registry->getMetricFamilySamples()))
        ->header('Content-Type', RenderTextFormat::MIME_TYPE);
});


use App\Http\Controllers\PlanoAlunoController;
use App\Http\Controllers\SiteArtigoPublicPageController;

// Páginas legais
Route::view('/termos', 'legal.termos')->name('legal.termos');
Route::view('/privacidade', 'legal.privacidade')->name('legal.privacidade');
Route::view('/lgpd', 'legal.lgpd')->name('legal.lgpd');

Route::prefix('admin')->group(function () {
    Route::get('planos', [PlanoAlunoController::class, 'indexView'])->name('alunos.planos.index');
    Route::get('planos/create', [PlanoAlunoController::class, 'create'])->name('alunos.planos.create');
    Route::post('planos', [PlanoAlunoController::class, 'store'])->name('alunos.planos.store');
    Route::get('planos/vincular', [PlanoAlunoController::class, 'vincular'])->name('alunos.planos.vincular');
    Route::post('planos/vincular', [PlanoAlunoController::class, 'vincularStore'])->name('alunos.planos.vincular.store');
    Route::get('planos/{plano}/edit', [PlanoAlunoController::class, 'edit'])->name('alunos.planos.edit');
    Route::put('planos/{plano}', [PlanoAlunoController::class, 'update'])->name('alunos.planos.update');
    Route::delete('planos/{plano}', [PlanoAlunoController::class, 'destroy'])->name('alunos.planos.destroy');
});
