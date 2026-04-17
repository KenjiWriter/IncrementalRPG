import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../store/useAuthStore';
import LandingView from '../views/LandingView.vue';
import DashboardView from '../views/DashboardView.vue';

const routes = [
    {
        path: '/',
        name: 'landing',
        component: LandingView,
        meta: { requiresGuest: true }
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: DashboardView,
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'landing' });
    } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
        next({ name: 'dashboard' });
    } else {
        next();
    }
});

export default router;
