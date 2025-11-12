<script setup>
import { onMounted, ref } from 'vue'
import { useTeams } from '../composables/useTeams'
import Titolone from '../components/Titolone.vue'
import Loader from '../components/Loader.vue'
import ErrorMsg from '../components/ErrorMsg.vue'
import WarningMsg from '../components/WarningMsg.vue'
import TeamsRankingList from '../components/TeamsRankingList.vue'

const { teams, loading, error, fetchTeams, teamsGroupedByTier } = useTeams()

onMounted(() => {
    fetchTeams()
})

</script>

<template>
    <!-- titolo -->
    <Titolone title="Main Dashboard" subtitle="Tutte le info sulle squadre di Serie A" />

    <!-- Loading State -->
    <Loader v-if="loading" />

    <div v-else class="container page-content">

        <!-- Error State -->
        <ErrorMsg v-if="error" :error="error" />

        <!-- Teams Grouped by Tier -->
        <TeamsRankingList v-if="!loading && teams.length > 0" :teams="teamsGroupedByTier" />

        <!-- Empty State -->
        <WarningMsg v-if="!loading && teams.length === 0"
            msg="⚠️ Nessuna squadra trovata. Contatta l'amministratore." />

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
</style>