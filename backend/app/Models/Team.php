<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'tier',
        'logo_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tier' => 'decimal:1',
    ];

    /**
     * Get all Games where this team is the home team.
     */
    // public function homeGames()
    // {
    //     return $this->hasMany(Game::class, 'home_team_id');
    // }

    /**
 * Get all Games where this team is the away team.
 */
    // public function awayGames()
    // {
    //     return $this->hasMany(Game::class, 'away_team_id');
    // }

    /**
 * Get all Games for this team (home or away).
 */
    // public function allGames()
    // {
    //     return $this->homeGames()->union($this->awayGames());
    // }
}
