<?php

use App\Http\Controllers\Admin\AcessoController;
use App\Http\Controllers\Admin\EstoqueController;
use App\Http\Controllers\Admin\PainelController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::view('login', 'admin.login')->name('login');
    Route::post('login', [AcessoController::class, 'entrar'])->middleware('throttle:5,1')->name('admin.entrar');
    Route::middleware('auth')->name('admin.')->group(function () {
        Route::get('/', [PainelController::class, 'dashboard'])->name('dashboard');
        Route::post('sair', [AcessoController::class, 'sair'])->name('sair');
        Route::resource('veiculos', EstoqueController::class)->parameters(['veiculos' => 'veiculo'])->except('show');
        Route::get('mensagens', [PainelController::class, 'mensagens'])->name('mensagens');
        Route::post('mensagens/{id}/lida', [PainelController::class, 'ler'])->whereNumber('id')->name('ler');
        Route::get('configuracoes', [PainelController::class, 'configuracoes'])->name('config');
        Route::put('configuracoes/{aba}', [PainelController::class, 'salvar'])->name('config.salvar');
    });
});
