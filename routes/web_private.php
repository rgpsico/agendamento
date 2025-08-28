<?php



use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AlunosController;
use App\Http\Controllers\Api\AgendamentoControllerApi;
use App\Http\Controllers\Api\EmpresaControllerApi;
use App\Http\Controllers\ConfiguracoesController;
use App\Http\Controllers\DisponibilidadeController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ModalidadeController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IntegrationsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\PixQrController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\PaymentConfigurationController;
use App\Http\Controllers\SiteTemplateController;


;
Route::post('/pagamento', [StripeController::class, 'treinoStripe'])->name('stripe.pagamento');
Route::get('/erro/pagamento', [PagamentoController::class, 'erroPagamento'])->name('erroPagamento');

Route::prefix('usuario')->group(function () {
    Route::post('/', [UserController::class, 'store'])->name('user.store');
    Route::get('logout/', [UserController::class, 'logout'])->name('user.logout');
    Route::post('/login', [UserController::class, 'login'])->name('user.login');
});

Route::post('/webhook/asaas', [PagamentoController::class, 'webhookAsaas'])->name('webhook.asaas');



Route::post('/pix/chave', [PixQrController::class, 'criarChavePix'])->name('pix.chave');
Route::get('/integracao', [PagamentoController::class, 'mostrarIntegracao'])->name('integracao.assas.escola')->middleware('auth');
Route::get('/integracao/pix', [PagamentoController::class, 'mostrarIntegracaopix'])->name('integracao.assas.pix')->middleware('auth');

Route::post('/integrar/asaas', [PagamentoController::class, 'integrarAsaas'])->name('integrar.asaas');


Route::get('/recibo/{id}', [PagamentoController::class, 'verRecibo'])->name('recibo');

// Grupo de rotas para gerenciamento de gateways de pagamento
Route::prefix('empresa/pagamento')->middleware(['auth'])->group(function () {
    // Listar todos os gateways configurados
    Route::get('/', [PagamentoController::class, 'index'])->name('empresa.pagamento.index');

    // Exibir formulário de criação de gateway
    Route::get('/create', [PagamentoController::class, 'create'])->name('empresa.pagamento.create');

    // Salvar novo gateway
    Route::post('/store', [PagamentoController::class, 'store'])->name('empresa.pagamento.store');

    // Exibir formulário de edição de gateway
    Route::get('/{id}/edit', [PagamentoController::class, 'edit'])->name('empresa.pagamento.edit');

    // Atualizar gateway existente
    Route::put('/{id}', [PagamentoController::class, 'update'])->name('empresa.pagamento.update');

    // Testar conexão com o gateway
    Route::post('/test', [PagamentoController::class, 'test'])->name('empresa.pagamento.test');

    // Processar pagamento com Asaas
    Route::post('/asaas', [PagamentoController::class, 'pagamentoAsaas'])->name('empresa.pagamento.asaas');
});

// Rota para o webhook do Asaas (sem autenticação, pois é chamada pela API do Asaas)
Route::post('/webhook/asaas', [PagamentoController::class, 'webhookAsaas'])->name('webhook.asaas');

// Rota para página de sucesso no checkout (já mencionada no seu controlador)
Route::get('/checkout/sucesso/{id}', [HomeController::class, 'checkoutSucesso'])->name('home.checkoutsucesso');

// Rota para página de erro no pagamento (já mencionada no seu controlador)
Route::get('/erro/pagamento', [PagamentoController::class, 'erroPagamento'])->name('erroPagamento');

Route::prefix('empresa/pagamento')->group(function () {
    Route::get('/', [PagamentoController::class, 'index'])->name('empresa.pagamento.index');
    Route::get('/create', [PagamentoController::class, 'create'])->name('empresa.pagamento.create');
    Route::post('/store', [PagamentoController::class, 'store'])->name('empresa.pagamento.store');
    Route::get('/{id}/edit', [PagamentoController::class, 'edit'])->name('empresa.pagamento.edit');
    Route::put('/{id}', [PagamentoController::class, 'update'])->name('empresa.pagamento.update');
});


Route::prefix('integracoes')->group(function () {
    Route::get('/asaas', [IntegrationsController::class, 'asaas'])->name('integracoes.asaas');
    Route::get('/stripe', [IntegrationsController::class, 'stripe'])->name('integracoes.stripe');
    Route::get('/mercadopago', [IntegrationsController::class, 'mercadopago'])->name('integracoes.mercadopago');
    Route::get('/configuracoes', [IntegrationsController::class, 'configuracoes'])->name('integracoes.configuracoes');
    Route::get('/relatorios', [IntegrationsController::class, 'relatorios'])->name('integracoes.relatorios');
    Route::post('/salvar', [IntegrationsController::class, 'salvar'])->name('integracoes.asaas.salvar');
});

