import { createRouter, createWebHistory } from 'vue-router'
import { useSessionStore } from '@/stores/session'

// import LoginView from '@/views/LoginView.vue'
// import PosView   from '@/views/PosView.vue'
import Login from '@/views/Login.vue'
import Outlets from '@/views/Outlets.vue'
import Pos from '@/views/Pos.vue'
import { BProgress } from '@bprogress/core';

const routes = [
    { path: '/login', component: Login },
    { path: '/outlets', component: Outlets, meta: { requiresAuth: true } },
    { path: '/', component: Pos, meta: { requiresAuth: true } }
]

const router = createRouter({
    history: createWebHistory('/terminal'),
    routes,
})

router.beforeEach(async (to) => {
    BProgress.start()

    const session = useSessionStore()
    await session.pingCheck()
    await session.restore()

    if (to.meta.requiresAuth && !session.isLoggedIn) {
        return '/login';
    }

    if (to.path === '/login' && session.isLoggedIn) {
        return '/'
    }

    if (to.path === '/' && session.isLoggedIn && !session.outlet) {
        return '/outlets'
    }
})


router.afterEach(() => {
    BProgress.done()
})

export default router
