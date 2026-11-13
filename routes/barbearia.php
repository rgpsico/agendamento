<?php

use App\Http\Controllers\Barbearia\AvisoController;
use App\Http\Controllers\Barbearia\BarbeiroController;
use App\Http\Controllers\Barbearia\CheckinController;
use App\Http\Controllers\Barbearia\ClienteController;
use App\Http\Controllers\Barbearia\EquipeController;
use App\Http\Controllers\Barbearia\PublicoController;
use Illuminate\Support\Facades\Route;

/*
 * Barbearia (nicho com a função fila_checkin): barbeiros da casa, check-in do cliente na fila e equipe do dono.
 * As rotas fixas vêm antes de /barbearia/{empresa} para não serem confundidas com o id da empresa.
 */
Route::prefix('barbearia')->name('barbearia.')->group(function () {
    Route::get('/', [PublicoController::class, 'index'])->name('index');

    // app do barbeiro (aviso no celular): service worker e manifesto
    Route::get('/sw.js', [AvisoController::class, 'serviceWorker'])->name('sw');
    Route::get('/manifest.webmanifest', [AvisoController::class, 'manifesto'])->name('manifesto');

    // cliente: cadastro rápido (nome, celular e senha) e login pelo celular
    Route::get('/entrar', [ClienteController::class, 'login'])->name('login');
    Route::post('/cadastro', [ClienteController::class, 'cadastro'])->middleware('throttle:cadastro')->name('cadastro');
    Route::post('/entrar', [ClienteController::class, 'entrar'])->middleware('throttle:login')->name('entrar');
    Route::post('/sair', [ClienteController::class, 'sair'])->name('sair');

    Route::middleware('auth')->group(function () {
        Route::get('/painel', [EquipeController::class, 'painel'])->name('painel');
        Route::get('/painel/equipe', [EquipeController::class, 'index'])->name('equipe');
        Route::post('/painel/equipe', [EquipeController::class, 'store'])->name('equipe.store');
        Route::post('/painel/equipe/{professor}/ativo', [EquipeController::class, 'ativo'])->name('equipe.ativo');
        Route::post('/painel/checkin', [EquipeController::class, 'checkin'])->name('checkin.config');
        Route::post('/painel/capa', [EquipeController::class, 'capa'])->name('capa');

        // barbeiro: fila ao vivo e agenda do dia
        Route::get('/painel/fila', [BarbeiroController::class, 'fila'])->name('painel.fila');
        Route::post('/painel/fila/{fila}/chamar', [BarbeiroController::class, 'chamar'])->name('painel.fila.chamar');
        Route::post('/painel/fila/{fila}/iniciar', [BarbeiroController::class, 'iniciar'])->name('painel.fila.iniciar');
        Route::post('/painel/fila/{fila}/finalizar', [BarbeiroController::class, 'finalizar'])->name('painel.fila.finalizar');
        Route::post('/painel/fila/{fila}/ausente', [BarbeiroController::class, 'ausente'])->name('painel.fila.ausente');

        // aviso no celular do barbeiro (Web Push)
        Route::post('/painel/aviso', [AvisoController::class, 'inscrever'])->name('painel.aviso');
        Route::delete('/painel/aviso', [AvisoController::class, 'cancelar'])->name('painel.aviso.cancelar');
        Route::post('/painel/aviso/testar', [AvisoController::class, 'testar'])->name('painel.aviso.testar');

        Route::get('/fila/{fila}', [CheckinController::class, 'fila'])->name('fila');
        Route::post('/fila/{fila}/desistir', [CheckinController::class, 'desistir'])->name('fila.desistir');
    });

    Route::middleware('empresa.recurso:fila_checkin')->prefix('{empresa}')->where(['empresa' => '[0-9]+'])->group(function () {
        Route::get('/', [PublicoController::class, 'show'])->name('show');
        Route::get('/c/{token}', [PublicoController::class, 'qr'])->name('qr');
        Route::get('/barbeiro/{professor}', [PublicoController::class, 'barbeiro'])->name('barbeiro');
        Route::post('/barbeiro/{professor}/checkin', [CheckinController::class, 'checkin'])->middleware('auth')->name('checkin');
    });
});
