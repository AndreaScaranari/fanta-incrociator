<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeamController;

Route::get('/', function () {
    return view('welcome');
});

// DEBUG ROUTES
Route::get('/debug/teams', [TeamController::class, 'index']);
Route::get('/debug/teams/{team}', [TeamController::class, 'show']);
