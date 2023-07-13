<?php

use App\Http\Controllers\AlunosController;
use App\Http\Controllers\Api\AulasControllerApi;
use App\Http\Controllers\Api\AuthControllerApi;
use App\Http\Controllers\Api\DiaDaSemanaControllerApi;
use App\Http\Controllers\Api\DisponibilidadeControllerApi;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\EmpresaControllerApi;
use App\Http\Controllers\Api\ProfessoresControllerApi;
use App\Http\Controllers\Api\ServicoControllerApi;
use App\Http\Controllers\Api\UserControllerApi;
use App\Http\Controllers\ModalidadeController;
use App\Http\Controllers\StripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

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


Route::resource('users', UserControllerApi::class);

Route::resource('aulas', AulasControllerApi::class);

Route::resource('disponibilidade', DisponibilidadeControllerApi::class);

Route::get('disponibilidade', [DisponibilidadeControllerApi::class, 'disponibilidade']);
Route::resource('dias', DiaDaSemanaControllerApi::class);

Route::post('/pagamento', [StripeController::class, 'treinoStripe'])->name('stripe.pagamento');


Route::delete('modalidade/{id}', [ModalidadeController::class, 'destroy'])->name('modalidade.destroy');


Route::post('/aluno/{id}/update', [AlunosController::class, 'update']);
