<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\UserController;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout/{user}', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Rotas de CRUD para Users
Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
