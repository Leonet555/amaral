<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AthleteFollowController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\AthletePostController;
use App\Http\Controllers\Api\EventLikeController;
use App\Http\Controllers\Api\UploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});

Route::get('/highlights', [EventController::class, 'highlights']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events-for-athlete', [EventController::class, 'indexForAthlete']);
    Route::get('/events/{event}', [EventController::class, 'show']);

    Route::middleware('role:organizer')->group(function () {
        Route::get('/organizer/dashboard', [EventController::class, 'organizerDashboard']);
        Route::post('/upload/banner', [UploadController::class, 'banner']);
        Route::post('/events', [EventController::class, 'store']);
        Route::put('/events/{event}', [EventController::class, 'update']);
        Route::delete('/events/{event}', [EventController::class, 'destroy']);
        Route::post('/events/{event}/open-registration', [EventController::class, 'openRegistration']);
        Route::post('/events/{event}/close-registration', [EventController::class, 'closeRegistration']);
        Route::post('/events/{event}/finalize', [EventController::class, 'finalize']);

        Route::post('/events/{event}/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
        Route::get('/categories/{category}/registrations', [RegistrationController::class, 'indexByCategory']);
        Route::patch('/registrations/{registration}', [RegistrationController::class, 'updatePaymentStatus']);
    });

    Route::get('/events/{event}/categories', [CategoryController::class, 'indexByEvent']);
    Route::get('/events/{event}/comments', [EventLikeController::class, 'comments']);
    Route::post('/events/{event}/comments', [EventLikeController::class, 'storeComment']);
    Route::get('/events/{event}/like-status', [EventLikeController::class, 'likeStatus']);
    Route::post('/events/{event}/like', [EventLikeController::class, 'like']);
    Route::delete('/events/{event}/like', [EventLikeController::class, 'unlike']);
    Route::get('/categories/{category}/matches', [MatchController::class, 'indexByCategory']);
    // Gerar chave e registrar resultado: autorização por dono do evento (controller), não por role
    Route::post('/categories/{category}/generate-bracket', [MatchController::class, 'generateBracket']);
    Route::post('/matches/{match}/result', [MatchController::class, 'registerResult']);

    Route::get('/athlete-dashboard', [RegistrationController::class, 'athleteDashboard']);

    Route::post('/upload/athlete-photo', [UploadController::class, 'athletePhoto']);
    Route::post('/upload/post-media', [UploadController::class, 'postMedia']);

    // Perfil do atleta: qualquer usuário logado pode criar/editar o próprio perfil (ex.: organizador na "Visão do atleta")
    Route::get('/athlete-profile', [RegistrationController::class, 'showAthleteProfile']);
    Route::post('/athlete-profile', [RegistrationController::class, 'storeAthleteProfile']);
    Route::put('/athlete-profile', [RegistrationController::class, 'updateAthleteProfile']);

    Route::middleware('role:athlete')->group(function () {
        Route::post('/registrations', [RegistrationController::class, 'store']);
        Route::get('/registrations', [RegistrationController::class, 'indexMyRegistrations']);
    });

    // Busca unificada: atletas e equipes (para seguir / se afiliar)
    Route::get('/search', [SearchController::class, 'index']);

    // Rede social: seguir atletas
    Route::get('/athlete-follows/following', [AthleteFollowController::class, 'following']);
    Route::get('/athlete-follows/followers', [AthleteFollowController::class, 'followers']);
    Route::get('/athlete-follows/discover', [AthleteFollowController::class, 'discover']);
    Route::get('/athletes/{athlete}', [AthleteFollowController::class, 'show'])->where('athlete', '[0-9]+');
    Route::post('/athletes/{athlete}/follow', [AthleteFollowController::class, 'follow'])->where('athlete', '[0-9]+');
    Route::delete('/athletes/{athlete}/unfollow', [AthleteFollowController::class, 'unfollow'])->where('athlete', '[0-9]+');

    // Publicações do atleta (fotos/vídeos no perfil)
    Route::get('/feed', [AthletePostController::class, 'feed']);
    Route::get('/feed/discover', [AthletePostController::class, 'feedDiscover']);
    Route::get('/athlete-posts', [AthletePostController::class, 'index']);
    Route::post('/athlete-posts', [AthletePostController::class, 'store']);
    Route::delete('/athlete-posts/{athletePost}', [AthletePostController::class, 'destroy']);

    // Equipes
    Route::get('/teams', [TeamController::class, 'index']);
    Route::post('/teams', [TeamController::class, 'store']);
    Route::get('/teams/{team}', [TeamController::class, 'show']);
    Route::put('/teams/{team}', [TeamController::class, 'update']);
    Route::post('/teams/{team}/join', [TeamController::class, 'join']);
    Route::post('/teams/{team}/leave', [TeamController::class, 'leave']);
    Route::delete('/teams/{team}/members/{athlete}', [TeamController::class, 'removeMember']);
});
