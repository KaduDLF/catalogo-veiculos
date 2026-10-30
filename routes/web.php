<?php

use App\Http\Controllers\ContatoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VeiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::view('/empresa', 'pages.empresa')->name('empresa');
Route::view('/contato', 'pages.contato')->name('contato');
Route::post('/contato', [ContatoController::class, 'store'])->middleware('throttle:5,1')->name('contato.store');
Route::get('/veiculos', [VeiculoController::class, 'index'])->name('veiculos.index');
Route::get('/veiculos/{veiculo}', [VeiculoController::class, 'show'])->whereNumber('veiculo')->name('veiculos.show');
