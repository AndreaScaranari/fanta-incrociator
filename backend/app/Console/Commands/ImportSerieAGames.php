<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FootballDataService;

class ImportSerieAGames extends Command
{
    protected $signature = 'import:serieagames {season? : Anno della stagione}';
    protected $description = 'Importa il calendario della Serie A';

    public function handle()
    {
        $season = $this->argument('season') ?: 2025;
        $service = new FootballDataService();

        $this->info("Importazione calendario Serie A {$season}...");

        $result = $service->importSerieAGames($season);

        if ($result['success']) {
            $this->info("✅ " . $result['message']);
            $this->info("📊 Partite importate: " . $result['imported']);
            $this->info("⏭️ Partite saltate: " . $result['skipped']);
        } else {
            $this->error("❌ Errore: " . $result['message']);
        }

        // ricalcolo l'easy score nel caso siano cambiate le date delle partite
        $this->call('calculate:easyscore');
    }
}
