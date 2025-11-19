import { createRouter, createWebHistory } from 'vue-router'
import TeamList from '../views/TeamList.vue'
import TeamRankingEditor from '../views/TeamRankingEditor.vue'

const appName = "Fanta Incrociator"

const routes = [
  {
    path: '/',
    name: 'Home',
    component: TeamList,
    meta: { title: 'Team List - ' + appName }
  },
  {
    path: '/ranking',
    name: 'Ranking',
    component: TeamRankingEditor,
    meta: { title: 'Ranking Editor - ' + appName }
  },
  {
  path: '/easyscore',
  name: 'EasyScore',
  component: () => import('@/views/EasyScore.vue'),
  meta: { title: 'EasyScore - ' + appName }
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