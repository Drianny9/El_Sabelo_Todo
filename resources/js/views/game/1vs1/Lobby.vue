<template>
    <div class="flex justify-center items-center min-h-screen bg-gray-900 text-white p-4">
        <Card class="w-full max-w-4xl bg-gray-800 shadow-lg rounded-lg">
            <template #title>
                <div class="text-center text-2xl font-bold text-yellow-400">
                    Lobby 1vs1
                </div>
            </template>
            <template #content>
                <!-- Botones Cabecera -->
                <div class="flex flex-col sm:flex-row justify-center gap-6 mb-8 mt-4">
                    <Button @click="showCreateModal = true" label="Crear Partida" icon="pi pi-plus" class="p-button-xl p-button-success shadow-md transition-transform hover:scale-105" />
                    <Button @click="showSearchModal = true" label="Buscar Partida" icon="pi pi-search" class="p-button-xl p-button-info shadow-md transition-transform hover:scale-105" />
                </div>

                <Divider align="center" type="dashed">
                    <b class="text-gray-400">Salas Públicas Disponibles</b>
                </Divider>

                <!-- Listado de Salas Públicas -->
                <div class="flex flex-col mt-6">
                    <div v-if="loadingRooms" class="flex justify-center my-8">
                        <ProgressSpinner style="width: 50px; height: 50px" />
                    </div>
                    <div v-else-if="openRooms.length === 0" class="text-center text-gray-400 my-8 text-lg">
                        <i class="pi pi-inbox text-4xl mb-4 block text-gray-500"></i>
                        No hay salas públicas abiertas en este momento. ¡Anímate a crear una!
                    </div>
                    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4 overflow-y-auto max-h-[400px] pr-2 custom-scrollbar">
                        <div v-for="room in openRooms" :key="room.id"
                            class="flex justify-between items-center bg-gray-700 p-5 rounded-lg border border-gray-600 hover:border-yellow-400 hover:bg-gray-600 transition-all cursor-pointer shadow-sm group"
                            @click="confirmJoin(room)">
                            <div>
                                <span class="font-bold text-xl block text-blue-300 group-hover:text-yellow-400 transition-colors">{{ room.name }}</span>
                                <span class="text-sm text-gray-400 mt-1 block"><i class="pi pi-user mr-1 text-xs"></i> Creador: {{ room.player1?.name || 'Jugador' }}</span>
                            </div>
                            <div class="flex items-center justify-center bg-gray-800 rounded-full h-10 w-10 group-hover:bg-yellow-400 transition-colors">
                                <i class="pi pi-chevron-right text-lg text-gray-400 group-hover:text-gray-900"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal: Crear Partida -->
                <Dialog v-model:visible="showCreateModal" header="Crear Nueva Partida" :modal="true" :style="{width: '450px'}" :pt="{root: {class: 'bg-gray-800 text-white rounded-xl overflow-hidden shadow-2xl'}, header: {class: 'bg-gray-800 text-white border-b border-gray-700'}, content: {class: 'bg-gray-800 p-6'}}">
                    <div class="flex flex-col gap-6 mt-2">
                        <div class="flex flex-col gap-2">
                            <label class="text-gray-300 font-semibold">Nombre de la Sala (Opcional)</label>
                            <InputText v-model="newRoom.name" placeholder="Ej: Sala de Mates" maxlength="20" class="w-full bg-gray-700 text-white border-gray-600 focus:border-blue-500" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-gray-300 font-semibold">Tipo de Acceso</label>
                            <select v-model="newRoom.is_public" class="w-full p-3 rounded-md bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-blue-500 transition-colors cursor-pointer appearance-none">
                                <option :value="true">🌍 Pública (Visible en la lista)</option>
                                <option :value="false">🔒 Privada (Oculta, acceso por código)</option>
                            </select>
                        </div>
                        
                        <div v-if="roomCode" class="mt-4 p-6 border border-green-500 bg-green-900 rounded-lg text-center animate-fade-in">
                            <p class="text-green-300 mb-2 font-medium">¡Sala creada! Comparte este código si es privada:</p>
                            <h3 class="text-4xl font-bold tracking-widest text-white">{{ roomCode }}</h3>
                            <Button @click="startGame(roomCode)" label="Entrar a jugar" icon="pi pi-play" class="p-button-success w-full mt-6 p-button-lg rounded-full" />
                        </div>
                    </div>
                    <template #footer v-if="!roomCode">
                        <div class="flex justify-end gap-3 mt-4 border-t border-gray-700 pt-4">
                            <Button label="Cancelar" icon="pi pi-times" @click="showCreateModal = false" class="p-button-text text-gray-400 hover:text-white" />
                            <Button label="Crear Sala" icon="pi pi-check" @click="createRoom" autofocus :loading="isCreating" class="p-button-success px-6" />
                        </div>
                    </template>
                </Dialog>

                <!-- Modal: Buscar Partida -->
                <Dialog v-model:visible="showSearchModal" header="Búsqueda Manual" :modal="true" :style="{width: '450px'}" :pt="{root: {class: 'bg-gray-800 text-white rounded-xl shadow-2xl'}, header: {class: 'bg-gray-800 text-white border-b border-gray-700'}, content: {class: 'bg-gray-800 p-6'}}">
                    <div class="flex flex-col gap-4 mt-2">
                        <p class="text-gray-400 text-center mb-2">Introduce el <strong class="text-yellow-400">código secreto</strong> o el <strong class="text-blue-300">nombre exacto</strong> de la sala a la que deseas unirte.</p>
                        <InputText v-model="joinSearchQuery" placeholder="Código o Nombre" class="w-full text-center text-xl tracking-wider py-4 bg-gray-700 text-white border-gray-600 focus:border-blue-500 rounded-lg" @keyup.enter="joinRoom(joinSearchQuery)" />
                    </div>
                    <template #footer>
                        <div class="flex justify-end gap-3 mt-4 border-t border-gray-700 pt-4">
                            <Button label="Cancelar" icon="pi pi-times" @click="showSearchModal = false" class="p-button-text text-gray-400 hover:text-white" />
                            <Button label="Unirse" icon="pi pi-sign-in" @click="joinRoom(joinSearchQuery)" autofocus :loading="isJoining" class="p-button-info px-6" />
                        </div>
                    </template>
                </Dialog>

                <!-- Modal: Confirmación -->
                <Dialog v-model:visible="showConfirmModal" header="Confirmar Entrada" :modal="true" :style="{width: '400px'}" :pt="{root: {class: 'bg-gray-800 text-white rounded-xl shadow-2xl'}, header: {class: 'bg-gray-800 text-white border-b border-gray-700'}, content: {class: 'bg-gray-800 p-6'}}">
                    <div class="text-center mt-2" v-if="selectedRoom">
                        <i class="pi pi-question-circle text-6xl text-yellow-500 mb-4 animate-bounce"></i>
                        <p class="text-lg text-gray-300">¿Estás seguro de que deseas entrar a jugar en:</p>
                        <p class="text-3xl font-bold text-blue-300 mt-3">{{ selectedRoom.name }} <span class="text-sm text-gray-500 font-normal block mt-1">Código: {{ selectedRoom.code }}</span></p>
                    </div>
                    <template #footer>
                        <div class="flex justify-end gap-3 mt-6 border-t border-gray-700 pt-4">
                            <Button label="Cancelar" icon="pi pi-times" @click="showConfirmModal = false" class="p-button-text text-gray-400 hover:text-white" />
                            <Button label="Sí, Entrar" icon="pi pi-check" @click="joinRoom(selectedRoom.code)" autofocus :loading="isJoining" class="p-button-success px-6" />
                        </div>
                    </template>
                </Dialog>
            </template>
        </Card>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import useRooms from '@/composables/rooms';
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Divider from 'primevue/divider';
import ProgressSpinner from 'primevue/progressspinner';
import Dialog from 'primevue/dialog';

