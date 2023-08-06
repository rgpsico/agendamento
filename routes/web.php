<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\SocialLiteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')->middleware(['check_user_authenticated'])->group(function () {
});


Route::get('/test', function () {
    return Inertia::render('Test');
});

Route::post('/deploy', function () {
    $secret = "157121214"; // Use a mesma que foi usada no webhook do GitHub
    $signature = hash_hmac('sha256', file_get_contents("php://input"), $secret);

    if (hash_equals($signature, $_SERVER['HTTP_X_HUB_SIGNATURE'])) {
        // Se a assinatura do webhook for válida, puxe as atualizações mais recentes
        shell_exec('cd .. && sudo git reset --hard HEAD && sudo git pull');
    }

    return ['status' => 'success'];
});

Route::get('/treino', [AgendaController::class, 'treino'])->name('treino');
Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


Route::get('/google', [SocialLiteController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/googleh', [SocialLiteController::class, 'handleGoogleCallback'])->name('handle.google');
