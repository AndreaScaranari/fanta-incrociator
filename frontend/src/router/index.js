import { createRouter, createWebHistory } from 'vue-router'
import TeamList from '../views/TeamList.vue'
import TeamRankingEditor from '../views/TeamRankingEditor.vue'
import { nextTick } from 'vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: TeamList,
    meta: { title: 'Team List - Fanta Incrociator' }
  },
  {
    path: '/ranking',
    name: 'Ranking',
    component: TeamRankingEditor,
    meta: { title: 'Ranking Editor - Fanta Incrociator' }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// per aggiornamento titoli
router.afterEach((to) => {
  document.title = to.meta.title || 'Fanta Incrociator'
})

export default router