<?php

use App\Http\Controllers\AlunosController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ProfessorController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->group(function () {

    Route::group(['prefix' => '/alunos'], function () {
        Route::get('/', [AlunosController::class, 'index'])->name('alunos.index');
        Route::get('/{id}/show', [AlunosController::class, 'show'])->name('alunos.show');
        Route::get('/create', [AlunosController::class, 'create'])->name('alunos.create');
        Route::get('/{id}/edit', [AlunosController::class, 'edit'])->name('alunos.edit');
        Route::post('/{id}/update', [AlunosController::class, 'update'])->name('alunos.update');
        Route::post('/post', [AlunosController::class, 'store'])->name('alunos.store');
        Route::delete('/{id}/destroy', [AlunosController::class, 'destroy'])->name('alunos.destroy');
    });
});

    // Route::group(['prefix' => '/professores'], function () {
    //     Route::get('/', [ProfessorController::class, 'index'])->name('professores.index');
    //     Route::get('/{id}/show', [ProfessorController::class, 'show'])->name('professores.show');
    //     Route::get('/create', [ProfessorController::class, 'create'])->name('professores.create');
    //     Route::get('/{id}/edit', [ProfessorController::class, 'edit'])->name('professores.edit');
    //     Route::post('/{id}/update', [ProfessorController::class, 'update'])->name('professores.update');
    //     Route::post('/post', [ProfessorController::class, 'store'])->name('professores.store');
    //     Route::delete('/{id}/destroy', [ProfessorController::class, 'destroy'])->name('professores.destroy');
    // });


    // Route::group(['prefix' => '/dashboard'], function () {
    //     Route::get('/', [DashBoardController::class, 'index'])->name('dashboard.index');
    //     Route::get('/{id}/show', [DashBoardController::class, 'show'])->name('dashboard.show');
    //     Route::get('/create', [DashBoardController::class, 'create'])->name('dashboard.create');
    //     Route::get('/{id}/edit', [DashBoardController::class, 'edit'])->name('dashboard.edit');
    //     Route::post('/{id}/update', [DashBoardController::class, 'update'])->name('dashboard.update');
    //     Route::post('/post', [DashBoardController::class, 'store'])->name('dashboard.store');
    //     Route::delete('/{id}/destroy', [DashBoardController::class, 'destroy'])->name('dashboard.destroy');
    // });

    // Route::group(['prefix' => '/empresa'], function () {
    //     Route::get('/', [EmpresaController::class, 'index'])->name('empresa.index');
    //     Route::get('/{id}/show', [EmpresaController::class, 'show'])->name('empresa.show');
    //     Route::get('/create', [EmpresaController::class, 'create'])->name('empresa.create');
    //     Route::get('/{id}/edit', [EmpresaController::class, 'edit'])->name('empresa.edit');
    //     Route::post('/{id}/update', [EmpresaController::class, 'update'])->name('empresa.update');
    //     Route::post('/post', [EmpresaController::class, 'store'])->name('empresa.store');
    //     Route::delete('/{id}/destroy', [EmpresaController::class, 'destroy'])->name('empresa.destroy');
    // });
