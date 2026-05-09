# Sistema de Gestión de Biblioteca

Sistema web completo para la gestión de una biblioteca. Permite administrar libros, autores, socios y préstamos a través de una API REST consumida por un frontend moderno.

## Estructura del proyecto

```
biblioteca/
├── api/          Backend - API REST con Laravel 13
└── frontend/     Frontend - SPA con Vue 3
```

## Funcionalidades

- **Autenticación** — Login y logout con tokens (Laravel Sanctum)
- **Libros** — Listado, búsqueda por título/autor/género, crear, editar y eliminar
- **Autores** — Gestión completa con biografía y nacionalidad
- **Socios** — Registro de personas que toman préstamos (nombre, apellido, celular, email)
- **Préstamos** — Registro de préstamos, fecha límite y marcado de devolución
- **Búsqueda en tiempo real** en libros, socios y préstamos
- **Paginación** en todos los listados

## Tecnologías

| Backend | Frontend |
|---------|----------|
| PHP 8.5 | Vue 3 |
| Laravel 13 | Pinia |
| MySQL | Vue Router |
| Laravel Sanctum | Axios |
| Docker + Sail | Tailwind CSS |
| PHPUnit | Vite |

## Instalación y uso

### Requisitos previos

- Docker y Docker Compose
- Node.js 20+

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/biblioteca.git
cd biblioteca
```

### 2. Configurar el backend

```bash
cd api

# Instalar dependencias PHP
composer install

# Copiar archivo de configuración
cp .env.example .env

# Levantar Docker
./vendor/bin/sail up -d

# Generar clave de la aplicación
./vendor/bin/sail artisan key:generate

# Ejecutar migraciones y datos de prueba
./vendor/bin/sail artisan migrate --seed
```

### 3. Configurar el frontend

```bash
cd ../frontend

# Instalar dependencias
npm install

# Iniciar servidor de desarrollo
npm run dev
```

Abrir en el navegador: `http://localhost:5173`

## Credenciales de acceso

```
Email:      admin@biblioteca.com
Contraseña: password
```

## Capturas de pantalla

> Agregar capturas aquí

## Repositorios

- [API — Backend](./api)
- [Frontend](./frontend)
