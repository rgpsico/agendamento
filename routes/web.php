<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')->middleware(['check_user_authenticated'])->group(function () {
});


Route::get('/test', function () {
    return Inertia::render('Test');
});
