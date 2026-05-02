<template>
    <div class="relative min-h-screen bg-gradient-to-b from-purple-900 via-indigo-900 to-purple-900 text-white flex items-center justify-center p-4 overflow-hidden pt-24 pb-20">
        
        <!-- Elementos 3D de fondo -->
        <img src="/images/Imagenes-Fondo/Interrogante.webp" alt="Interrogante" class="absolute top-10 left-10 w-32 md:w-48 opacity-40 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />
        <img src="/images/Imagenes-Fondo/Corona.webp" alt="Corona" class="absolute bottom-10 right-10 w-40 md:w-56 opacity-30 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />
        
        <!-- Contenedor Principal Premium "Tarjeta" -->
        <div class="relative w-full max-w-4xl bg-white text-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.5)] rounded-[3rem] border-8 border-purple-300/30 overflow-hidden z-10 flex flex-col">
            
            <div class="bg-gradient-to-r from-purple-100 to-white px-8 py-6 relative border-b-2 border-purple-200 text-center">
                <h1 class="text-3xl md:text-4xl font-black sans-serif text-purple-700 tracking-wider uppercase drop-shadow-sm">Lobby 1 VS 1</h1>
                <p class="text-purple-500 font-bold mt-2 uppercase tracking-widest text-sm">Arenas de Duelo</p>
            </div>

            <div class="p-6 md:p-10 flex flex-col flex-grow">
                <!-- Botones Cabecera -->
                <div class="flex flex-col sm:flex-row justify-center gap-6 mb-10 w-full max-w-2xl mx-auto">
                    <button @click="showCreateModal = true" class="flex-1 bg-gradient-to-b from-yellow-300 to-yellow-400 hover:from-yellow-200 hover:to-yellow-300 text-yellow-900 font-black text-xl md:text-2xl py-4 rounded-full border-4 border-yellow-200/50 shadow-[0_8px_0_#b45309] hover:shadow-[0_5px_0_#b45309] hover:translate-y-1 active:shadow-[0_0px_0_#b45309] active:translate-y-3 transition-all duration-150 uppercase tracking-wider sans-serif flex items-center justify-center gap-3">
                        <i class="pi pi-plus font-black"></i> Crear Partida
                    </button>
                    <button @click="showSearchModal = true" class="flex-1 bg-gradient-to-b from-blue-400 to-blue-500 hover:from-blue-300 hover:to-blue-400 text-blue-950 font-black text-xl md:text-2xl py-4 rounded-full border-4 border-blue-300/50 shadow-[0_8px_0_#1d4ed8] hover:shadow-[0_5px_0_#1d4ed8] hover:translate-y-1 active:shadow-[0_0px_0_#1d4ed8] active:translate-y-3 transition-all duration-150 uppercase tracking-wider sans-serif flex items-center justify-center gap-3">
                        <i class="pi pi-search font-black"></i> Buscar Partida
                    </button>
                </div>
                
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-1 h-1 bg-purple-100 rounded-full"></div>
                    <span class="text-purple-400 font-black uppercase tracking-widest text-sm md:text-base">Salas Públicas</span>
                    <div class="flex-1 h-1 bg-purple-100 rounded-full"></div>
                </div>

                <!-- Listado de Salas Públicas -->
                <div class="flex flex-col flex-grow bg-gray-50/50 rounded-[2rem] border-2 border-gray-100 p-4 md:p-6 shadow-inner min-h-[300px]">
                    <div v-if="loadingRooms" class="flex flex-col items-center justify-center flex-grow py-10">
                        <ProgressSpinner style="width: 60px; height: 60px" strokeWidth="6" strokeColor="#9333EA" />
                        <p class="mt-6 text-purple-700 font-black uppercase tracking-wider animate-pulse pt-2">Buscando rivales...</p>
                    </div>
                    <div v-else-if="openRooms.length === 0" class="text-center py-10 flex-grow flex flex-col items-center justify-center">
                        <div class="bg-gray-200 w-24 h-24 rounded-full flex items-center justify-center mb-6 shadow-inner">
                            <i class="pi pi-inbox text-5xl text-gray-400 drop-shadow-sm"></i>
                        </div>
                        <h3 class="text-2xl font-black text-gray-600 mb-2 uppercase tracking-wide">No hay salas abiertas</h3>
                        <p class="text-gray-500 font-bold">¡Sé el primero en crear un duelo hoy!</p>
                    </div>
                    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-5 overflow-y-auto max-h-[350px] pr-2 custom-scrollbar">
                        <div v-for="room in openRooms" :key="room.id"
                            class="bg-white p-5 rounded-[1.5rem] border-4 border-purple-100 hover:border-yellow-400 cursor-pointer shadow-sm hover:shadow-[0_8px_20px_rgba(234,179,8,0.3)] hover:-translate-y-1 transition-all group flex justify-between items-center"
                            @click="confirmJoin(room)">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-full bg-purple-100 border-2 border-purple-300 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-sm">
                                    <img src="/images/Home/Avatar_solitario.webp" alt="Avatar" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <span class="font-black text-lg md:text-xl text-purple-900 group-hover:text-yellow-600 transition-colors uppercase tracking-wide truncate max-w-[150px] md:max-w-[200px] block">{{ room.name }}</span>
                                    <span class="text-xs md:text-sm text-gray-500 font-bold block mt-1"><i class="pi pi-user mr-1 text-purple-400"></i> Creador: <span class="text-gray-800">{{ room.player1?.name || 'Jugador' }}</span></span>
                                </div>
                            </div>
                            <div class="flex items-center justify-center bg-gray-100 rounded-full h-12 w-12 flex-shrink-0 transition-colors shadow-inner border-2 border-transparent group-hover:bg-yellow-400 group-hover:border-yellow-200 group-hover:shadow-[0_4px_0_#b45309]">
                                <i class="pi pi-bolt text-xl text-gray-400 group-hover:text-yellow-900 font-black"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal: Crear Partida -->
                <Dialog v-model:visible="showCreateModal" header="Crear Nueva Partida" :modal="true" :style="{width: '450px'}" :pt="{root: {class: 'bg-white text-gray-800 rounded-[2rem] overflow-hidden shadow-2xl border-4 border-purple-200'}, header: {class: 'bg-purple-50 text-purple-900 border-b-2 border-purple-100 p-6'}, content: {class: 'bg-white p-8'}, title: {class: 'text-2xl font-black uppercase tracking-wide'} }">
                    <div class="flex flex-col gap-6 mt-2">
                        <div class="flex flex-col gap-2">
                            <label class="text-gray-600 font-black uppercase tracking-wider text-sm">Nombre de la Sala (Opcional)</label>
                            <InputText v-model="newRoom.name" placeholder="Ej: Sala de Mates" maxlength="20" class="w-full bg-gray-50 text-gray-900 border-2 border-gray-200 focus:border-purple-500 rounded-xl py-3 px-4 shadow-inner text-lg font-bold placeholder:font-normal" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-gray-600 font-black uppercase tracking-wider text-sm">Tipo de Acceso</label>
                            <select v-model="newRoom.is_public" class="w-full p-3 rounded-xl bg-gray-50 border-2 border-gray-200 text-gray-900 focus:outline-none focus:border-purple-500 transition-colors cursor-pointer appearance-none shadow-inner font-bold text-base">
                                <option :value="true">🌍 Pública (Visible en la lista)</option>
                                <option :value="false">🔒 Privada (Oculta, con clave)</option>
                            </select>
                        </div>
                        
                        <div v-if="roomCode" class="mt-6 p-6 border-4 border-green-300 bg-green-50 rounded-[2rem] text-center shadow-inner">
                            <p class="text-green-800 mb-2 font-bold uppercase tracking-wide text-sm">¡Sala creada! Código secreto:</p>
                            <h3 class="text-5xl font-black tracking-widest text-green-600 drop-shadow-sm my-4">{{ roomCode }}</h3>
                            <button @click="startGame(roomCode)" class="w-full bg-gradient-to-b from-green-400 to-green-500 hover:from-green-300 hover:to-green-400 text-green-950 font-black text-xl py-4 rounded-full border-4 border-green-300/50 shadow-[0_6px_0_#15803d] hover:shadow-[0_4px_0_#15803d] hover:translate-y-0.5 active:shadow-none active:translate-y-2 transition-all duration-150 uppercase tracking-wider sans-serif mt-2">
                                Entrar a jugar
                            </button>
                        </div>
                    </div>
                    <template #footer v-if="!roomCode">
                        <div class="flex justify-end gap-4 mt-6 border-t-2 border-gray-100 pt-6">
                            <button @click="showCreateModal = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-black py-3 px-6 rounded-full transition-colors uppercase text-sm tracking-wider">Cancelar</button>
                            <button 
  @click="createRoom" 
  :disabled="isCreating" 
  class="bg-gradient-to-b from-yellow-300 to-yellow-400 hover:from-yellow-200 text-yellow-900 font-black py-3 px-8 rounded-full border-4 border-yellow-200/50 shadow-[0_4px_0_#b45309] hover:shadow-[0_2px_0_#b45309] hover:translate-y-0.5 active:shadow-none active:translate-y-1 transition-all uppercase text-sm tracking-wider flex items-center justify-center min-w-[140px]"
