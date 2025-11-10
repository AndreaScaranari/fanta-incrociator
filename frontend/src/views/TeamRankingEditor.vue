<script setup>
import { onMounted, ref } from 'vue'
import { useTeams } from '../composables/useTeams'
import Loader from '../components/Loader.vue'
import ErrorMsg from '../components/ErrorMsg.vue'
import Titolone from '../components/Titolone.vue'

const { teams, loading, error, fetchTeams, updateTeamTier, teamsByTier } = useTeams()
const tempTiers = ref({})
const updatingId = ref(null)

onMounted(() => {
    fetchTeams()
})

const updateSingleTeam = async (team) => {
    const newTier = tempTiers.value[team.id]

    if (!newTier || newTier === team.tier) {
        return
    }

    updatingId.value = team.id

    try {
        await updateTeamTier(team.id, newTier)
        // Reset il valore temporaneo dopo il salvataggio
        tempTiers.value[team.id] = ''
    } catch (err) {
        console.error('Errore nel salvataggio:', err)
    } finally {
        updatingId.value = null
    }
}

const getTierBadgeClass = (tier) => {
    const tierClasses = {
        '1': 'badge bg-success',
        '1.0': 'badge bg-success',
        '1.5': 'badge bg-info',
        '2': 'badge bg-warning text-dark',
        '2.0': 'badge bg-warning text-dark',
        '2.5': 'badge bg-warning text-dark',
        '3': 'badge bg-danger',
        '3.0': 'badge bg-danger'
    }
    return tierClasses[tier] || 'badge bg-secondary'
}

const getRowClass = (team) => {
    if (tempTiers.value[team.id] && tempTiers.value[team.id] !== team.tier) {
        return 'table-warning'
    }
    return ''
}
</script>

<template>
    <!-- titolo -->
    <Titolone title="Modifica Ranking" subtitle="Aggiorna il tier delle squadre" />

    <!-- Loading State -->
    <Loader v-if="loading" />
    <div v-else class="container page-content">

        <!-- Error State -->
        <ErrorMsg v-if="error" :error="error" />

        <!-- Ranking Table -->
        <div v-if="!loading && teams.length > 0" class="card">
            <div class="card-header">
                <h5 class="mb-0">Squadre e Tier</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nome Squadra</th>
                            <th>Tier Attuale</th>
                            <th>Nuovo Tier</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="team in teamsByTier" :key="team.id" :class="getRowClass(team)">
                            <td class="team-name">
                                {{ team.nome }}
                            </td>
                            <td>
                                <span :class="getTierBadgeClass(team.tier)">{{ team.tier }}</span>
                            </td>
                            <td>
                                <select v-model.number="tempTiers[team.id]" class="form-select form-select-sm"
                                    style="max-width: 100px">
                                    <option value="">-- Seleziona --</option>
                                    <option value="1.0">1.0</option>
                                    <option value="1.5">1.5</option>
                                    <option value="2.0">2.0</option>
                                    <option value="2.5">2.5</option>
                                    <option value="3.0">3.0</option>
                                </select>
                            </td>
                            <td>
                                <button v-if="tempTiers[team.id] && tempTiers[team.id] !== team.tier"
                                    @click="updateSingleTeam(team)" class="btn btn-sm btn-success"
                                    :disabled="updatingId === team.id">
                                    <span v-if="updatingId === team.id" class="spinner-border spinner-border-sm me-2"
                                        role="status">
                                        <span class="visually-hidden">Salvataggio...</span>
                                    </span>
                                    Salva
                                </button>
                                <span v-else class="text-muted small">-</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
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

    .card-header {
        color: $white;
        background-color: $green;
    }
}

.team-name {
    font-weight: bold;
}

.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}

.form-select-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}
</style>