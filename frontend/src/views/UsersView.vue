<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Biblioteca</h1>
            <div class="flex gap-4 items-center">
                <RouterLink to="/books" class="text-gray-600 hover:text-blue-600">Libros</RouterLink>
                <RouterLink to="/authors" class="text-gray-600 hover:text-blue-600">Autores</RouterLink>
                <RouterLink to="/loans" class="text-gray-600 hover:text-blue-600">Préstamos</RouterLink>
                <RouterLink to="/users" class="text-blue-600 font-medium">Socios</RouterLink>
                <button @click="handleLogout" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                    Cerrar Sesión
                </button>
            </div>
        </nav>

        <div class="max-w-6xl mx-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Socios</h2>
                <button @click="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Nuevo Socio
                </button>
            </div>

            <div class="mb-4">
                <input v-model="search" @input="onSearch" type="text" placeholder="Buscar por nombre, apellido o email..."
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div v-if="loading" class="text-gray-500">Cargando socios...</div>

            <div v-else>
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Nombre</th>
                                <th class="px-6 py-3">Apellido</th>
                                <th class="px-6 py-3">Celular</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="member in members" :key="member.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ member.name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ member.last_name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ member.phone ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ member.email }}</td>
                                <td class="px-6 py-4 flex gap-3">
                                    <button @click="openModal(member)" class="text-blue-600 hover:underline text-xs">Editar</button>
                                    <button @click="deleteMember(member.id)" class="text-red-500 hover:underline text-xs">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center mt-4">
                    <button :disabled="currentPage === 1" @click="changePage(currentPage - 1)"
                        class="px-4 py-2 bg-blue-600 text-white rounded disabled:opacity-40 hover:bg-blue-700">
                        Anterior
                    </button>
                    <span class="text-gray-600 text-sm">Página {{ currentPage }} de {{ lastPage }}</span>
                    <button :disabled="currentPage === lastPage" @click="changePage(currentPage + 1)"
                        class="px-4 py-2 bg-blue-600 text-white rounded disabled:opacity-40 hover:bg-blue-700">
                        Siguiente
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    {{ editing ? 'Editar Socio' : 'Nuevo Socio' }}
                </h3>

                <div v-if="formError" class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">{{ formError }}</div>

                <form @submit.prevent="saveMember" class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                            <input v-model="form.name" type="text" required
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido *</label>
                            <input v-model="form.last_name" type="text" required
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                        <input v-model="form.phone" type="text"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input v-model="form.email" type="email" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="closeModal"
                            class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="saving"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
                            {{ saving ? 'Guardando...' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import api from '../axios'

const router = useRouter()
const authStore = useAuthStore()

const members = ref([])
const loading = ref(true)
const currentPage = ref(1)
const lastPage = ref(1)
const search = ref('')
let searchTimeout = null

const showModal = ref(false)
const editing = ref(null)
const saving = ref(false)
const formError = ref(null)

const form = ref({
    name: '',
    last_name: '',
    phone: '',
    email: '',
})

async function fetchMembers(page = 1) {
    loading.value = true
    try {
        const { data } = await api.get('/members', {
            params: { page, search: search.value || undefined }
        })
        members.value = data.data
        currentPage.value = data.meta.current_page
        lastPage.value = data.meta.last_page
    } finally {
        loading.value = false
    }
}

function onSearch() {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => fetchMembers(1), 400)
}

function openModal(member = null) {
    editing.value = member
    formError.value = null
    form.value = member
        ? { name: member.name, last_name: member.last_name, phone: member.phone ?? '', email: member.email }
        : { name: '', last_name: '', phone: '', email: '' }
    showModal.value = true
}

function closeModal() {
    showModal.value = false
    editing.value = null
}

async function saveMember() {
    saving.value = true
    formError.value = null
    try {
        if (editing.value) {
            await api.put(`/members/${editing.value.id}`, form.value)
        } else {
            await api.post('/members', form.value)
        }
        closeModal()
        fetchMembers(currentPage.value)
    } catch (e) {
        formError.value = e.response?.data?.message ?? 'Error al guardar el socio.'
    } finally {
        saving.value = false
    }
}

async function deleteMember(id) {
    if (!confirm('¿Estás seguro de eliminar este socio?')) return
    try {
        await api.delete(`/members/${id}`)
        fetchMembers(currentPage.value)
    } catch (e) {
        alert('No se pudo eliminar el socio.')
    }
}

function changePage(page) {
    fetchMembers(page)
}

async function handleLogout() {
    await authStore.logout()
    router.push('/login')
}

onMounted(() => fetchMembers())
</script>
