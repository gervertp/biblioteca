<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Biblioteca</h1>
            <div class="flex gap-4 items-center">
                <RouterLink to="/books" class="text-gray-600 hover:text-blue-600">Libros</RouterLink>
                <RouterLink to="/authors" class="text-blue-600 font-medium">Autores</RouterLink>
                <RouterLink to="/loans" class="text-gray-600 hover:text-blue-600">Préstamos</RouterLink>
                <RouterLink to="/users" class="text-gray-600 hover:text-blue-600">Socios</RouterLink>
                <button @click="handleLogout" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                    Cerrar Sesión
                </button>
            </div>
        </nav>

        <div class="max-w-6xl mx-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Autores</h2>
                <button @click="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Nuevo Autor
                </button>
            </div>

            <div v-if="loading" class="text-gray-500">Cargando autores...</div>

            <div v-else>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="author in authors" :key="author.id" class="bg-white rounded-lg shadow p-5">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ author.name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ author.nationality ?? 'Nacionalidad desconocida' }}</p>
                                <p class="text-sm text-gray-500">{{ author.birth_date ?? 'Fecha desconocida' }}</p>
                                <p class="text-sm text-gray-600 mt-3 line-clamp-3">{{ author.bio ?? 'Sin biografía.' }}</p>
                            </div>
                            <div class="flex flex-col gap-2 ml-2">
                                <button @click="openModal(author)" class="text-blue-600 hover:underline text-xs">Editar</button>
                                <button @click="deleteAuthor(author.id)" class="text-red-500 hover:underline text-xs">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-6">
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
                    {{ editing ? 'Editar Autor' : 'Nuevo Autor' }}
                </h3>

                <div v-if="formError" class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">{{ formError }}</div>

                <form @submit.prevent="saveAuthor" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input v-model="form.name" type="text" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nacionalidad</label>
                        <input v-model="form.nationality" type="text"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento</label>
                        <input v-model="form.birth_date" type="date"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Biografía</label>
                        <textarea v-model="form.bio" rows="3"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
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

const authors = ref([])
const loading = ref(true)
const currentPage = ref(1)
const lastPage = ref(1)

const showModal = ref(false)
const editing = ref(null)
const saving = ref(false)
const formError = ref(null)

const form = ref({
    name: '',
    nationality: '',
    birth_date: '',
    bio: '',
})

async function fetchAuthors(page = 1) {
    loading.value = true
    try {
        const { data } = await api.get(`/authors?page=${page}`)
        authors.value = data.data
        currentPage.value = data.meta.current_page
        lastPage.value = data.meta.last_page
    } finally {
        loading.value = false
    }
}

function openModal(author = null) {
    editing.value = author
    formError.value = null
    form.value = author
        ? { name: author.name, nationality: author.nationality, birth_date: author.birth_date, bio: author.bio }
        : { name: '', nationality: '', birth_date: '', bio: '' }
    showModal.value = true
}

function closeModal() {
    showModal.value = false
    editing.value = null
}

async function saveAuthor() {
    saving.value = true
    formError.value = null
    try {
        if (editing.value) {
            await api.put(`/authors/${editing.value.id}`, form.value)
        } else {
            await api.post('/authors', form.value)
        }
        closeModal()
        fetchAuthors(currentPage.value)
    } catch (e) {
        formError.value = e.response?.data?.message ?? 'Error al guardar.'
    } finally {
        saving.value = false
    }
}

async function deleteAuthor(id) {
    if (!confirm('¿Estás seguro de eliminar este autor?')) return
    try {
        await api.delete(`/authors/${id}`)
        fetchAuthors(currentPage.value)
    } catch (e) {
        alert('No se pudo eliminar el autor.')
    }
}

function changePage(page) {
    fetchAuthors(page)
}

async function handleLogout() {
    await authStore.logout()
    router.push('/login')
}

onMounted(() => fetchAuthors())
</script>
