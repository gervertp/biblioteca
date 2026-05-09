import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../axios'

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null)
    const token = ref(localStorage.getItem('token'))

    const isAuthenticated = computed(() => !!token.value)

    async function login(email, password) {
        const { data } = await api.post('/login', { email, password })
        user.value = data.user
        token.value = data.token
        localStorage.setItem('token', data.token)
    }

    async function register(name, email, password, password_confirmation) {
        const { data } = await api.post('/register', { name, email, password, password_confirmation })
        user.value = data.user
        token.value = data.token
        localStorage.setItem('token', data.token)
    }

    async function logout() {
        try {
            await api.post('/logout')
        } catch {
            // El token puede estar expirado, igual limpiamos sesión
        }
        user.value = null
        token.value = null
        localStorage.removeItem('token')
    }

    return { user, token, isAuthenticated, login, register, logout }
})