>
    <ProgressSpinner 
      v-if="isCreating" 
      style="width: 20px; height: 20px" 
      strokeWidth="6" 
      class="absolute"
    /> 
    <span :class="{'opacity-0': isCreating}">Crear Sala</span>
</button>
                        </div>
                    </template>
                </Dialog>

                <!-- Modal: Buscar Partida -->
                <Dialog v-model:visible="showSearchModal" header="Búsqueda de Duelo" :modal="true" :style="{width: '450px'}" :pt="{root: {class: 'bg-white text-gray-800 rounded-[2rem] shadow-2xl border-4 border-blue-200'}, header: {class: 'bg-blue-50 text-blue-900 border-b-2 border-blue-100 p-6'}, content: {class: 'bg-white p-8'}, title: {class: 'text-2xl font-black uppercase tracking-wide'}}">
                    <div class="flex flex-col gap-6 mt-2 text-center">
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 shadow-inner">
                            <p class="text-blue-800 text-sm font-bold leading-relaxed">Introduce el <strong class="text-yellow-600 font-black">código secreto</strong> o el <strong class="text-blue-600 font-black">nombre</strong> de la sala.</p>
                        </div>
                        <InputText v-model="joinSearchQuery" placeholder="Código o Nombre" class="w-full text-center text-2xl font-black tracking-widest py-4 bg-gray-50 text-gray-900 border-4 border-gray-200 focus:border-blue-500 rounded-xl uppercase shadow-inner placeholder:text-gray-300 placeholder:font-normal" @keyup.enter="joinRoom(joinSearchQuery)" />
                    </div>
                    <template #footer>
                        <div class="flex justify-end gap-4 mt-6 border-t-2 border-gray-100 pt-6">
                            <button @click="showSearchModal = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-black py-3 px-6 rounded-full transition-colors uppercase text-sm tracking-wider">Cancelar</button>
                            <button @click="joinRoom(joinSearchQuery)" :disabled="isJoining" class="bg-gradient-to-b from-blue-400 to-blue-500 hover:from-blue-300 text-blue-950 font-black py-3 px-8 rounded-full shadow-[0_4px_0_#1d4ed8] hover:shadow-[0_2px_0_#1d4ed8] hover:translate-y-0.5 active:shadow-none active:translate-y-1 transition-all uppercase text-sm tracking-wider flex items-center justify-center min-w-[140px]">
                                <ProgressSpinner v-if="isJoining" style="width: 20px; height: 20px" strokeWidth="6" class="absolute"/> 
                                <span :class="{'opacity-0': isJoining}">Unirse</span>
                            </button>
                        </div>
                    </template>
                </Dialog>

                <!-- Modal: Confirmación -->
                <Dialog v-model:visible="showConfirmModal" header="Confirmar Duelo" :modal="true" :style="{width: '400px'}" :pt="{root: {class: 'bg-white text-gray-800 rounded-[2rem] shadow-2xl border-4 border-purple-200'}, header: {class: 'bg-purple-50 text-purple-900 border-b-2 border-purple-100 p-6'}, content: {class: 'bg-white p-8'}, title: {class: 'text-xl font-black uppercase tracking-wide'}}">
                    <div class="text-center mt-2" v-if="selectedRoom">
                        <div class="mx-auto bg-yellow-100 w-20 h-20 rounded-full flex items-center justify-center mb-6 shadow-inner border-4 border-yellow-200">
                             <i class="pi pi-bolt text-5xl text-yellow-500"></i>
                         </div>
                        <p class="text-base text-gray-500 font-bold uppercase tracking-wider mb-2">Preparando Arena:</p>
                        <p class="text-3xl font-black text-purple-700 mt-1 uppercase">{{ selectedRoom.name }}</p>
                        <div class="mt-4 bg-gray-100 rounded-lg py-2 border border-gray-200 shadow-inner inline-block px-6">
                            <p class="text-sm text-gray-500 font-bold uppercase">Código: <span class="text-gray-800 tracking-wider font-black">{{ selectedRoom.code }}</span></p>
                        </div>
                    </div>
                    <template #footer>
                        <div class="flex justify-center gap-4 mt-8">
                            <button @click="showConfirmModal = false" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-black py-4 px-4 rounded-full transition-colors uppercase text-sm tracking-wider">Cancelar</button>
                            <button @click="joinRoom(selectedRoom.code)" :disabled="isJoining" class="flex-1 bg-gradient-to-b from-yellow-300 to-yellow-400 hover:from-yellow-200 text-yellow-900 font-black py-4 px-4 rounded-full shadow-[0_4px_0_#b45309] hover:translate-y-0.5 active:shadow-none active:translate-y-1 transition-all uppercase text-sm tracking-wider flex items-center justify-center">
                                <ProgressSpinner v-if="isJoining" style="width: 20px; height: 20px" strokeWidth="6" class="absolute"/> 
                                <span :class="{'opacity-0': isJoining}">¡Entrar!</span>
                            </button>
                        </div>
                    </template>
                </Dialog>
            </div>
        </div>
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
