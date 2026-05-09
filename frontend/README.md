# Biblioteca — Frontend

Interfaz web para el sistema de gestión de biblioteca. Construida con Vue 3 y Tailwind CSS, consume la API REST del backend.

> Este proyecto es el frontend del sistema. El backend está en [biblioteca/api](../api).

## Tecnologías

- Vue 3 (Composition API)
- Pinia (manejo de estado global)
- Vue Router (navegación entre páginas)
- Axios (peticiones HTTP a la API)
- Tailwind CSS (estilos)
- Vite (bundler)

## Instalación

### Requisitos

- Node.js 20+
- El backend debe estar corriendo en `http://localhost`

### Pasos

```bash
# Instalar dependencias
npm install

# Iniciar servidor de desarrollo
npm run dev
```

Abrir en el navegador: `http://localhost:5173`

### Build para producción

```bash
npm run build
```

## Credenciales de acceso

```
Email:      admin@biblioteca.com
Contraseña: password
```

## Páginas

| Ruta | Descripción | Requiere login |
|------|-------------|----------------|
| `/login` | Inicio de sesión | No |
| `/books` | Listado y gestión de libros | Sí |
| `/authors` | Listado y gestión de autores | Sí |
| `/loans` | Registro y gestión de préstamos | Sí |
| `/users` | Listado y gestión de socios | Sí |

## Características

- **Autenticación** — El token se guarda en localStorage y se envía automáticamente en cada petición
- **Guards de navegación** — Las páginas protegidas redirigen al login si no hay sesión activa
- **Búsqueda en tiempo real** — Debounce de 400ms para no saturar la API
- **Búsqueda en modales** — El modal de préstamos busca socios y libros en tiempo real dentro de la API
- **Paginación** — Navegación entre páginas en todos los listados
- **Manejo de errores** — Si el token expira, redirige automáticamente al login

## Estructura del proyecto

```
src/
├── views/
│   ├── LoginView.vue       Pantalla de inicio de sesión
│   ├── BooksView.vue       CRUD de libros
│   ├── AuthorsView.vue     CRUD de autores
│   ├── LoansView.vue       CRUD de préstamos
│   └── UsersView.vue       CRUD de socios
├── stores/
│   └── auth.js             Estado global de autenticación (Pinia)
├── router/
│   └── index.js            Rutas y guards de navegación
└── axios.js                Configuración de Axios con interceptores
```
