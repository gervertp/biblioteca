<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Biblioteca</h1>
            <div class="flex gap-4 items-center">
                <RouterLink to="/books" class="text-gray-600 hover:text-blue-600">Libros</RouterLink>
                <RouterLink to="/authors" class="text-gray-600 hover:text-blue-600">Autores</RouterLink>
                <RouterLink to="/loans" class="text-blue-600 font-medium">Préstamos</RouterLink>
                <RouterLink to="/users" class="text-gray-600 hover:text-blue-600">Socios</RouterLink>
                <button @click="handleLogout" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                    Cerrar Sesión
                </button>
            </div>
        </nav>

        <div class="max-w-6xl mx-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Préstamos</h2>
                <button @click="showModal = true" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Nuevo Préstamo
                </button>
            </div>

            <div v-if="loading" class="text-gray-500">Cargando préstamos...</div>

            <div v-else>
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Usuario</th>
                                <th class="px-6 py-3">Libro</th>
                                <th class="px-6 py-3">Fecha préstamo</th>
                                <th class="px-6 py-3">Fecha límite</th>
                                <th class="px-6 py-3">Estado</th>
                                <th class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="loan in loans" :key="loan.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-800">{{ loan.member.name }} {{ loan.member.last_name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ loan.book.title }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ loan.loaned_at }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ loan.due_date }}</td>
                                <td class="px-6 py-4">
                                    <span v-if="loan.returned_at"
                                        class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-medium">
                                        Devuelto {{ loan.returned_at }}
                                    </span>
                                    <span v-else
                                        class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-medium">
                                        Pendiente
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex gap-3">
                                    <button v-if="!loan.returned_at" @click="markReturned(loan.id)"
                                        class="text-green-600 hover:underline text-xs">
                                        Marcar devuelto
                                    </button>
                                    <button @click="deleteLoan(loan.id)" class="text-red-500 hover:underline text-xs">
                                        Eliminar
                                    </button>
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

        <!-- Modal Nuevo Préstamo -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Nuevo Préstamo</h3>

                <div v-if="formError" class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">{{ formError }}</div>

                <form @submit.prevent="saveLoan" class="space-y-4">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Socio *</label>
                        <input v-model="userSearch" @input="onUserSearch" type="text" placeholder="Buscar socio por nombre o email..."
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <div v-if="userResults.length > 0"
                            class="absolute z-10 w-full bg-white border border-gray-200 rounded shadow-md mt-1 max-h-48 overflow-y-auto">
                            <div v-for="member in userResults" :key="member.id" @click="selectUser(member)"
                                class="px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm text-gray-800">
                                {{ member.name }} {{ member.last_name }} <span class="text-gray-400 text-xs">{{ member.email }}</span>
                            </div>
                        </div>
                        <p v-if="form.member_id" class="text-xs text-green-600 mt-1">✓ Socio seleccionado: {{ userSearch }}</p>
                    </div>
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Libro *</label>
                        <input v-model="bookSearch" @input="onBookSearch" type="text" placeholder="Buscar libro..."
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <div v-if="bookResults.length > 0"
                            class="absolute z-10 w-full bg-white border border-gray-200 rounded shadow-md mt-1 max-h-48 overflow-y-auto">
                            <div v-for="book in bookResults" :key="book.id" @click="selectBook(book)"
                                class="px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm text-gray-800">
                                {{ book.title }}
                            </div>
                        </div>
                        <p v-if="form.book_id" class="text-xs text-green-600 mt-1">✓ Libro seleccionado: {{ bookSearch }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de préstamo *</label>
                        <input v-model="form.loaned_at" type="date" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha límite de devolución *</label>
                        <input v-model="form.due_date" type="date" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showModal = false"
                            class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="saving"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
                            {{ saving ? 'Guardando...' : 'Registrar' }}
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

const loans = ref([])
const loading = ref(true)
const currentPage = ref(1)
const lastPage = ref(1)

const showModal = ref(false)
const saving = ref(false)
const formError = ref(null)

const userSearch = ref('')
const userResults = ref([])
const bookSearch = ref('')
const bookResults = ref([])
let userSearchTimeout = null
let bookSearchTimeout = null

const form = ref({
    member_id: '',
    book_id: '',
    loaned_at: '',
    due_date: '',
})

async function fetchLoans(page = 1) {
    loading.value = true
    try {
        const { data } = await api.get(`/loans?page=${page}`)
        loans.value = data.data
        currentPage.value = data.meta.current_page
        lastPage.value = data.meta.last_page
    } finally {
        loading.value = false
    }
}

function onUserSearch() {
    form.value.member_id = ''
    clearTimeout(userSearchTimeout)
    if (!userSearch.value.trim()) { userResults.value = []; return }
    userSearchTimeout = setTimeout(async () => {
        const { data } = await api.get('/members', { params: { search: userSearch.value } })
        userResults.value = data.data
    }, 300)
}

function selectUser(member) {
    form.value.member_id = member.id
    userSearch.value = member.name + ' ' + member.last_name
    userResults.value = []
}

function onBookSearch() {
    form.value.book_id = ''
    clearTimeout(bookSearchTimeout)
    if (!bookSearch.value.trim()) { bookResults.value = []; return }
    bookSearchTimeout = setTimeout(async () => {
        const { data } = await api.get('/books', { params: { search: bookSearch.value } })
        bookResults.value = data.data
    }, 300)
}

function selectBook(book) {
    form.value.book_id = book.id
    bookSearch.value = book.title
    bookResults.value = []
}

async function saveLoan() {
    saving.value = true
    formError.value = null
    try {
        await api.post('/loans', form.value)
        showModal.value = false
        form.value = { member_id: '', book_id: '', loaned_at: '', due_date: '' }
        userSearch.value = ''
        bookSearch.value = ''
        fetchLoans(currentPage.value)
    } catch (e) {
        formError.value = e.response?.data?.message ?? 'Error al registrar el préstamo.'
    } finally {
        saving.value = false
    }
}

async function markReturned(id) {
    const today = new Date().toISOString().split('T')[0]
    try {
        await api.put(`/loans/${id}`, { returned_at: today })
        fetchLoans(currentPage.value)
    } catch (e) {
        alert('Error al marcar la devolución.')
    }
}

async function deleteLoan(id) {
    if (!confirm('¿Estás seguro de eliminar este préstamo?')) return
    try {
        await api.delete(`/loans/${id}`)
        fetchLoans(currentPage.value)
    } catch (e) {
        alert('No se pudo eliminar el préstamo.')
    }
}

function changePage(page) {
    fetchLoans(page)
}

async function handleLogout() {
    await authStore.logout()
    router.push('/login')
}

onMounted(() => fetchLoans())
</script>
