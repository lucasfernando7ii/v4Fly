import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../store/auth.js';
import Login from '../views/Login.vue';
import Dashboard from '../views/Dashboard.vue';
import Register from '../views/Register.vue'; 


const routes = [
    {
    path: '/register', 
    name: 'Register',
    component: Register,
    meta: { requiresAuth: false },
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/',
    redirect: '/login',
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (to.meta.requiresAuth) {
    if (authStore.isAuthenticated) {
      next();
    } else {
      next({ name: 'Login' });
    }
  }

  else {
    if (authStore.isAuthenticated && to.name === 'Login') {
      next({ name: 'Dashboard' });
    } else {
      next();
    }
  }
});

export default router;
