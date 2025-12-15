<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'giornata',
        'data_partita',
        'season',
        'home_goals',
        'away_goals',
        'home_easy_score',
        'away_easy_score'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_partita' => 'datetime',
        'giornata' => 'integer',
        'home_goals' => 'integer',
        'away_goals' => 'integer',
        'home_easy_score' => 'decimal:1',
        'away_easy_score' => 'decimal:1'
    ];

    /**
     * Get the home team.
     */
    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    /**
     * Get the away team.
     */
    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    /**
     * Scope per filtrare per stagione
     */
    public function scopeSeason($query, $season = null)
    {
        $season = $season ?? config('fanta.current_season');
        return $query->where('season', $season);
    }

    /**
     * Scope per filtrare per giornata
     */
    public function scopeGiornata($query, $giornata)
    {
        return $query->where('giornata', $giornata);
    }

    /**
     * Scope per ottenere le prossime N giornate
     */
    public function scopeNextGiornate($query, $fromGiornata, $count)
    {
        return $query->whereBetween('giornata', [$fromGiornata, $fromGiornata + $count - 1]);
    }

    /**
     * Scope per ottenere partite di una squadra
     */
    public function scopeForTeam($query, $teamId)
    {
        return $query->where('home_team_id', $teamId)
            ->orWhere('away_team_id', $teamId);
    }

    /**
     * Determina se la squadra gioca in casa
     */
    public function isHomeTeam($teamId): bool
    {
        return $this->home_team_id == $teamId;
    }

    /**
     * Ottieni l'avversario di una squadra
     */
    public function getOpponent($teamId)
    {
        return $this->isHomeTeam($teamId) ? $this->awayTeam : $this->homeTeam;
    }
}
