<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EasyScoreService;

class CalculateEasyScore extends Command
{
    protected $signature = 'calculate:easyscore';
    protected $description = 'Calcola gli EasyScore per il calendario della Serie A';

    public function handle()
    {
        $service = new EasyScoreService();
        $this->info("Inizializzazione assegnazione EasyScore...");
        $result = $service->calculateAllEasyScores();

        $this->info("✅ Calcolati {$result['calculated']} ES su {$result['total']}");

        if (!empty($result['errors'])) {
            $this->warn("⚠️  Errori riscontrati nelle partite con i seguenti ID:");
            foreach ($result['errors'] as $error) {
                $this->error(" $error /");
            }
        }
    }
}
