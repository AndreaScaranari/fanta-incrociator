<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('away_team_id')->constrained('teams')->onDelete('cascade');
            $table->unsignedTinyInteger('giornata');
            $table->dateTime('data_partita')->nullable();
            // $table->string('season', 9)->default('2025-2026');
            $table->unsignedSmallInteger('season')->default(2025);
            $table->decimal('home_easy_score', 3, 1)->nullable();
            $table->decimal('away_easy_score', 3, 1)->nullable();
            $table->unsignedTinyInteger('home_goals')->nullable();
            $table->unsignedTinyInteger('away_goals')->nullable();
            $table->timestamps();

            // Indici per performance
            $table->index('giornata');
            $table->index(['season', 'giornata']);
            $table->index(['home_team_id', 'season']);
            $table->index(['away_team_id', 'season']);

            // Vincoli di unicità
            $table->unique(['home_team_id', 'giornata', 'season'], 'unique_home_match');
            $table->unique(['away_team_id', 'giornata', 'season'], 'unique_away_match');

            // ⚠️ NOTA: Questi vincoli non impediscono completamente 
            // che una squadra giochi 2 volte nella stessa giornata
            // Serve validazione aggiuntiva nel Model!
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
