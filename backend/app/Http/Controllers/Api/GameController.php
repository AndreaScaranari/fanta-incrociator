<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    /**
     * GET /api/games
     * Recupera tutte le partite con le relazioni homeTeam e awayTeam
     */
    public function index(Request $request): JsonResponse
    {
        $season = $request->query('season', 2025);

        $games = Game::with(['homeTeam', 'awayTeam'])
            ->where('season', $season)
            ->orderBy('giornata')
            ->orderBy('data_partita')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $games,
            'count' => $games->count()
        ]);
    }
}
