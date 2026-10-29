<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\VeiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/veiculos', [VeiculoController::class, 'index'])->name('veiculos.index');
Route::get('/veiculos/{veiculo}', [VeiculoController::class, 'show'])->whereNumber('veiculo')->name('veiculos.show');
