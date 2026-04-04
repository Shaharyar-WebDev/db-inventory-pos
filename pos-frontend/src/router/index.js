import { createRouter, createWebHistory } from 'vue-router'
import { useSessionStore } from '@/stores/session'

import LoginView from '@/views/LoginView.vue'
import PosView   from '@/views/PosView.vue'

const routes = [
  { path: '/login', component: LoginView },
  { path: '/',      component: PosView, meta: { requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory('/pos/'),
  routes,
})

// Auth guard — redirect to login if not logged in
router.beforeEach(async (to) => {
  const session = useSessionStore()
  await session.restore()

  if (to.meta.requiresAuth && !session.isLoggedIn) {
    return '/login'
  }
})

export default router
