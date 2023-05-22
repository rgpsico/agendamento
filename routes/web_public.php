<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/login', function () {
    return view('auth.login');
});


Route::get('/', function () {
    return view('index');
});


Route::get('/register', [HomeController::class, 'register'])->name('home.register');
Route::get('/login', [HomeController::class, 'login'])->name('home.login');

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/{id}/profissional', [HomeController::class, 'show'])->name('home.show');
Route::get('/{id}/bokking', [HomeController::class, 'booking'])->name('home.booking');
Route::get('/{id}/checkout', [HomeController::class, 'checkout'])->name('home.checkout');
Route::get('/{id}/checkoutsucesso', [HomeController::class, 'checkoutSucesso'])->name('home.checkoutsucesso');


Route::prefix('public')->group(function () {
    Route::group(['prefix' => '/categoria'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/{id}/show', [CategoryController::class, 'show'])->name('category.show');
        Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/{id}/update', [CategoryController::class, 'update'])->name('category.update');
        Route::post('/post', [CategoryController::class, 'store'])->name('category.store');
        Route::delete('/{id}/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');
    });
});
