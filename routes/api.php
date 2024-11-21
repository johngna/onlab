<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

route::get('/clientes', [App\Http\Controllers\ApiController::class, 'clientes'])->name('api.clientes');
