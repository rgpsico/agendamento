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


Route::get('/disponibilidadedia', [DisponibilidadeServicoController::class, 'getDisponibilidade']);
Route::post('/disponibilidade/update', [DisponibilidadeServicoController::class, 'updateDisponibilidade']);
Route::post('/disponibilidade/reservar', [DisponibilidadeServicoController::class, 'reservarVaga']);

Route::get('/professores/media-avaliacoes', [AvaliacaoController::class, 'mediaAvaliacoes']);

Route::post('/send-whatsapp', [TwilioController::class, 'sendWhatsApp']);

Route::post('modalidade/{id}/update', [ModalidadeController::class, 'updateApi'])->name('modalidade.update');
Route::delete('modalidade/{id}/destroy', [ModalidadeController::class, 'destroy'])->name('modalidade.destroy');

Route::post('/aluno/store', [AlunosController::class, 'store']);
Route::post('/aluno/{id}/update', [AlunosControllerApi::class, 'update']);

Route::delete('/aluno/{id}/destroy/{professor_id}', [AlunosController::class, 'destroy']);

// routes/api.php
Route::get('professores/{id}/alunos', [EmpresaControllerApi::class, 'getAlunoByIdProfessor']);


Route::post('/treino/email', [AlunosControllerApi::class, 'treinoEmail']);


Route::post('/treino/service', [AlunosControllerApi::class, 'service']);
Route::get('/treino/cobrancas', [AlunosControllerApi::class, 'getCobrancas']);
