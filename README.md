# El Sabelotodo

El Sabelotodo es una aplicacion web interactiva tipo trivial disenada para poner a prueba los conocimientos de los usuarios mediante diferentes modos de juego, incluyendo un modo 1vs1 en tiempo real y un modo individual contrarreloj. Cuenta con un sistema de logros, perfiles de usuario y un panel de administracion completo.

## Caracteristicas Principales

*   **Modos de Juego:**
    *   **1vs1 en tiempo real:** Compite contra otros jugadores en salas privadas o publicas.
    *   **Modo Individual:** Responde la mayor cantidad de preguntas en el menor tiempo posible para escalar en el ranking.
*   **Gestion de Usuarios y Roles:** Sistema de autenticacion seguro, con roles (Administrador y Usuario) gestionados a traves de Spatie Permission.
*   **Gamificacion:** Sistema de puntuacion global, ranking de los mejores jugadores y un sistema de Logros que otorga puntos extra al cumplir ciertos retos.
*   **Panel de Administracion:** Un CRUD completo para gestionar usuarios, roles, permisos, categorias y las preguntas del trivial.
*   **Diseno Premium y Responsive:** Interfaz moderna y atractiva adaptada a dispositivos moviles (verificado en iPhone 14 Pro) y escritorio.

## Arquitectura y Tecnologias Utilizadas

Este proyecto se basa en una arquitectura cliente-servidor integrada en el mismo repositorio:

### Frontend (Client-side)
*   **Vue 3 (Composition API):** Framework para construir la interfaz de usuario.
*   **Pinia:** Gestor de estado global para manejar la informacion de la aplicacion y la sesion del usuario de forma reactiva.
*   **Tailwind CSS & PrimeVue:** Para la maquetacion responsiva y diseno de componentes.
*   **Axios:** Para la comunicacion asincrona (AJAX) con la API del servidor, gestionando los tokens Bearer para la seguridad.
*   **Composables:** Uso de composables para encapsular y reutilizar la logica de las llamadas a la API (ej. `useRooms`, `useLogros`).

### Backend (Server-side)
*   **Laravel 10:** Framework PHP para la creacion de la API RESTful.
*   **Eloquent ORM:** Gestion de base de datos relacional y relaciones N:M complejas.
*   **Spatie Media Library:** Para la gestion, subida y optimizacion de imagenes asociadas a modelos (avatares de usuario).
*   **Spatie Permission:** Para el control estricto de roles y permisos.

---

## Manual de Instalacion y Despliegue

Sigue estos pasos para desplegar el proyecto en tu entorno local de desarrollo. 
Requisitos previos: PHP, Composer, Node.js y MySQL/MariaDB.

### 1. Clonar el repositorio
```bash
git clone <url-del-repositorio>
cd ELSABELOTODO
```

### 2. Configuracion del Backend (Laravel)
Instala las dependencias de PHP mediante Composer:
```bash
composer install
```

Configura las variables de entorno. Duplica el archivo de ejemplo y renombralo a `.env`:
```bash
cp .env.example .env
```

Abre el archivo `.env` recien creado y configura los datos de conexion a tu base de datos local:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=elsabelotodo
DB_USERNAME=root
DB_PASSWORD=
```

Genera la clave de encriptacion de la aplicacion y crea el enlace simbolico para que las imagenes sean accesibles publicamente:
```bash
php artisan key:generate
php artisan storage:link
```

Migra la base de datos y ejecuta los seeders. Este paso es fundamental para cargar la estructura de la base de datos, las preguntas iniciales, los roles y los usuarios de prueba:
```bash
php artisan migrate:fresh --seed
```

### 3. Configuracion del Frontend (Vue)
Instala las dependencias de Node.js necesarias para compilar los assets del frontend:
```bash
npm install
```

### 4. Ejecucion del Proyecto
Para que la aplicacion funcione correctamente, debes levantar ambos servidores de desarrollo simultaneamente en dos terminales distintas.

**Terminal 1 (Servidor Backend de Laravel):**
```bash
php artisan serve
```
El servidor backend estara disponible en `http://localhost:8000`.

**Terminal 2 (Servidor Frontend con Vite):**
```bash
npm run dev
```

---

## Usuarios de Prueba (Seeders)

La base de datos se inicializa con los siguientes usuarios para facilitar el acceso y las pruebas del sistema. La contrasena por defecto para todos ellos es `12345678`.

*   **Usuario Administrador:** `admin@demo.com` (Acceso total al panel de administracion y CRUDs)
*   **Usuario Estandar:** `user@demo.com` (Acceso a las funciones estandar de juego y perfil)
