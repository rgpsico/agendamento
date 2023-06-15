<?php

use App\Http\Controllers\Api\AulasControllerApi;
use App\Http\Controllers\Api\DiaDaSemanaControllerApi;
use App\Http\Controllers\Api\DisponibilidadeControllerApi;
use App\Http\Controllers\Api\ProfessoresControllerApi;
use App\Http\Controllers\Api\UserControllerApi;
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


Route::resource('agendamento', AgendamentoControllerApi::class);

Route::resource('professor', ProfessoresControllerApi::class);

Route::resource('empresas', ProfessoresControllerApi::class);
Route::get('professor/{id}/aulas', [ProfessoresControllerApi::class, 'aulas']);


Route::resource('users', UserControllerApi::class);

Route::resource('aulas', AulasControllerApi::class);

Route::resource('disponibilidade', DisponibilidadeControllerApi::class);

Route::resource('dias', DiaDaSemanaControllerApi::class);
