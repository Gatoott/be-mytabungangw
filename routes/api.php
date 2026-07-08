<?php

use App\Http\Controllers\TabunganController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tabungan', [TabunganController::class, 'store']);
    Route::put('/tabungan/update/{id}', [TabunganController::class, 'update']);
    Route::put('/tabungan/transaksi/{id}', [TabunganController::class, 'transaksi']);

});

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'regis']);