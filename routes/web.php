<?php

use App\Livewire\Clientes\ListaCliente;
use App\Livewire\Equipamento;
use App\Livewire\Equipamentos\EquipamentoLista;
use App\Livewire\Monitor;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //route to equipamento-list livewire component
    Route::get('/equipamentos', EquipamentoLista::class)->name('equipamentos');
    Route::get('/clientes', ListaCliente::class)->name('clientes');


    Route::get('/monitor/{equipamento}', Monitor::class)->name('monitor');

    Route::get('equipamento/{numero_serie}', Equipamento::class)->name('equipamento');

});
