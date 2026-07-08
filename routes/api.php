<?php

use App\Http\Controllers\HistoriController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tabungan', [TabunganController::class, 'index']);
    Route::post('/tabungan', [TabunganController::class, 'store']);
    Route::put('/tabungan/update/{id}', [TabunganController::class, 'update']);
    Route::put('/tabungan/transaksi/{id}', [TabunganController::class, 'transaksi']);
    Route::delete('/tabungan/destroy/{id}', [TabunganController::class, 'destroy']);

    Route::get('/histori', [HistoriController::class, 'index']);
});

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'regis']);