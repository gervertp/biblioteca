# Biblioteca — API

API REST para el sistema de gestión de biblioteca. Construida con Laravel 13 y autenticación basada en tokens con Laravel Sanctum.

> Este proyecto es el backend del sistema. El frontend está en [biblioteca/frontend](../frontend).

## Tecnologías

- PHP 8.5
- Laravel 13
- MySQL
- Laravel Sanctum
- Docker + Laravel Sail
- PHPUnit

## Instalación

### Requisitos

- Docker y Docker Compose

### Pasos

```bash
# Instalar dependencias PHP
composer install

# Copiar configuración
cp .env.example .env

# Levantar Docker
./vendor/bin/sail up -d

# Generar clave de la aplicación
./vendor/bin/sail artisan key:generate

# Crear tablas y cargar datos de prueba
./vendor/bin/sail artisan migrate --seed
```

El servidor estará disponible en `http://localhost`

## Datos de prueba

El seeder carga automáticamente:

- 1 usuario administrador
- 500 autores
- 2000 libros
- 500 socios

**Credenciales del administrador:**
```
Email:      admin@biblioteca.com
Contraseña: password
```

## Endpoints

Todas las rutas tienen el prefijo `/api`.

### Autenticación

| Método | Ruta | Auth | Descripción |
|--------|------|------|-------------|
| POST | `/login` | No | Iniciar sesión |
| POST | `/logout` | Sí | Cerrar sesión |

### Libros

| Método | Ruta | Auth | Descripción |
|--------|------|------|-------------|
| GET | `/books` | No | Listar libros (paginado) |
| GET | `/books?search=texto` | No | Buscar por título, autor o género |
| GET | `/books/{id}` | No | Ver un libro |
| POST | `/books` | Sí | Crear libro |
| PUT | `/books/{id}` | Sí | Editar libro |
| DELETE | `/books/{id}` | Sí | Eliminar libro |

### Autores

| Método | Ruta | Auth | Descripción |
|--------|------|------|-------------|
| GET | `/authors` | No | Listar autores (paginado) |
| GET | `/authors/{id}` | No | Ver un autor |
| POST | `/authors` | Sí | Crear autor |
| PUT | `/authors/{id}` | Sí | Editar autor |
| DELETE | `/authors/{id}` | Sí | Eliminar autor |

### Socios

| Método | Ruta | Auth | Descripción |
|--------|------|------|-------------|
| GET | `/members` | Sí | Listar socios (paginado) |
| GET | `/members?search=texto` | Sí | Buscar por nombre, apellido o email |
| GET | `/members/{id}` | Sí | Ver un socio |
| POST | `/members` | Sí | Registrar socio |
| PUT | `/members/{id}` | Sí | Editar socio |
| DELETE | `/members/{id}` | Sí | Eliminar socio |

### Préstamos

| Método | Ruta | Auth | Descripción |
|--------|------|------|-------------|
| GET | `/loans` | No | Listar préstamos (paginado) |
| GET | `/loans/{id}` | No | Ver un préstamo |
| POST | `/loans` | Sí | Registrar préstamo |
| PUT | `/loans/{id}` | Sí | Marcar como devuelto |
| DELETE | `/loans/{id}` | Sí | Eliminar préstamo |

### Autenticación de requests

Los endpoints protegidos requieren el token en el header:

```
Authorization: Bearer {token}
```

## Estructura del proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── AuthorController.php
│   │   ├── BookController.php
│   │   ├── LoanController.php
│   │   └── MemberController.php
│   ├── Requests/          Validaciones con mensajes en español
│   └── Resources/         Formato JSON de las respuestas
├── Models/
│   ├── Autor.php
│   ├── Book.php
│   ├── Loan.php
│   ├── Member.php
│   └── User.php
database/
├── factories/             Datos falsos para tests y seeders
├── migrations/            Estructura de las tablas
└── seeders/               Carga masiva de datos
routes/
└── api.php                Rutas públicas y protegidas
tests/
└── Feature/
    └── AuthTest.php       Tests de autenticación
```

## Tests

```bash
./vendor/bin/sail artisan test --compact
```