Route::prefix('configuracoes')->group(function () {
    Route::get('/admin', [ConfiguracoesController::class, 'indexAdmin'])->name('configuracoes.indexAdmin');
    Route::get('/configuracoes', [ConfiguracoesController::class, 'index'])->name('configuracoes.index');
    Route::post('/configuracoes/salvar', [ConfiguracoesController::class, 'salvar'])->name('configuracoes.salvar');

    Route::get('/configuracoes', [ConfiguracoesController::class, 'index'])->name('configuracoes.index');
    Route::post('/configuracoesgeral/salvar', [ConfiguracoesController::class, 'salvarConfigGeral'])->name('configuracoesGeral.salvar');

    Route::get('/permissoes', [ConfiguracoesController::class, 'permissoes'])->name('configuracoes.permissoes');
    Route::get('/pagamentos', [ConfiguracoesController::class, 'pagamentos'])->name('configuracoes.pagamentos');
    Route::get('/empresa', [ConfiguracoesController::class, 'empresa'])->name('configuracoes.empresa');
    Route::get('/usuarios', [ConfiguracoesController::class, 'usuarios'])->name('configuracoes.usuarios');
    Route::get('/sistema', [ConfiguracoesController::class, 'sistema'])->name('configuracoes.sistema');
    Route::get('/configuracoes', [ConfiguracoesController::class, 'index'])->name('configuracoes.index');
    Route::post('/configuracoes/salvar', [ConfiguracoesController::class, 'salvar'])->name('configuracoes.salvar');
});

