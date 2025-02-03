<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SocialLiteController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\AvaliacaoController;
use Illuminate\Support\Facades\Route;
use Spatie\GoogleCalendar\Event;
use Inertia\Inertia;

use App\Http\Controllers\GoogleController;



Route::get('/google/prof/redirect', [SocialLiteController::class, 'professorRedirectToGoogle'])->name('prof.login.google');
Route::get('/google/prof/callback', [SocialLiteController::class, 'professorGoogleCallback'])->name('prof.handle.google');


Route::get('/google/aluno/redirect', [SocialLiteController::class, 'alunoRedirectToGoogle'])->name('aluno.googleAuth.redirect');
Route::get('/google/callback', [SocialLiteController::class, 'alunoGoogleCallback'])->name('aluno.googleAuth.handle');




// Route::get('/google-calendar/auth', [GoogleCalendarController::class, 'authenticate'])->name('google.calendar.auth');
// Route::get('/google-calendar/events', [GoogleCalendarController::class, 'listEvents'])->name('google.calendar.events');





Route::get('/google-calendar/auth', [GoogleCalendarController::class, 'authenticate'])->name('google.calendar.auth');
Route::get('/google-calendar/callback', [GoogleCalendarController::class, 'authenticate']);
Route::get('/google-calendar/events', [GoogleCalendarController::class, 'listEvents'])->name('google.calendar.events');
Route::get('/google-calendar/create-event', [GoogleCalendarController::class, 'createEvent']);

Route::get('/events', [GoogleCalendarController::class, 'getAllEvents'])->name('google.calendar.events');
Route::get('/google-calendar/create-event', [GoogleCalendarController::class, 'createEvent']);



Route::prefix('admin')->middleware(['check_user_authenticated'])->group(function () {
});

Route::post('/avaliacao/store', [AvaliacaoController::class, 'store'])->name('empresa.avaliacao.store');


Route::get('/test', function () {
    return Inertia::render('Test');
});

Route::post('/deploy', function () {
    $secret = "123"; // Use a mesma que foi usada no webhook do GitHub
    $signature = hash_hmac('sha256', file_get_contents("php://input"), $secret);

    if (hash_equals($signature, $_SERVER['HTTP_X_HUB_SIGNATURE'])) {
        // Se a assinatura do webhook for válida, puxe as atualizações mais recentes feito aqui
        shell_exec('cd .. && sudo git reset --hard HEAD && sudo git pull');
    }

    return ['status' => 'success'];
});

Route::get('/treino', [AgendaController::class, 'treino'])->name('treino');
Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
