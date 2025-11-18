<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\SettingController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('teams')->group(function () {
    // GET /api/teams - Lista tutte le squadre
    Route::get('/', [TeamController::class, 'index'])->name('teams.index');

    // POST /api/teams - Crea nuova squadra
    Route::post('/', [TeamController::class, 'store'])->name('teams.store');

    // POST /api/teams/reorder - Riordina multiple squadre
    Route::post('/reorder', [TeamController::class, 'reorder'])->name('teams.reorder');

    // Settings
    Route::get('/current-giornata', [SettingController::class, 'getCurrentGiornata']);

    // GET /api/teams/{team} - Dettaglio singola squadra
    Route::get('/{team}', [TeamController::class, 'show'])->name('teams.show');

    // PUT /api/teams/{team}/tier - Modifica tier
    Route::put('/{team}/tier', [TeamController::class, 'updateTier'])->name('teams.updateTier');

    // DELETE /api/teams/{team} - Elimina squadra
    Route::delete('/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
});
