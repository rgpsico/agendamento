<?php

use App\Http\Controllers\AlunosController;
use App\Http\Controllers\Api\AgendamentoControllerApi;
use App\Http\Controllers\Api\AlunosControllerApi;
use App\Http\Controllers\Api\AulasControllerApi;
use App\Http\Controllers\Api\AuthControllerApi;
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
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

Route::post('/events', [GoogleCalendarController::class, 'createEvent']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->get('/teste', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthControllerApi::class, 'login']);

Route::resource('agendamento', AgendamentoControllerApi::class);



Route::resource('professor', ProfessoresControllerApi::class);

Route::resource('empresas', EmpresaControllerApi::class);
Route::get('search/empresa', [EmpresaControllerApi::class, 'search'])->name('empresa.search');


Route::resource('servicos', ServicoControllerApi::class);

Route::get('professor/{id}/aulas', [ProfessoresControllerApi::class, 'aulas']);

Route::post('pagamentoApi', [PagamentoController::class, 'pagamentoStripe']);


Route::resource('users', UserControllerApi::class);
Route::post('transacao', [UserControllerApi::class, 'transacao']);

Route::post('googleAuth', [UserControllerApi::class, 'googleAuth']);

Route::resource('aulas', AulasControllerApi::class);

Route::resource('disponibilidade', DisponibilidadeControllerApi::class);

Route::get('disponibilidade', [DisponibilidadeControllerApi::class, 'disponibilidade']);

Route::resource('dias', DiaDaSemanaControllerApi::class);


use Twilio\Rest\Client;

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

Route::post('/web-hook', [PixQrController::class, 'handleAsaasWebhook']);

Route::post('/pix-qrcode', [PixQrController::class, 'generatePixQrCode']);
Route::post('/customers', [PixQrController::class, 'createCustomer']);
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

Route::middleware('auth:sanctum')->post('/empresa/update', [EmpresaControllerApi::class, 'update']);


Route::get('/disponibilidadedia', [DisponibilidadeServicoController::class, 'getDisponibilidade']);
Route::post('/disponibilidade/update', [DisponibilidadeServicoController::class, 'updateDisponibilidade']);
Route::post('/disponibilidade/reservar', [DisponibilidadeServicoController::class, 'reservarVaga']);

Route::get('/professores/media-avaliacoes', [AvaliacaoController::class, 'mediaAvaliacoes']);

Route::post('/send-whatsapp', [TwilioController::class, 'sendWhatsApp']);

Route::post('modalidade/{id}/update', [ModalidadeController::class, 'updateApi'])->name('modalidade.update');
Route::delete('modalidade/{id}/destroy', [ModalidadeController::class, 'destroy'])->name('modalidade.destroy');

Route::post('/aluno/store', [AlunosController::class, 'store']);
Route::post('/aluno/{id}/update', [AlunosControllerApi::class, 'update']);
Route::post('/aluno/{id}/update-endereco', [AlunosControllerApi::class, 'updateEndereco']);

Route::delete('/aluno/{id}/destroy/{professor_id}', [AlunosController::class, 'destroy']);

// routes/api.php
Route::get('professores/{id}/alunos', [EmpresaControllerApi::class, 'getAlunoByIdProfessor']);


Route::post('/treino/email', [AlunosControllerApi::class, 'treinoEmail']);


Route::post('/treino/service', [AlunosControllerApi::class, 'service']);
Route::get('/treino/cobrancas', [AlunosControllerApi::class, 'getCobrancas']);

Route::post('/admin/asaas/limpar-sandbox', [PagamentoController::class, 'deleteAllPayments']);
Route::post('/admin/asaas/getwallet', [PagamentoController::class, 'getCustomerWallet']);


Route::get('/asaas/customer/{customerId}', [PagamentoController::class, 'getCustomer']);

Route::post('/asaas/getOrCreateAsaasCustome', [PagamentoController::class, 'getOrCreateAsaasCustomer']);

Route::prefix('empresa/pagamento')->group(function () {
    Route::get('/', [PagamentoController::class, 'index'])->name('empresa.pagamento.index');
    Route::get('/create', [PagamentoController::class, 'create'])->name('empresa.pagamento.create');
    Route::post('/store', [PagamentoController::class, 'store'])->name('empresa.pagamento.store');
    Route::get('/{id}/edit', [PagamentoController::class, 'edit'])->name('empresa.pagamento.edit');
    Route::put('/{id}', [PagamentoController::class, 'update'])->name('empresa.pagamento.update');
    // Route::post('/asaas', [PagamentoController::class, 'pagamentoAsaas'])->name('empresa.pagamento.asaas');
});


Route::middleware('auth')->post('/subcontas', [ProfessoresAsaasController::class, 'createSubaccount'])->name('criar-subconta-professor');