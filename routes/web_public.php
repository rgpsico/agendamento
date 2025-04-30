<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;



Route::get('/', [SiteController::class, 'mostrarDominio']);

Route::get('/registrar_escola', [HomeController::class, 'registerProf'])->name('public.registerProf');
Route::get('/loginGoogle', [HomeController::class, 'handleGoogleCallback'])->name('public.googleAuth');


Route::get('/registrar_escola', [HomeController::class, 'registerProf'])->name('public.registerProf');
Route::get('/loginGoogle', [HomeController::class, 'handleGoogleCallback'])->name('public.googleAuth.prof');


Route::get('/registeraluno', function () {
    return view('auth.registerAluno');
});


Route::get('/login', function () {
    return view('auth.login');
});



Route::get('/registerProf', [HomeController::class, 'registerProf'])->name('home.registerProf');
Route::get('/registerAluno', [HomeController::class, 'registerAluno'])->name('home.registerAluno');

Route::get('/login', [HomeController::class, 'login'])->name('home.login');

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/{id}/empresa', [HomeController::class, 'show'])->name('home.show');
Route::get('/{id}/bokking', [HomeController::class, 'booking'])->name('home.booking');
Route::get('/{id}/checkout', [HomeController::class, 'checkout'])->name('home.checkout');
Route::get('/{user_id}/checkoutAuth', [HomeController::class, 'checkoutAuth'])->name('home.checkoutAuth')->middleware('auth');
Route::get('/{id}/checkoutsucesso', [HomeController::class, 'checkoutSucesso'])->name('home.checkoutsucesso');


Route::post('/agendar', [AgendaController::class, 'store'])->name('agendamento.pagamento');


Route::get('/notificacoes/usuario', [NotificationController::class, 'getUserNotifications']);
Route::get('/notificacoes/empresa', [NotificationController::class, 'getEmpresaNotifications']);
Route::post('/notificacao/lida/{id}', [NotificationController::class, 'markAsRead']);

Route::prefix('public')->group(function () {
    // Route::group(['prefix' => '/categoria'], function () {
    //     Route::get('/', [CategoryController::class, 'index'])->name('category.index');
    //     Route::get('/{id}/show', [CategoryController::class, 'show'])->name('category.show');
    //     Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
    //     Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    //     Route::post('/{id}/update', [CategoryController::class, 'update'])->name('category.update');
    //     Route::post('/post', [CategoryController::class, 'store'])->name('category.store');
    //     Route::delete('/{id}/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');
    // });
});
