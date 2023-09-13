<?php



use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AlunosController;

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ModalidadeController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('usuario')->group(function () {
    Route::post('/', [UserController::class, 'store'])->name('user.store');
    Route::get('/', [UserController::class, 'logout'])->name('user.logout');
    Route::post('/login', [UserController::class, 'login'])->name('user.login');
});



Route::prefix('cliente')->middleware('auth')->group(function () {
    Route::group(['prefix' => '/servicos'], function () {
        Route::get('/', [ServicoController::class, 'index'])->name('admin.servico.index');
        Route::get('/{id}/servico', [ServicoController::class, 'show'])->name('admin.servico.show');
        Route::get('/create', [ServicoController::class, 'create'])->name('admin.servico.create');
        Route::get('/{id}/edit', [ServicoController::class, 'edit'])->name('admin.servico.edit');
        Route::post('/store', [ServicoController::class, 'store'])->name('admin.servico.store');
        Route::delete('/{id}/delete', [ServicoController::class, 'destroy'])->name('admin.servico.destroy');
        Route::post('/{id}/update', [ServicoController::class, 'update'])->name('admin.servico.update');
    });

    Route::group(['prefix' => '/empresa'], function () {
        Route::get('/', [EmpresaController::class, 'index'])->name('empresa.index');
        Route::get('/{id}/show', [EmpresaController::class, 'show'])->name('empresa.show');
        Route::get('/create', [EmpresaController::class, 'create'])->name('empresa.create');
        Route::get('/{id}/edit', [EmpresaController::class, 'edit'])->name('empresa.edit');
        Route::post('update', [EmpresaController::class, 'update'])->name('empresa.update');
        Route::post('/post', [EmpresaController::class, 'store'])->name('empresa.store');
        Route::post('/{userId}/endereco_empresa', [EmpresaController::class, 'endereco_update'])->name('empresa.update_endereco');
        Route::get('/disponibilidade', [EmpresaController::class, 'disponibilidade'])->name('empresa.disponibilidade');
        Route::post('/disponibilidade', [EmpresaController::class, 'cadastrarDisponibilidade'])->name('empresa.disponibilidade.store');

        Route::get('/', [EmpresaController::class, 'index'])->name('agenda.index');
        Route::get('/create', [EmpresaController::class, 'create'])->name('agenda.create');
        Route::post('/agenda/store', [EmpresaController::class, 'agendatore'])->name('empresa.agenda.store');
        Route::put('/{id}/agenda/store', [EmpresaController::class, 'agendaUpdate'])->name('empresa.agenda.update');
        Route::post('/{userId}/profile', [EmpresaController::class, 'profile'])->name('empresa.profile');
        Route::delete('/{id}/destroy', [EmpresaController::class, 'destroy'])->name('empresa.destroy');
        Route::get('/{userId}/config', [EmpresaController::class, 'configuracao'])->name('empresa.configuracao');
        Route::get('/fotos', [EmpresaController::class, 'fotos'])->name('empresa.fotos');
        Route::get('/{userId}/endereco_empresa', [EmpresaController::class, 'endereco'])->name('empresa.endereco');
        Route::post('/uploadEmpresa', [EmpresaController::class, 'uploadImage'])->name('empresa.upload');
        Route::delete('/{id}/excluirImagens', [EmpresaController::class, 'destroy'])->name('gallery.destroy');


        Route::group(['prefix' => '/alunos'], function () {
            Route::get('/', [AlunosController::class, 'index'])->name('alunos.index');
            Route::get('/{id}/show', [AlunosController::class, 'show'])->name('alunos.show');
            Route::get('/create', [AlunosController::class, 'create'])->name('alunos.create');
            Route::get('/{id}/edit', [AlunosController::class, 'edit'])->name('aluno.edit');
            Route::post('/{id}/update', [AlunosController::class, 'update'])->name('alunos.update');
            Route::post('/post', [AlunosController::class, 'store'])->name('alunos.store');
            Route::delete('/{id}/destroy', [AlunosController::class, 'destroy'])->name('alunos.destroy');
            Route::get('/config', [AlunosController::class, 'configuracao'])->name('alunos.configuracao');
            Route::post('/uploadAluno', [AlunosController::class, 'uploadImage'])->name('aluno.upload');
        });

        Route::group(['prefix' => '/dashboard'], function () {
            Route::get('/', [EmpresaController::class, 'dashboard'])->name('cliente.dashboard');
        });

        Route::group(['prefix' => '/agenda'], function () {
            Route::get('/', [AgendaController::class, 'index'])->name('agenda.index');
            Route::get('/calendario', [AgendaController::class, 'calendario'])->name('agenda.calendario');
            Route::get('/{id}/show', [AgendaController::class, 'show'])->name('agenda.show');
            Route::get('/create', [AgendaController::class, 'create'])->name('agenda.create');
            Route::get('/{id}/edit', [AgendaController::class, 'edit'])->name('agenda.edit');
            Route::get('/form', [AgendaController::class, 'form'])->name('agenda.form ');
            Route::post('/{id}/update', [AgendaController::class, 'update'])->name('agenda.update');
            Route::post('/post', [AgendaController::class, 'store'])->name('agenda.store');
            Route::delete('/{id}/destroy', [AgendaController::class, 'destroy'])->name('agenda.destroy');
        });


        Route::group(['prefix' => '/modalidade'], function () {
            Route::get('/', [ModalidadeController::class, 'index'])->name('modalidade.index');
            Route::get('/create', [ModalidadeController::class, 'create'])->name('modalidade.create');
            Route::post('/{id}/update', [ModalidadeController::class, 'update'])->name('modalidade.update');
            Route::post('/{id}/show', [ModalidadeController::class, 'show'])->name('modalidade.show');
            Route::post('store', [ModalidadeController::class, 'store'])->name('modalidade.store');
            Route::delete('{id}/destroy', [ModalidadeController::class, 'destroy'])->name('admin.modalidade.destroy');
        });

        Route::get('{id}/profile', [UserController::class, 'profile'])->name('usuario.profile');
    });
});



Route::prefix('pagamento')->middleware('auth')->group(function () {
    Route::post('/stripe', [PagamentoController::class, 'pagamentoStripe'])->name('pagamento.stripe');
});



Route::prefix('admin')->middleware('auth')->group(function () {
    Route::group(['prefix' => '/manager'], function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    });
});
