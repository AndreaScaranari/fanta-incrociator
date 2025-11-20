<script setup>
import { onMounted, computed } from 'vue';
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
    availableGiornate,
    fetchAllData,
    teams,
    selectedTeams,
    filteredEasyScoreTable,
    selectAllTeams,
    deselectAllTeams,
    easyScoreTotals
} = useEasyScore();

onMounted(() => {
    fetchAllData();
});

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

/**
 * Squadre ordinate alfabeticamente (per i checkbox)
 */
const sortedTeams = computed(() => {
    return [...teams.value].sort((a, b) => a.nome.localeCompare(b.nome));
});
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
                        <option v-for="g in availableGiornate" :key="g.giornata" :value="g.giornata">
                            Giornata {{ g.giornata }}{{ g.giornata === currentGiornata ? ' (Corrente)' : '' }}{{ g.data
                                ? ` - ${g.data}` : '' }}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">A Giornata:</label>
                    <select v-model.number="toGiornata" class="form-select">
                        <option v-for="g in availableGiornate" :key="g.giornata" :value="g.giornata"
                            :disabled="g.giornata < fromGiornata">
                            Giornata {{ g.giornata }}{{ g.data ? ` - ${g.data}` : '' }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Sezione Filtro Squadre -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">🎯 Filtra Squadre</h5>

                            <!-- Pulsanti Select/Deselect -->
                            <div class="mb-3">
                                <button @click="selectAllTeams" class="btn btn-sm btn-success me-2">
                                    ✓ Seleziona Tutte
                                </button>
                                <button @click="deselectAllTeams" class="btn btn-sm btn-danger">
                                    ✗ Deseleziona Tutte
                                </button>
                            </div>

                            <!-- Checkbox Squadre -->
                            <div class="row">
                                <div class="col-md-3 col-sm-6" v-for="team in sortedTeams" :key="team.id">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" :id="'team-' + team.id"
                                            :value="team.id" v-model="selectedTeams">
                                        <label class="form-check-label" :for="'team-' + team.id">
                                            {{ team.nome }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Range -->
            <div class="alert alert-info">
                <p>
                    <strong>📊 Giornate:</strong>
                    Visualizzando {{ toGiornata - fromGiornata + 1 }} giornate
                    (dalla {{ fromGiornata }} alla {{ toGiornata }})
                </p>
                <p class="mb-0">
                    <strong>📊 Squadre:</strong>
                    Visualizzando {{ filteredEasyScoreTable.length }} squadre su {{ teams.length }}
                </p>
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
                        <tr v-for="row in filteredEasyScoreTable" :key="row.team.id">
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
                                    <small class="text-muted fw-bold">({{ g.opponent.easyScore }})</small>
                                </template>
                                <template v-else>
                                    <span class="text-muted">-</span>
                                </template>
                            </td>
                        </tr>
                        <!-- Riga Totali -->
                        <tr class="table-secondary fw-bold">
                            <td class="text-center">TOTALE</td>
                            <td class="text-center">-</td>
                            <td v-for="total in easyScoreTotals" :key="total.giornata" class="text-center">
                                {{ total.count }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="filteredEasyScoreTable.length == 0" class="no-team-selected">Nessuna Squadra
                    Selezionata</div>
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

// .table-responsive {
//     max-height: 70vh;
//     overflow-y: auto;
// }

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}

.no-team-selected {
    text-align: center;
    background-color: $full-white;
    width: 80%;
    margin: 0 auto;
    padding: 0.5rem;
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