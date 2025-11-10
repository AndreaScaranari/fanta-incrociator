<script setup>
import { onMounted, ref } from 'vue'
import { useTeams } from '../composables/useTeams'
import Titolone from '../components/Titolone.vue'
import Loader from '../components/Loader.vue'

const { teams, loading, error, fetchTeams, teamsGroupedByTier } = useTeams()

onMounted(() => {
    fetchTeams()
})

const handleRefresh = () => {
    fetchTeams()
}

const getTierClass = (tier) => {
    const tierClasses = {
        '1': 'bg-success text-white',
        '1.5': 'bg-info text-white',
        '2': 'bg-warning text-dark',
        '2.5': 'bg-warning text-dark',
        '3': 'bg-danger text-white'
    }
    return tierClasses[tier] || 'bg-secondary text-white'
}
</script>

<template>
    <!-- titolo -->
    <Titolone title="Main Dashboard" subtitle="Tutte le info sulle squadre di Serie A" />

    <!-- Loading State -->
    <Loader v-if="loading" />

    <div v-else class="container page-content py-4">

        <!-- Error State -->
        <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ error }}
            <button type="button" class="btn-close" @click="error = null"></button>
        </div>

        <!-- Teams Grouped by Tier -->
        <div v-if="!loading && teams.length > 0" class="row">
            <div v-for="(tierTeams, tier) in teamsGroupedByTier" :key="tier" class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header" :class="getTierClass(tier)">
                        <h5 class="mb-0">
                            Tier {{ tier }}
                            <span class="badge bg-secondary ms-2">{{ tierTeams.length }}</span>
                        </h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li v-for="team in tierTeams" :key="team.id"
                            class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ team.nome }}</strong>
                                <br>
                                <small class="text-muted">ID: {{ team.id }}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ team.tier }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="!loading && teams.length === 0" class="alert alert-warning" role="alert">
            ⚠️ Nessuna squadra trovata. Contatta l'amministratore.
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col">
                <button @click="handleRefresh" class="btn btn-outline-primary me-2" :disabled="loading">
                    <i class="bi bi-arrow-clockwise"></i> Ricarica
                </button>
                <router-link to="/ranking" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Modifica Ranking
                </router-link>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
@use "@/assets/style/colors" as *;

.page-content {
    display: flex;
    flex-direction: column;
}

.card {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;

    &:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
}

.list-group-item {
    border-left: 4px solid transparent;
    transition: all 0.2s;

    &:hover {
        border-left-color: #007bff;
        background-color: #f8f9fa;
    }
}
</style>