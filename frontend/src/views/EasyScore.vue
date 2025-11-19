<script setup>
import { onMounted } from 'vue';
import { useEasyScore } from '@/composables/useEasyScore';
import Titolone from '@/components/Titolone.vue';
import Loader from '@/components/Loader.vue';
import ErrorMsg from '@/components/ErrorMsg.vue';

const {
    loading,
    error,
    currentGiornata,
    fromGiornata,
    toGiornata,
    easyScoreTable,
    availableGiornate,
    fetchAllData
} = useEasyScore();

onMounted(() => {
    fetchAllData();
});

/**
 * Determina il colore della cella in base all'EasyScore
 * Verde scuro = facile, Rosso = difficile
 */
const getEasyScoreColor = (score) => {
    const numScore = parseFloat(score);

    if (numScore >= 3.0) return '#2d6a4f'; // Verde scuro
    if (numScore >= 2.5) return '#52b788'; // Verde
    if (numScore >= 2.0) return '#fee440'; // Giallo
    if (numScore >= 1.5) return '#fb8500'; // Arancione
    return '#d62828'; // Rosso
};

/**
 * Determina la classe tier in base all'EasyScore
 */
const getTierClass = (score) => {
    const numScore = parseFloat(score);

    if (numScore >= 3.0) return 'bg-tier-1-0';
    if (numScore >= 2.5) return 'bg-tier-1-5';
    if (numScore >= 2.0) return 'bg-tier-2-0';
    if (numScore >= 1.5) return 'bg-tier-2-5';
    return 'bg-tier-3-0';
};
</script>

<template>
    <div class="easy-score-page">
        <!-- titolo -->
        <Titolone title="Analisi Calendario" subtitle="Incroci e EasyScore partita per partita" />

        <!-- Loading State -->
        <Loader v-if="loading" />

        <div v-else class="container page-content">

            <!-- Error State -->
            <ErrorMsg v-if="error" :error="error" />

            <!-- Filtri Giornate -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Da Giornata:</label>
                    <select v-model.number="fromGiornata" class="form-select">
                        <option v-for="g in availableGiornate" :key="g" :value="g">
                            Giornata {{ g }}{{ g === currentGiornata ? ' (Corrente)' : '' }}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">A Giornata:</label>
                    <select v-model.number="toGiornata" class="form-select">
                        <option v-for="g in availableGiornate" :key="g" :value="g" :disabled="g < fromGiornata">
                            Giornata {{ g }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Info Range -->
            <div class="alert alert-info">
                <strong>📊 Analisi:</strong>
                Visualizzando {{ toGiornata - fromGiornata + 1 }} giornate
                (dalla {{ fromGiornata }} alla {{ toGiornata }})
            </div>

            <!-- Tabella EasyScore -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th class="text-center">Squadra</th>
                            <th class="text-center">EasyScore Totale</th>
                            <th v-for="i in (toGiornata - fromGiornata + 1)" :key="i" class="text-center">
                                Gior {{ fromGiornata + i - 1 }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in easyScoreTable" :key="row.team.id">
                            <!-- Nome Squadra -->
                            <td class="fw-bold">{{ row.team.nome }}</td>

                            <!-- EasyScore Totale -->
                            <td class="text-center fw-bold fs-5"
                                :class="[getTierClass(row.totalEasyScore), 'text-white']">
                                {{ row.totalEasyScore }}
                            </td>

                            <!-- Giornate con avversari -->
                            <td v-for="g in row.giornate" :key="g.giornata" class="text-center"
                                :class="g.opponent ? [getTierClass(g.opponent.easyScore), 'text-white'] : ''">
                                <template v-if="g.opponent">
                                    <span :class="g.opponent.isHome ? 'fw-bold' : ''">
                                        {{ g.opponent.isHome ? '🏠' : '✈️' }} {{ g.opponent.name }}
                                    </span>
                                    <br>
                                    <small class="text-muted">({{ g.opponent.easyScore }})</small>
                                </template>
                                <template v-else>
                                    <span class="text-muted">-</span>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Legenda -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">📖 Legenda</h5>
                            <ul class="mb-0">
                                <li><strong>🏠</strong> = Partita in casa</li>
                                <li><strong>✈️</strong> = Partita in trasferta</li>
                                <li><strong>Verde</strong> = Partita facile (EasyScore alto)</li>
                                <li><strong>Rosso</strong> = Partita difficile (EasyScore basso)</li>
                                <li><strong>EasyScore Totale</strong> = Somma degli EasyScore nel range selezionato</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
@use "@/assets/style/colors" as *;

.page-content {
    display: flex;
    flex-direction: column;
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
}

.easy-score-page {
    min-height: 100vh;
}

.table-responsive {
    max-height: 70vh;
    overflow-y: auto;
}

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}

/* Responsive */
@media (max-width: 768px) {
    .table {
        font-size: 0.85rem;
    }

    td,
    th {
        padding: 0.5rem;
    }
}
</style>