<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\UserController;

Route::middleware('api')->group(function () {
    // Rotas de CRUD para Users
    Route::resource('users', UserController::class);

    // Rotas de CRUD para Checklists
    Route::apiResource('checklists', ChecklistController::class);
});
