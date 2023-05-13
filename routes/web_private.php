<?php

use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\EmpresaController;
use Illuminate\Support\Facades\Route;


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
