<?php

use Illuminate\Support\Facades\Route;



Route::prefix('admin')->middleware(['check_user_authenticated'])->group(function () {
});
