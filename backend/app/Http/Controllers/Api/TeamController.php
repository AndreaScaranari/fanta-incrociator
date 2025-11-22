<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

class TeamController extends Controller
{
    /**
     * GET /api/teams
     * Recupera tutte le squadre, opzionalmente ordinate per tier
     */
    public function index(Request $request): JsonResponse
    {
        $query = Team::query();

        // Parametro opzionale per ordinamento
        $sortBy = $request->query('sort', 'tier');
        $order = $request->query('order', 'asc');

        if (in_array($sortBy, ['tier', 'nome', 'id'])) {
            $query->orderBy($sortBy, $order);
        }

        // ordine secondario per nome in caso di pari tier
        $query->orderBy('nome', 'asc');

        $teams = $query->get();

        return response()->json([
            'success' => true,
            'data' => $teams,
            'count' => $teams->count(),
        ]);
    }

    /**
     * GET /api/teams/{id}
     * Recupera i dettagli di una singola squadra
     */
    public function show(Team $team): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $team,
        ]);
    }

    /**
     * PUT /api/teams/{id}/tier
     * Modifica il tier di una squadra
     *
     * Body JSON richiesto:
     * {
     *   "tier": 2.5
     * }
     */
    public function updateTier(Request $request, Team $team): JsonResponse
    {
        // Validazione
        $validated = $request->validate([
            'tier' => 'required|numeric|between:1,3',
        ]);

        // Aggiorna il tier
        $team->update([
            'tier' => $validated['tier'],
        ]);

        // ricalcolo l'easy score nel caso siano cambiati i tier
        Artisan::call('calculate:easyscore');

        return response()->json([
            'success' => true,
            'message' => "Tier della squadra {$team->nome} aggiornato a {$team->tier}",
            'data' => $team,
        ]);
    }

    /**
     * POST /api/teams/reorder
     * Riordina i tier di più squadre in una sola richiesta
     *
     * Body JSON richiesto:
     * {
     *   "teams": [
     *     { "id": 1, "tier": 1.5 },
     *     { "id": 2, "tier": 2.0 },
     *     { "id": 3, "tier": 2.5 }
     *   ]
     * }
     */
    public function reorder(Request $request): JsonResponse
    {
        // Validazione
        $validated = $request->validate([
            'teams' => 'required|array',
            'teams.*.id' => 'required|integer|exists:teams,id',
            'teams.*.tier' => 'required|numeric|between:1,3',
        ]);

        // Aggiorna tutti i tier in un'unica transazione
        $updated = [];
        foreach ($validated['teams'] as $teamData) {
            $team = Team::find($teamData['id']);
            $team->update(['tier' => $teamData['tier']]);
            $updated[] = $team;
        }

        // ricalcolo l'easy score nel caso siano cambiati i tier
        Artisan::call('calculate:easyscore');

        return response()->json([
            'success' => true,
            'message' => count($updated) . ' squadre aggiornate',
            'data' => $updated,
        ]);
    }

    /**
     * POST /api/teams
     * Crea una nuova squadra (opzionale, per future features)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|unique:teams|max:100',
            'tier' => 'required|numeric|between:1,3',
            'logo_url' => 'nullable|url|max:255',
        ]);

        $team = Team::create($validated);

        return response()->json([
            'success' => true,
            'message' => "Squadra {$team->nome} creata",
            'data' => $team,
        ], 201);
    }

    /**
     * DELETE /api/teams/{id}
     * Elimina una squadra (opzionale, per future features)
     */
    public function destroy(Team $team): JsonResponse
    {
        $teamName = $team->nome;
        $team->delete();

        return response()->json([
            'success' => true,
            'message' => "Squadra $teamName eliminata",
        ]);
    }
}
