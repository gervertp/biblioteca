<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Crear Cuenta</h2>

            <div v-if="error" class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">{{ error }}</div>

            <form @submit.prevent="handleRegister" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input v-model="name" type="text" placeholder="Juan Pérez" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input v-model="email" type="email" placeholder="juan@gmail.com" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input v-model="password" type="password" placeholder="********" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                    <input v-model="password_confirmation" type="password" placeholder="********" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <button type="submit" :disabled="loading"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50 transition">
                    {{ loading ? 'Cargando...' : 'Registrarse' }}
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-4">
                ¿Ya tienes cuenta?
                <RouterLink to="/login" class="text-blue-600 hover:underline">Inicia sesión</RouterLink>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const error = ref(null)
const loading = ref(false)

async function handleRegister() {
    error.value = null
    loading.value = true
    try {
        await authStore.register(name.value, email.value, password.value, password_confirmation.value)
        router.push('/books')
    } catch (e) {
        error.value = e.response?.data?.message ?? 'Error al registrarse.'
    } finally {
        loading.value = false
    }
}
</script>
