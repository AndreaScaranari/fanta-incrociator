<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Team;

class EasyScoreService
{
    private float $bonusHome;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bonusHome = config('fanta.easyscore.bonus_casa', 0.5);
    }

    /**
     * Ricalcola TUTTI gli easy score
     */
    public function calculateAllEasyScores(): array
    {
        $games = Game::with(['homeTeam', 'awayTeam'])->get();
        $errors = [];

        foreach ($games as $game) {
            if (!$this->calculateGameEasyScore($game)) {
                $errors[] = "Game ID {$game->id} failed";
            }
        }

        return [
            'total' => count($games),
            'calculated' => count($games) - count($errors),
            'errors' => $errors
        ];
    }

    /**
     * Calcola gli easy score per una partita specifica
     */
    public function calculateGameEasyScore(Game $game): bool
    {
        $homeTeam = $game->homeTeam;
        $awayTeam = $game->awayTeam;

        if (!$homeTeam || !$awayTeam) {
            return false;
        }

        $homeEasyScore = floatval($awayTeam->tier ?? 1000) + $this->bonusHome;
        $awayEasyScore = floatval($homeTeam->tier ?? 100);

        $game->update([
            'home_easy_score' => $homeEasyScore,
            'away_easy_score' => $awayEasyScore
        ]);

        return true;
    }
}
