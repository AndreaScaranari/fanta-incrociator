<?php

namespace App\Services;

use App\Models\Team;
use App\Models\Game;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class FootballDataService
{
    private $apiKey;
    private $baseUrl = 'https://api.football-data.org/v4';
    private $serieACompetitionId = 2019; // ID Serie A su Football-Data.org

    public function __construct()
    {
        // $this->apiKey = config('services.football_data.api_key');
        $this->apiKey = "92a6551514294e2f918d3b403931efb1";
    }

    /**
     * Importa il calendario completo della Serie A
     */
    public function importSerieAGames($season = 2025)
    {
        try {
            $response = Http::withHeaders([
                'X-Auth-Token' => $this->apiKey
            ])->get("{$this->baseUrl}/competitions/{$this->serieACompetitionId}/matches", [
                'season' => $season
            ]);

            if (!$response->successful()) {
                throw new Exception('Errore nel recupero dei dati: ' . $response->status());
            }

            $data = $response->json();
            $imported = 0;
            $skipped = 0;

            foreach ($data['matches'] as $matchData) {
                $result = $this->importSingleMatch($matchData, $season);
                if ($result) {
                    $imported++;
                } else {
                    $skipped++;
                }
            }

            return [
                'success' => true,
                'imported' => $imported,
                'skipped' => $skipped,
                'message' => "Importate {$imported} partite, {$skipped} saltate"
            ];
        } catch (Exception $e) {
            Log::error('Errore importazione calendario: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Importa una singola partita
     */
    private function importSingleMatch($matchData, $season)
    {
        // Mappa i nomi delle squadre da Football-Data ai nostri
        $teamMapping = $this->getTeamMapping();

        $homeTeamName = $this->normalizeTeamName($matchData['homeTeam']['shortName']);
        $awayTeamName = $this->normalizeTeamName($matchData['awayTeam']['shortName']);

        // Trova le squadre nel nostro database
        $homeTeam = Team::where('nome', $teamMapping[$homeTeamName] ?? $homeTeamName)->first();
        $awayTeam = Team::where('nome', $teamMapping[$awayTeamName] ?? $awayTeamName)->first();

        if (!$homeTeam || !$awayTeam) {
            Log::warning("Squadre non trovate: {$homeTeamName} vs {$awayTeamName}");
            return false;
        }

        // Determina la giornata dal matchday
        $giornata = $matchData['matchday'];

        // Controlla se la partita esiste già
        $exists = Game::where('home_team_id', $homeTeam->id)
            ->where('away_team_id', $awayTeam->id)
            ->where('giornata', $giornata)
            ->where('season', $season)
            ->exists();

        if ($exists) {
            return false;
        }

        // Crea la partita
        Game::create([
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'giornata' => $giornata,
            'data_partita' => $matchData['utcDate'] ?? null,
            'season' => $season,
            'home_goals' => $matchData['score']['fullTime']['home'] ?? null,
            'away_goals' => $matchData['score']['fullTime']['away'] ?? null,
        ]);

        return true;
    }

    /**
     * Normalizza il nome della squadra
     */
    private function normalizeTeamName($name)
    {
        // Rimuove FC, AC, etc e normalizza
        $name = str_replace(['FC ', 'AC ', 'SS ', 'US ', 'AS '], '', $name);
        return trim($name);
    }

    /**
     * Mappa i nomi delle squadre da Football-Data ai nostri
     */
    private function getTeamMapping()
    {
        return [
            'Juventus' => 'Juventus',
            'Internazionale' => 'Inter',
            'Milano' => 'Milan',
            'Napoli' => 'Napoli',
            'Roma' => 'Roma',
            'Atalanta' => 'Atalanta',
            'Bologna' => 'Bologna',
            'Lazio' => 'Lazio',
            'Fiorentina' => 'Fiorentina',
            'Como 1907' => 'Como',
            'Como' => 'Como',
            'Torino' => 'Torino',
            'Udinese' => 'Udinese',
            'Cagliari' => 'Cagliari',
            'Parma Calcio 1913' => 'Parma',
            'Parma' => 'Parma',
            'Cremonese' => 'Cremonese',
            'Sassuolo' => 'Sassuolo',
            'Lecce' => 'Lecce',
            'Pisa' => 'Pisa',
            'Genoa' => 'Genoa',
            'Hellas Verona' => 'Verona',
            'Verona' => 'Verona'
        ];
    }
}
