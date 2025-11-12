<script setup>
import { onMounted, ref, computed } from 'vue'
import { useTeams } from '../composables/useTeams'
import Loader from '../components/Loader.vue'
import ErrorMsg from '../components/ErrorMsg.vue'
import Titolone from '../components/Titolone.vue'
import UpdateButton from '../components/UpdateButton.vue'

// Composable - contiene la logica dei dati
const { teams, loading, error, fetchTeams, reorderTeams, teamsByTier } = useTeams()

// State locale - traccia i cambiamenti
const tempTiers = ref({})
const isUpdating = ref(false)

// Carica i dati al mount
onMounted(() => {
    fetchTeams()
})

/**
 * Calcola quanti team sono stati modificati
 */
const changedTeamsCount = computed(() => {
    return Object.keys(tempTiers.value).filter(teamId => {
        return tempTiers.value[teamId] && tempTiers.value[teamId] !== teams.value.find(t => t.id == teamId)?.tier
    }).length
})

/**
 * Costruisce l'array di team da inviare al backend
 * Filtra solo quelli modificati
 */
const getChangedTeams = () => {
    return Object.keys(tempTiers.value)
        .filter(teamId => {
            return tempTiers.value[teamId] && tempTiers.value[teamId] !== teams.value.find(t => t.id == teamId)?.tier
        })
        .map(teamId => ({
            id: parseInt(teamId),
            tier: parseFloat(tempTiers.value[teamId])
        }))
}

/**
 * Invia TUTTI i cambiamenti al backend in una singola richiesta
 */
const updateAllTeams = async () => {
    const changedTeams = getChangedTeams()

    if (changedTeams.length === 0) {
        alert('Nessun cambio da salvare!')
        return
    }

    isUpdating.value = true

    try {
        await reorderTeams(changedTeams)
        // Reset i valori temporanei dopo il successo
        tempTiers.value = {}
        alert(`✅ ${changedTeams.length} squadre aggiornate!`)
    } catch (err) {
        console.error('Errore nell\'aggiornamento:', err)
    } finally {
        isUpdating.value = false
    }
}

/**
 * Ritorna la classe CSS per il badge del tier
 */
const getTierBg = (tier) => {
    const tierClasses = {
        '1.0': '1-0',
        '1.5': '1-5',
        '2.0': '2-0',
        '2.5': '2-5',
        '3.0': '3-0'
    }
    return tierClasses[tier] || 'badge bg-secondary'
}

/**
 * Ritorna la classe CSS per la riga (highlight se modificata)
 */
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

            <!-- Pulsante Aggiorna (SOPRA) -->
            <div class="update-actions">
                <UpdateButton :changedCount="changedTeamsCount" :isLoading="isUpdating" @update="updateAllTeams" />
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nome Squadra</th>
                            <th>Tier Attuale</th>
                            <th>Nuovo Tier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="team in teamsByTier" :key="team.id" :class="getRowClass(team)">
                            <td class="team-name">
                                {{ team.nome }}
                            </td>
                            <td class="badge-cell">
                                <span :class="`badge bg-tier-${getTierBg(team.tier)}`">{{ team.tier }}</span>
                            </td>
                            <td>
                                <select v-model.number="tempTiers[team.id]" class="form-select form-select-sm">
                                    <option value="">-- Seleziona --</option>
                                    <option value="1.0">1.0</option>
                                    <option value="1.5">1.5</option>
                                    <option value="2.0">2.0</option>
                                    <option value="2.5">2.5</option>
                                    <option value="3.0">3.0</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pulsante Aggiorna (SOTTO) -->
            <div class="update-actions">
                <UpdateButton :changedCount="changedTeamsCount" :isLoading="isUpdating" @update="updateAllTeams" />
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

th {
    text-align: center;
}

.badge-cell {
    text-align: center;
    text-align: center;
    vertical-align: middle;
}

.team-name {
    font-weight: bold;
    text-align: center;
    vertical-align: middle;
}

.badge {
    color: $white;
}

.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}

.form-select-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.update-actions {
    padding: 1rem;
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: center;

    &:last-of-type {
        border-top: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
    }

    .btn {
        min-width: 150px;
        background-color: $blue;
        color: $white;
        font-weight: 500;
        transition: all 0.25s ease-in-out;

        &:hover {
            filter: brightness(1.1);
            scale: 1.01;
        }
    }
}
</style>