const router = useRouter();

//Usamos el composable que encapsula toda la lógica de salas y comunicación con la API
const {
    openRooms,
    loadingRooms,
    roomCode,
    fetchRooms,
    createRoom: createRoomApi,
    joinRoom: joinRoomApi
} = useRooms();

// Modals state
const showCreateModal = ref(false);
const showSearchModal = ref(false);
const showConfirmModal = ref(false);

const joinSearchQuery = ref('');
const isCreating = ref(false);
const isJoining = ref(false);

// Create Room state
const newRoom = ref({
    name: '',
    is_public: true
});

const selectedRoom = ref(null);

let pullInterval = null;

const fetchRoomsWithLoading = async () => {
    loadingRooms.value = true;
    try {
        await fetchRooms();
    } finally {
        loadingRooms.value = false;
    }
};

onMounted(() => {
    fetchRoomsWithLoading();
    pullInterval = setInterval(fetchRooms, 5000);
});

onUnmounted(() => {
    if (pullInterval) clearInterval(pullInterval);
});

// Función para crear sala
const createRoom = async () => {
    isCreating.value = true;
    try {
        await createRoomApi(newRoom.value);
        fetchRooms(); // actualizamos la lista global por detrás
    } catch (error) {
        alert("No se pudo crear la sala.");
    } finally {
        isCreating.value = false;
    }
};

// Función para abrir la confirmación
const confirmJoin = (room) => {
    selectedRoom.value = room;
    showConfirmModal.value = true;
};

// Función para unirse a sala (desde confirmación o desde búsqueda)
const joinRoom = async (codeOrName) => {
    if (!codeOrName) {
        alert("Por favor, introduce un código o nombre válido.");
        return;
    }

    isJoining.value = true;
    try {
        const data = await joinRoomApi(codeOrName);
        showConfirmModal.value = false;
        showSearchModal.value = false;
        // El servidor ahora devuelve el código real de la sala que se emparejó (útil si buscaron por nombre)
        const matchedCode = data.code;
        router.push({ name: 'game.1vs1.play', params: { code: matchedCode } });
    } catch (error) {
        alert(error.response?.data?.message || "No se pudo unir a la sala. Verifica que existe y que hay un hueco libre.");
    } finally {
        isJoining.value = false;
    }
};

// Función para que el creador de la sala comience la partida
const startGame = (code) => {
    router.push({ name: 'game.1vs1.play', params: { code } });
};
</script>

<style scoped>
/* Scrollbar custom para la lista de salas */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #374151; /* gray-700 */
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #4B5563; /* gray-600 */
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #6B7280; /* gray-500 */
}
</style>
