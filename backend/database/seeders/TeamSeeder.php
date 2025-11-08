<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            // Tier 1 - Top Clubs
            ['nome' => 'Inter', 'tier' => 1.0],
            ['nome' => 'Napoli', 'tier' => 1.0],
            ['nome' => 'Milan', 'tier' => 1.0],
            ['nome' => 'Roma', 'tier' => 1.0],

            // Tier 1.5 - Upper-Mid
            ['nome' => 'Bologna', 'tier' => 1.5],
            ['nome' => 'Juventus', 'tier' => 1.5],
            ['nome' => 'Como', 'tier' => 1.5],

            // Tier 2 - Mid
            ['nome' => 'Udinese', 'tier' => 2.0],
            ['nome' => 'Lazio', 'tier' => 2.0],
            ['nome' => 'Atalanta', 'tier' => 2.0],
            ['nome' => 'Sassuolo', 'tier' => 2.0],
            ['nome' => 'Torino', 'tier' => 2.0],

            // Tier 2.5 - Lower-Mid
            ['nome' => 'Cremonese', 'tier' => 2.5],
            ['nome' => 'Fiorentina', 'tier' => 2.5],
            ['nome' => 'Cagliari', 'tier' => 2.5],
            ['nome' => 'Lecce', 'tier' => 2.5],

            // Tier 3 - Bottom
            ['nome' => 'Parma', 'tier' => 3.0],
            ['nome' => 'Pisa', 'tier' => 3.0],
            ['nome' => 'Genoa', 'tier' => 3.0],
            ['nome' => 'Verona', 'tier' => 3.0],
        ];

        foreach ($teams as $team) {
            DB::table('teams')->insert([
                'nome' => $team['nome'],
                'tier' => $team['tier'],
                'logo_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
