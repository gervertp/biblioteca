import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import BooksView from '../views/BooksView.vue'
import AuthorsView from '../views/AuthorsView.vue'
import LoansView from '../views/LoansView.vue'
import UsersView from '../views/UsersView.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        { path: '/', redirect: '/books' },
        { path: '/login', component: LoginView },
        { path: '/register', component: RegisterView },
        { path: '/books', component: BooksView, meta: { requiresAuth: true } },
        { path: '/authors', component: AuthorsView, meta: { requiresAuth: true } },
        { path: '/loans', component: LoansView, meta: { requiresAuth: true } },
        { path: '/users', component: UsersView, meta: { requiresAuth: true } },
    ],
})

router.beforeEach((to) => {
    const authStore = useAuthStore()
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        return '/login'
    }
})

export default router
