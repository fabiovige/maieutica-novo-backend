<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout/{user}', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Rotas de CRUD para Users
Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
