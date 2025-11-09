import { ref, computed } from 'vue'
import api from '../services/api'

export function useTeams() {
  const teams = ref([])
  const loading = ref(false)
  const error = ref(null)

  /**
   * Recupera tutte le squadre
   */
  const fetchTeams = async () => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/teams')
      teams.value = response.data.data
    } catch (err) {
      error.value = err.message || 'Errore nel caricamento delle squadre'
      console.error('Errore fetchTeams:', err)
    } finally {
      loading.value = false
    }
  }

  /**
   * Aggiorna il tier di una squadra
   * @param {number} teamId - ID della squadra
   * @param {number} newTier - Nuovo tier
   */
  const updateTeamTier = async (teamId, newTier) => {
    try {
      const response = await api.put(`/teams/${teamId}/tier`, {
        tier: newTier
      })
      
      // Aggiorna la squadra locale
      const team = teams.value.find(t => t.id === teamId)
      if (team) {
        team.tier = response.data.data.tier
      }
      
      return response.data.data
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      console.error('Errore updateTeamTier:', err)
      throw err
    }
  }

  /**
   * Riordina multiple squadre in una richiesta
   * @param {Array} teamsData - Array di {id, tier}
   */
  const reorderTeams = async (teamsData) => {
    try {
      const response = await api.post('/teams/reorder', {
        teams: teamsData
      })
      
      // Ricarica tutte le squadre per sincronizzare
      await fetchTeams()
      
      return response.data.data
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      console.error('Errore reorderTeams:', err)
      throw err
    }
  }

  /**
   * Ottiene squadre ordinate per tier
   */
  const teamsByTier = computed(() => {
    return [...teams.value].sort((a, b) => a.tier - b.tier)
  })

  /**
   * Raggruppa squadre per tier
   */
  const teamsGroupedByTier = computed(() => {
    const grouped = {}
    teamsByTier.value.forEach(team => {
      if (!grouped[team.tier]) {
        grouped[team.tier] = []
      }
      grouped[team.tier].push(team)
    })
    return grouped
  })

  return {
    teams,
    loading,
    error,
    fetchTeams,
    updateTeamTier,
    reorderTeams,
    teamsByTier,
    teamsGroupedByTier
  }
}