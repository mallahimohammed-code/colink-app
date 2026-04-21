<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\CompetenceController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/competences', [CompetenceController::class, 'index']);
Route::get('/offres', [OffreController::class, 'index']);
Route::get('/offres/{offre}', [OffreController::class, 'show']);

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

        Route::post('/offres/{offre}/candidater', [CandidatureController::class, 'store']);
        Route::get('/mes-candidatures', [CandidatureController::class, 'mine']);
    });

    Route::middleware('role:recruteur')->group(function () {
        Route::post('/offres', [OffreController::class, 'store']);
        Route::put('/offres/{offre}', [OffreController::class, 'update']);
        Route::delete('/offres/{offre}', [OffreController::class, 'destroy']);

        Route::get('/offres/{offre}/candidatures', [CandidatureController::class, 'forOffre']);
        Route::patch('/candidatures/{candidature}/statut', [CandidatureController::class, 'updateStatut']);
    });

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'users']);
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser']);
        Route::patch('/offres/{offre}', [AdminController::class, 'toggleOffre']);
    });
});
