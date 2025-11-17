<?php

namespace App\Services;

use App\Models\Team;
use App\Models\Game;

class EasyScoreService
{
    private $bonusHome = 0.5; // modificabile in futuro

    public function assignEasyScore()
    {
        $games = Game::where('*');
        foreach ($games as $game) {
            $home = $game->home_team_id;
            $away = $game->away_team_id;
            $this->calcolateEasyScore($home, $away);
        }
    }

    private function calcolateEasyScore($home, $away)
    {
        $home_easy_score_part = Team::where('id', $away)->tier;
        $home_easy_score = floatval($home_easy_score_part) + $this->bonusHome;

        $away_easy_score = floatval(Team::where('id', $home)->tier);

        $home->home_easy_score = $home_easy_score;
        $away->away_easy_score = $away_easy_score;
    }
}
