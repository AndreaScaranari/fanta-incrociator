<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stagione Corrente (Automatica)
    |--------------------------------------------------------------------------
    |
    | Calcola automaticamente la stagione in base alla data.
    | Da luglio in poi → anno successivo (nuova stagione).
    |
    */
    'current_season' => env('FANTA_CURRENT_SEASON') ?? (function () {
        $now = now();
        $currentYear = $now->year;
        $currentMonth = $now->month;

        // Se siamo a prima di luglio (7), è ancora la stagione precedente (x - 1)
        return $currentMonth < 7 ? $currentYear - 1 : $currentYear;
    })(),

    /*
    |--------------------------------------------------------------------------
    | Giornate
    |--------------------------------------------------------------------------
    */
    'default_giornate_ahead' => 5,  // Range default per analisi (TODO: spostare in user settings)

    /*
    |--------------------------------------------------------------------------
    | Tier
    |--------------------------------------------------------------------------
    */
    'default_tier' => 3.0,  // Tier default per squadre neopromosse
    'tier_min' => 1.0,
    'tier_max' => 3.0,

    /*
    |--------------------------------------------------------------------------
    | EasyScore
    |--------------------------------------------------------------------------
    */
    'easyscore' => [
        'bonus_casa' => 0.5,  // Bonus per partite in casa
    ],

    /*
    |--------------------------------------------------------------------------
    | API Football-Data.org
    |--------------------------------------------------------------------------
    */
    'football_api' => [
        'base_url' => 'https://api.football-data.org/v4',
        'api_key' => env('FOOTBALL_DATA_API_KEY'),
        'competition_id' => 2019,  // Serie A
    ],
];
