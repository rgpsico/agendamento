<?php

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
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\ProfessoresAsaasController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\SiteDepoimentoController;

//   Route::get('/', [UserManagementController::class, 'index'])->name('register.aluno');
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


Route::prefix('admin/site/ssl')->middleware(['auth'])->name('admin.site.dominios.')->group(function () {
    Route::get('/', [SiteController::class, 'editarDominio'])->name('index');
    Route::post('/', [SiteController::class, 'atualizarDominio'])->name('update');
});
// routes/web.php
Route::post('/admin/site/dominios/update', [SiteController::class, 'atualizarDominio'])->name('admin.site.dominios.update');

Route::prefix('admin/site')->middleware(['auth'])->group(function () {
    Route::get('configuracoes', [SiteController::class, 'edit'])->name('admin.site.configuracoes');
    Route::resource('servicos', SiteController::class)->names('admin.site.servicos');
    Route::resource('depoimentos', SiteController::class)->names('admin.site.depoimentos');
    Route::resource('contatos', SiteController::class)->names('admin.site.contatos');

    Route::get('dominios', [SiteController::class, 'dominios'])->name('admin.site.dominios')->middleware('can:admin');

    Route::get('configuracoes', [SiteController::class, 'edit'])->name('admin.site.configuracoes');

    // Atualizar configurações do site
    Route::put('configuracoes/{site}', [SiteController::class, 'update'])->name('admin.site.configuracoes.update');
});

Route::prefix('admin/site/servicos')->middleware(['auth'])->name('admin.site.servicos.')->group(function () {
    Route::get('/', [SiteServicoController::class, 'index'])->name('index');
    Route::get('create', [SiteServicoController::class, 'create'])->name('create');
    Route::post('store', [SiteServicoController::class, 'store'])->name('store');
    Route::get('{servico}/edit', [SiteServicoController::class, 'edit'])->name('edit');
    Route::put('{servico}/update', [SiteServicoController::class, 'update'])->name('update');
    Route::delete('{servico}/destroy', [SiteServicoController::class, 'destroy'])->name('destroy');
});




Route::prefix('admin/site/depoimentos')->middleware('auth')->name('admin.site.depoimentos.')->group(function () {
    Route::get('/', [SiteDepoimentoController::class, 'index'])->name('index');
    Route::get('create', [SiteDepoimentoController::class, 'create'])->name('create');
    Route::post('store', [SiteDepoimentoController::class, 'store'])->name('store');
    Route::get('{depoimento}/edit', [SiteDepoimentoController::class, 'edit'])->name('edit');
    Route::put('{depoimento}/update', [SiteDepoimentoController::class, 'update'])->name('update');
    Route::delete('{depoimento}/destroy', [SiteDepoimentoController::class, 'destroy'])->name('destroy');
});


Route::prefix('admin/site/contatos')->middleware('auth')->name('admin.site.contatos.')->group(function () {
    Route::get('/', [SiteContatoController::class, 'index'])->name('index');
    Route::get('create', [SiteContatoController::class, 'create'])->name('create');
    Route::post('store', [SiteContatoController::class, 'store'])->name('store');
    Route::get('{contato}/edit', [SiteContatoController::class, 'edit'])->name('edit');
    Route::put('{contato}/update', [SiteContatoController::class, 'update'])->name('update');
    Route::delete('{contato}/destroy', [SiteContatoController::class, 'destroy'])->name('destroy');
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


Route::get('/test', function () {
    return Inertia::render('Test');
});

Route::post('/deploy', function () {
    $secret = "123"; // Use a mesma que foi usada no webhook do GitHub
    $signature = hash_hmac('sha256', file_get_contents("php://input"), $secret);

    if (hash_equals($signature, $_SERVER['HTTP_X_HUB_SIGNATURE'])) {
        // Se a assinatura do webhook for válida, puxe as atualizações mais recentes feito aqui
        shell_exec('cd .. && sudo git reset --hard HEAD && sudo git pull');
    }

    return ['status' => 'success'];
});

Route::get('/treino', [AgendaController::class, 'treino'])->name('treino');
Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