Route::prefix('cliente')->middleware('auth')->group(function () {
    Route::group(['prefix' => '/servicos'], function () {
        Route::get('/', [ServicoController::class, 'index'])->name('admin.servico.index');
        Route::get('/{id}/servico', [ServicoController::class, 'show'])->name('admin.servico.show');
        Route::get('/create', [ServicoController::class, 'create'])->name('admin.servico.create');
        Route::get('/{id}/edit', [ServicoController::class, 'edit'])->name('admin.servico.edit');
        Route::post('/store', [ServicoController::class, 'store'])->name('admin.servico.store');
        Route::delete('/{id}/delete', [ServicoController::class, 'destroy'])->name('admin.servico.destroy');
        Route::post('/{id}/update', [ServicoController::class, 'update'])->name('admin.servico.update');
    });

    Route::group(['prefix' => '/empresa'], function () {
        Route::get('tipopagamento/config', [PaymentConfigurationController::class, 'index'])->name('empresa.pagamento.config.index');
        Route::put('tipopagamento/{empresaId}', [PaymentConfigurationController::class, 'update'])->name('empresa.tipopagamento.config.update');
        Route::put('tipopagamento', [PaymentConfigurationController::class, 'store'])->name('empresa.tipopagamento.config.store');
        Route::post('empresa/pagamento/test', [PaymentConfigurationController::class, 'test'])->name('empresa.pagamento.config.test');


        Route::post('/{id}/restore', [EmpresaController::class, 'restore'])->name('empresa.restore');
        Route::get('/empresa/all', [EmpresaController::class, 'index'])->name('empresa.index');
        Route::get('/{id}/show', [EmpresaController::class, 'show'])->name('empresa.show');
        Route::get('/create', [EmpresaController::class, 'create'])->name('empresa.create');
        Route::get('/{id}/edit', [EmpresaController::class, 'edit'])->name('empresa.edit');
        Route::any('update/{id}', [EmpresaController::class, 'update'])->name('empresa.update');
        Route::post('/post', [EmpresaController::class, 'store'])->name('empresa.store');
        Route::post('/{userId}/endereco_empresa', [EmpresaController::class, 'endereco_update'])->name('empresa.update_endereco');

        Route::get('/pagamento/create', [PagamentoController::class, 'create'])->name('empresa.pagamento.create');
        Route::get('/pagamento', [PagamentoController::class, 'index'])->name('pagamento.index');
        Route::post('/pagamento/store', [PagamentoController::class, 'store'])->name('empresa.pagamento.store');
        Route::put('/pagamento/{id}/update', [PagamentoController::class, 'update'])->name('empresa.pagamento.update');

        Route::get('/pagamento/{id}/edit', [PagamentoController::class, 'edit'])->name('empresa.pagamento.edit');

        Route::post('/disponibilidadeperstore', [DisponibilidadeController::class, 'storeper'])->name('storeper');
        Route::get('/disponibilidadeper', [EmpresaController::class, 'disponibilidadePersonalizada'])->name('empresa.disponibilidadePersonalizada');
        Route::get('/disponibilidadeper/horariosauto', [EmpresaController::class, 'autoHorario'])->name('empresa.horarios.auto');
        Route::post('/disponibilidadeper/horariogerar', [EmpresaController::class, 'gerarHorarios'])->name('horario.gerar');

        Route::get('/disponibilidade', [EmpresaController::class, 'disponibilidade'])->name('empresa.disponibilidade');
        Route::post('/disponibilidade', [EmpresaController::class, 'cadastrarDisponibilidade'])->name('empresa.disponibilidade.store');


        Route::get('/servicos', [ServicoController::class, 'listarServicos'])->name('listar.servicos');
        Route::get('/configurar-horarios/{idServico}', [ServicoController::class, 'configurarHorarios'])->name('configurar.horarios');
        Route::post('/salvar-horarios/{idServico}', [ServicoController::class, 'salvarHorarios'])->name('salvar.horarios');


        Route::get('/', [EmpresaController::class, 'index'])->name('agenda.index');
        Route::get('/create', [EmpresaController::class, 'create'])->name('agenda.create');
        Route::post('/agenda/store', [EmpresaController::class, 'agendatore'])->name('empresa.agenda.store');
        Route::put('/{id}/agenda/update', [EmpresaController::class, 'agendaUpdate'])->name('empresa.agenda.update');
        Route::post('/{userId}/profile', [EmpresaController::class, 'profile'])->name('empresa.profile');
        Route::delete('/{id}/destroy', [EmpresaController::class, 'destroy'])->name('empresa.destroy');
        Route::get('/{userId}/config', [EmpresaController::class, 'configuracao'])->name('empresa.configuracao');
        Route::get('/fotos', [EmpresaController::class, 'fotos'])->name('empresa.fotos');
        Route::get('/{userId}/endereco_empresa', [EmpresaController::class, 'endereco'])->name('empresa.endereco');
        Route::post('/uploadEmpresa', [EmpresaController::class, 'uploadImage'])->name('empresa.upload');
        Route::post('/{id}/excluirImagens', [EmpresaController::class, 'galleryDestroy'])->name('gallery.destroy');


        Route::group(['prefix' => '/alunos'], function () {
            Route::get('/', [AlunosController::class, 'index'])->name('alunos.index');
            Route::get('/{id}/show', [AlunosController::class, 'show'])->name('alunos.show');
            Route::get('/create', [AlunosController::class, 'create'])->name('alunos.create');
            Route::get('/{id}/edit', [AlunosController::class, 'edit'])->name('aluno.edit');
            Route::post('/{id}/update', [AlunosController::class, 'update'])->name('alunos.update');
            Route::post('/post', [AlunosController::class, 'store'])->name('alunos.store');
            Route::delete('/{id}/destroy', [AlunosController::class, 'destroy'])->name('alunos.destroy');
            Route::get('/config', [AlunosController::class, 'configuracao'])->name('alunos.configuracao');
            Route::post('/uploadAluno', [AlunosController::class, 'uploadImage'])->name('aluno.upload');
        });

        Route::group(['prefix' => '/dashboard'], function () {
            Route::get('/', [EmpresaController::class, 'dashboard'])->name('cliente.dashboard');
        });

        Route::group(['prefix' => '/agenda'], function () {
            Route::get('/', [AgendaController::class, 'index'])->name('agenda.index');

            Route::get('/calendario', [AgendaController::class, 'calendario'])->name('agenda.calendario');
            Route::get('/{id}/show', [AgendaController::class, 'show'])->name('agenda.show');
            Route::get('/create', [AgendaController::class, 'create'])->name('agenda.create');
            Route::get('/{id}/edit', [AgendaController::class, 'edit'])->name('agenda.edit');
            Route::get('/form', [AgendaController::class, 'form'])->name('agenda.form ');
            Route::post('/{id}/update', [AgendaController::class, 'update'])->name('agenda.update');
            Route::post('/post', [AgendaController::class, 'store'])->name('agenda.store');
            Route::delete('/{id}/destroy', [AgendaController::class, 'destroy'])->name('agenda.destroy');
        });


        Route::group(['prefix' => '/modalidade'], function () {
            Route::get('/', [ModalidadeController::class, 'index'])->name('modalidade.index');
            Route::get('/create', [ModalidadeController::class, 'create'])->name('modalidade.create');
            Route::post('/{id}/update', [ModalidadeController::class, 'update'])->name('modalidade.update');
            Route::post('/{id}/show', [ModalidadeController::class, 'show'])->name('modalidade.show');
            Route::post('store', [ModalidadeController::class, 'store'])->name('modalidade.store');
            Route::delete('{id}/destroy', [ModalidadeController::class, 'destroy'])->name('admin.modalidade.destroy');
        });

        Route::get('{id}/profile', [UserController::class, 'profile'])->name('usuario.profile');
    });

    Route::get('/professor/agendamentos', [AgendamentoControllerApi::class, 'getAgendamentos']);
});



Route::prefix('pagamento')->middleware('auth')->group(function () {
    Route::post('/stripe', [PagamentoController::class, 'pagamentoStripe'])->name('pagamento.stripe');
});

Route::group(['prefix' => '/manager'], function () {
    Route::get('/', [EmpresaController::class, 'dashboard'])->name('admin.dashboard');
    // Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::post('/analytics/event', [\App\Http\Controllers\AnalyticsController::class, 'recordEvent'])->name('analytics.event');

Route::resource('site-templates', SiteTemplateController::class);


Route::get('site-templates/lista', [SiteTemplateController::class, 'index'])->name('admin.sitetemplate.index');


Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/relatorios', [\App\Http\Controllers\AnalyticsController::class, 'dashboard'])->name('admin.analytics.dashboard');
});
