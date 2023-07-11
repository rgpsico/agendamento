<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AlunosController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('usuario')->group(function () {
    Route::post('/', [UserController::class, 'store'])->name('user.store');
    Route::get('/', [UserController::class, 'logout'])->name('user.logout');
    Route::post('/login', [UserController::class, 'login'])->name('user.login');
});

Route::prefix('pagamento')->middleware('auth')->group(function () {
    Route::post('/stripe', [PagamentoController::class, 'pagamentoStripe'])->name('pagamento.stripe');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::group(['prefix' => '/servicos'], function () {
        Route::get('/', [ServicoController::class, 'index'])->name('admin.servico.index');
        Route::get('/{id}/servico', [ServicoController::class, 'show'])->name('admin.servico.show');
        Route::get('/create', [ServicoController::class, 'create'])->name('admin.servico.create');
        Route::get('/{id}/edit', [ServicoController::class, 'edit'])->name('admin.servico.edit');
        Route::post('/store', [ServicoController::class, 'store'])->name('admin.servico.store');
        Route::delete('/{id}/delete', [ServicoController::class, 'destroy'])->name('admin.servico.destroy');
        Route::post('/{id}/update', [ServicoController::class, 'update'])->name('admin.servico.update');
    });

    Route::get('/', [EmpresaController::class, 'index'])->name('agenda.index');

    Route::group(['prefix' => '/dashboard'], function () {
        Route::get('/', [DashBoardController::class, 'dashboard'])->name('dashboard');
    });

    Route::group(['prefix' => '/empresa'], function () {
        Route::get('/', [EmpresaController::class, 'index'])->name('empresa.index');
        Route::get('/{id}/show', [EmpresaController::class, 'show'])->name('empresa.show');
        Route::get('/create', [EmpresaController::class, 'create'])->name('empresa.create');
        Route::get('/{id}/edit', [EmpresaController::class, 'edit'])->name('empresa.edit');
        Route::post('update', [EmpresaController::class, 'update'])->name('empresa.update');
        Route::post('/post', [EmpresaController::class, 'store'])->name('empresa.store');
        Route::post('/{userId}/endereco_empresa', [EmpresaController::class, 'endereco_update'])->name('empresa.update_endereco');
        Route::get('/disponibilidade', [EmpresaController::class, 'disponibilidade'])->name('empresa.disponibilidade');
        Route::post('/disponibilidade', [EmpresaController::class, 'cadastrarDisponibilidade'])->name('empresa.disponibilidade.store');

        Route::delete('/{id}/destroy', [EmpresaController::class, 'destroy'])->name('empresa.destroy');
        Route::get('/{userId}/config', [EmpresaController::class, 'configuracao'])->name('empresa.configuracao');
        Route::get('/{userId}/fotos', [EmpresaController::class, 'fotos'])->name('empresa.fotos');
        Route::get('/{userId}/endereco_empresa', [EmpresaController::class, 'endereco'])->name('empresa.endereco');
        Route::post('/uploadEmpresa', [EmpresaController::class, 'uploadImage'])->name('empresa.upload');
        Route::delete('/{id}/excluirImagens', [EmpresaController::class, 'destroy'])->name('gallery.destroy');
    });

    Route::group(['prefix' => '/escola'], function () {
        Route::group(['prefix' => '/alunos'], function () {
            Route::get('/', [AlunosController::class, 'index'])->name('alunos.index');
            Route::get('/{id}/show', [AlunosController::class, 'show'])->name('alunos.show');
            Route::get('/create', [AlunosController::class, 'create'])->name('alunos.create');
            Route::get('/{id}/edit', [AlunosController::class, 'edit'])->name('alunos.edit');
            Route::post('/{id}/update', [AlunosController::class, 'update'])->name('alunos.update');
            Route::post('/post', [AlunosController::class, 'store'])->name('alunos.store');
            Route::delete('/{id}/destroy', [AlunosController::class, 'destroy'])->name('alunos.destroy');
            Route::get('/config', [AlunosController::class, 'configuracao'])->name('alunos.configuracao');
        });

        Route::group(['prefix' => '/dashboard'], function () {
            Route::get('/', [DashBoardController::class, 'dashboardAlunos'])->name('escola.dashboard');
        });

        Route::group(['prefix' => '/agenda'], function () {
            Route::get('/', [AgendaController::class, 'index'])->name('agenda.index');
            Route::get('/{id}/show', [AgendaController::class, 'show'])->name('agenda.show');
            Route::get('/create', [AgendaController::class, 'create'])->name('agenda.create');
            Route::get('/{id}/edit', [AgendaController::class, 'edit'])->name('agenda.edit');
            Route::get('/form', [AgendaController::class, 'form'])->name('agenda.form ');
            Route::post('/{id}/update', [AgendaController::class, 'update'])->name('agenda.update');
            Route::post('/post', [AgendaController::class, 'store'])->name('agenda.store');
            Route::delete('/{id}/destroy', [AgendaController::class, 'destroy'])->name('agenda.destroy');
        });
    });
});


Route::prefix('admin')->middleware('auth')->group(function () {
    Route::group(['prefix' => '/manager'], function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    });
});
