<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AlunosController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('usuario')->group(function () {
    Route::post('/', [UserController::class, 'store'])->name('user.store');
});

Route::prefix('admin')->group(function () {
    Route::group(['prefix' => '/dashboard'], function () {
        Route::get('/', [DashBoardController::class, 'dashboard'])->name('dashboard');
    });

    Route::group(['prefix' => '/empresa'], function () {
        Route::get('/', [EmpresaController::class, 'index'])->name('empresa.index');
        Route::get('/{id}/show', [EmpresaController::class, 'show'])->name('empresa.show');
        Route::get('/create', [EmpresaController::class, 'create'])->name('empresa.create');
        Route::get('/{id}/edit', [EmpresaController::class, 'edit'])->name('empresa.edit');
        Route::post('/{id}/update', [EmpresaController::class, 'update'])->name('empresa.update');
        Route::post('/post', [EmpresaController::class, 'store'])->name('empresa.store');
        Route::delete('/{id}/destroy', [EmpresaController::class, 'destroy'])->name('empresa.destroy');
        Route::get('/config', [EmpresaController::class, 'configuracao'])->name('empresa.configuracao');
    });
});

Route::prefix('admin')->group(function () {
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
