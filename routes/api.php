<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('role:candidat')->group(function () {
        Route::post('/profil', [ProfilController::class, 'store']);
        Route::get('/profil', [ProfilController::class, 'show']);
        Route::put('/profil', [ProfilController::class, 'update']);
        Route::post('/profil/competences', [ProfilController::class, 'attachCompetence']);
        Route::delete('/profil/competences/{competence}', [ProfilController::class, 'detachCompetence']);
    });
});
