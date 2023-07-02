<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('alunoadmin')->group(function () {
    Route::get('/', 'AlunoadminController@index')->name('alunos.aulas');
    Route::get('/{id}/fotos', 'AlunoadminController@fotos')->name('alunos.fotos');
    Route::get('/{id}/perfil', 'AlunoadminController@perfil')->name('alunos.perfil');
});
