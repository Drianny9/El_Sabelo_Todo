# Plan de Implementación de Pinia

Este documento detalla la propuesta para extender el uso de Pinia en el proyecto, resolviendo los puntos pendientes de la checklist y añadiendo las mejoras sugeridas para la persistencia del estado en partidas y puntuaciones.

## User Review Required

> [!IMPORTANT]
> **Revisión del usuario:** Por favor, revisa los tres apartados propuestos a continuación. Confirma si estás de acuerdo con crear dos nuevos stores (Filtros y Juego) y actualizar el de autenticación, o si prefieres enfocarlo de otra manera.

## Proposed Changes

### 1. Actualización Reactiva de Puntuaciones (Auth Store)

Actualmente la puntuación se guarda en base de datos, pero en el cliente no se refleja inmediatamente en el perfil o en el menú a menos que se vuelva a recargar el usuario (`fetchUser`).
**Plan:**
- Aprovecharemos el `authStore` existente en `resources/js/store/auth.js`.
- Añadiremos una acción `incrementScore(points)` o actualizaremos `auth.user.puntuacion` desde el composable `games.js` cuando se termine una partida individual (`guardarPuntuacion`).
- **Beneficio:** Al terminar una partida, el número de puntos en la UI (perfil, cabecera si la hay) subirá mágicamente al instante sin hacer peticiones extra.

### 2. Persistencia en el Modo 1vs1 (Game Store)

En el modo 1vs1, si el usuario recarga la página por error o le da al botón "atrás", la variable local `roomCode` de `rooms.js` se pierde y la partida queda inaccesible.
**Plan:**
- Crear un nuevo store de Pinia: `resources/js/store/game.js`.
- Este store tendrá `{ persist: true }` (usando SessionStorage o LocalStorage) y guardará:
  - `activeRoomCode`: El código de la sala en curso.
  - `isRoomCreator`: Si el jugador fue quien creó la sala.
- Si el usuario navega por error, el componente `Lobby.vue` podrá leer el store y decir: *"Tienes una partida en curso en la sala XYZ, ¿quieres volver a entrar?"*.

### 3. Guardado de Filtros de Usuario (Filters Store)

Para cubrir el punto "Filtrado en Cliente y persistencia" de la rúbrica.
**Plan:**
- Crear un nuevo store: `resources/js/store/filters.js`.
- Guardará los parámetros de búsqueda de las vistas públicas (ej: el filtro actual en el listado de logros o preguntas públicas, o el orden del ranking).
- **Beneficio:** Si un usuario ordena el ranking "de menor a mayor", navega a jugar, y vuelve al ranking, este mantendrá su filtro guardado.

---

### Archivos a Modificar / Crear

#### [MODIFY] `resources/js/store/auth.js`
- Añadir método `updateScore(newTotal)` o `addScore(points)`.

#### [MODIFY] `resources/js/composables/games.js`
- Importar `authStore` y, en `guardarPuntuacion()`, sumar los aciertos al estado local del usuario.

#### [NEW] `resources/js/store/game.js`
- Crear el store para persistir la partida 1vs1 activa.

#### [MODIFY] `resources/js/composables/rooms.js` y `Lobby.vue`
- Conectar la creación/unión de salas al nuevo `gameStore` para no perder el código.

#### [NEW] `resources/js/store/filters.js`
- Crear store para persistir los textos y selects de filtrado.

## Verification Plan

### Manual Verification
1. **Prueba de Puntuación:** Jugar una partida individual, sacar 3 puntos. Ir al perfil y comprobar que los puntos han sumado instantáneamente sin refrescar la página completa.
2. **Prueba 1vs1:** Crear una sala 1vs1, obtener el código. Ir a la ruta `/` (Home), luego volver al `/1vs1` y verificar que la sala no se ha perdido.
3. **Prueba de Filtros:** Buscar en el listado de ranking o un CRUD de tabla, cambiar de página y volver. El filtro de texto debe seguir escrito.
