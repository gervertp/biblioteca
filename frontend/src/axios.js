import axios from 'axios'

const api = axios.create({
    baseURL: 'http://localhost/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    }
})

api.interceptors.request.use((config) => {
    const token = localStorage.getItem('token')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
})

api.interceptors.response.use(
    (response) => response,
    (error) => {
        const isAuthRoute = ['/login', '/register'].some(path =>
            error.config?.url?.includes(path)
        )
        if (error.response?.status === 401 && !isAuthRoute) {
            localStorage.removeItem('token')
            window.location.href = '/login'
        }
        return Promise.reject(error)
    }
)

export default api
