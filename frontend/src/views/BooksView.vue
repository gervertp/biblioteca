<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Biblioteca</h1>
            <div class="flex gap-4 items-center">
                <RouterLink to="/books" class="text-blue-600 font-medium">Libros</RouterLink>
                <RouterLink to="/authors" class="text-gray-600 hover:text-blue-600">Autores</RouterLink>
                <RouterLink to="/loans" class="text-gray-600 hover:text-blue-600">Préstamos</RouterLink>
                <RouterLink to="/users" class="text-gray-600 hover:text-blue-600">Socios</RouterLink>
                <button @click="handleLogout" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                    Cerrar Sesión
                </button>
            </div>
        </nav>

        <div class="max-w-6xl mx-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Libros</h2>
                <button @click="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Nuevo Libro
                </button>
            </div>

            <div class="mb-4">
                <input v-model="search" @input="onSearch" type="text" placeholder="Buscar por título, autor o género..."
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div v-if="loading" class="text-gray-500">Cargando libros...</div>

            <div v-else>
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Título</th>
                                <th class="px-6 py-3">Autor</th>
                                <th class="px-6 py-3">Género</th>
                                <th class="px-6 py-3">Año</th>
                                <th class="px-6 py-3">Stock</th>
                                <th class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="book in books" :key="book.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ book.title }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ book.autor.name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ book.genre }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ book.published_year }}</td>
                                <td class="px-6 py-4">
                                    <span :class="book.stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                        class="px-2 py-1 rounded-full text-xs font-medium">
                                        {{ book.stock > 0 ? book.stock + ' disponibles' : 'Sin stock' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex gap-3">
                                    <button @click="openModal(book)" class="text-blue-600 hover:underline text-xs">Editar</button>
                                    <button @click="deleteBook(book.id)" class="text-red-500 hover:underline text-xs">Eliminar</button>
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
                    {{ editing ? 'Editar Libro' : 'Nuevo Libro' }}
                </h3>

                <div v-if="formError" class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">{{ formError }}</div>

                <form @submit.prevent="saveBook" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Autor *</label>
                        <select v-model="form.author_id" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecciona un autor</option>
                            <option v-for="author in allAuthors" :key="author.id" :value="author.id">
                                {{ author.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                        <input v-model="form.title" type="text" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ISBN *</label>
                        <input v-model="form.isbn" type="text" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Género *</label>
                        <input v-model="form.genre" type="text" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Año *</label>
                            <input v-model="form.published_year" type="number" required
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                            <input v-model="form.stock" type="number" min="0"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
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

const books = ref([])
const allAuthors = ref([])
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
    author_id: '',
    title: '',
    isbn: '',
    genre: '',
    published_year: '',
    stock: 1,
})

async function fetchBooks(page = 1) {
    loading.value = true
    try {
        const { data } = await api.get('/books', {
            params: { page, search: search.value || undefined }
        })
        books.value = data.data
        currentPage.value = data.meta.current_page
        lastPage.value = data.meta.last_page
    } finally {
        loading.value = false
    }
}

function onSearch() {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => fetchBooks(1), 400)
}

async function fetchAllAuthors() {
    const { data } = await api.get('/authors?page=1')
    allAuthors.value = data.data
}

function openModal(book = null) {
    editing.value = book
    formError.value = null
    form.value = book
        ? { author_id: book.autor.id, title: book.title, isbn: book.isbn, genre: book.genre, published_year: book.published_year, stock: book.stock }
        : { author_id: '', title: '', isbn: '', genre: '', published_year: '', stock: 1 }
    showModal.value = true
}

function closeModal() {
    showModal.value = false
    editing.value = null
}

async function saveBook() {
    saving.value = true
    formError.value = null
    try {
        if (editing.value) {
            await api.put(`/books/${editing.value.id}`, form.value)
        } else {
            await api.post('/books', form.value)
        }
        closeModal()
        fetchBooks(currentPage.value)
    } catch (e) {
        formError.value = e.response?.data?.message ?? 'Error al guardar.'
    } finally {
        saving.value = false
    }
}

async function deleteBook(id) {
    if (!confirm('¿Estás seguro de eliminar este libro?')) return
    try {
        await api.delete(`/books/${id}`)
        fetchBooks(currentPage.value)
    } catch (e) {
        alert('No se pudo eliminar el libro.')
    }
}

function changePage(page) {
    fetchBooks(page)
}

async function handleLogout() {
    await authStore.logout()
    router.push('/login')
}

onMounted(() => {
    fetchBooks()
    fetchAllAuthors()
})
</script>
