<template>
    <div class="flex justify-center items-center min-h-screen bg-gray-900 text-white p-4">
        <Card class="w-full max-w-4xl bg-gray-800 shadow-lg rounded-lg">
            <template #title>
                <div class="text-center text-2xl font-bold text-yellow-400">
                    Lobby 1vs1
                </div>
            </template>
            <template #content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-4">
                    <!-- Columna Izquierda: Acciones manuales -->
                    <div
                        class="flex flex-col gap-8 border-b md:border-b-0 md:border-r border-gray-600 pb-8 md:pb-0 md:pr-8">
                        <!-- Sección para Crear Partida -->
                        <div class="text-center">
                            <h2 class="text-xl mb-4 text-blue-300">Crear Nueva Partida</h2>
                            <Button @click="createRoom" label="Crear Partida" icon="pi pi-plus"
                                class="p-button-lg p-button-success w-full" :loading="isCreating" />
                        </div>

                        <!-- Mostrar el código de la sala una vez creada -->
                        <div v-if="roomCode" class="mt-4">
                            <InviteLink :code="roomCode" @start-game="startGame" />
                        </div>

                        <Divider align="center" type="dashed">
                            <b>O</b>
                        </Divider>

                        <!-- Sección para Unirse a Partida manualmente -->
                        <div class="text-center">
                            <h2 class="text-xl mb-4 text-blue-300">Unirse por Código</h2>
                            <div class="p-inputgroup">
                                <InputText v-model="joinCode" placeholder="Código de sala" @keyup.enter="joinRoom()" />
                                <Button @click="joinRoom()" icon="pi pi-sign-in" class="p-button-info"
                                    :loading="isJoining" />
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Salas abiertas -->
                    <div class="flex flex-col">
                        <h2 class="text-xl mb-4 text-center text-blue-300">Salas Disponibles</h2>
                        <div v-if="loadingRooms" class="flex justify-center my-4">
                            <ProgressSpinner style="width: 50px; height: 50px" />
                        </div>
                        <div v-else-if="openRooms.length === 0" class="text-center text-gray-400 my-4">
                            No hay salas abiertas en este momento. ¡Crea una!
                        </div>
                        <div v-else class="flex flex-col gap-3 overflow-y-auto max-h-[400px] pr-2">
                            <!-- iteramos sobre las salas que nos devuelve el servidor -->
                            <div v-for="room in openRooms" :key="room.id"
                                class="flex justify-between items-center bg-gray-700 p-3 rounded-lg border border-gray-600 hover:border-yellow-400 transition-colors">
                                <div>
                                    <span class="font-bold text-lg block">{{ room.player1?.name || 'Jugador' }}</span>
                                    <span class="text-sm text-gray-400">Código: {{ room.code }}</span>
                                </div>
                                <!-- reutilizamos el boton de unirse pasandole el codigo -->
                                <Button @click="joinRoom(room.code)" label="Unirse" icon="pi pi-play"
                                    class="p-button-sm p-button-warning" :loading="joiningCode === room.code" />
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import InviteLink from '@/components/game/InviteLink.vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Divider from 'primevue/divider';
import ProgressSpinner from 'primevue/progressspinner';

const router = useRouter();
const roomCode = ref('');
const joinCode = ref('');
const isCreating = ref(false);
const isJoining = ref(false);

//variables para la lista de salas abiertas
const openRooms = ref([]);
const loadingRooms = ref(true);
let pullInterval = null;
const joiningCode = ref(null); //para saber qué sala está cargando

//Función para traer las salas de la bbdd mediante el nuevo endpoint
const fetchRooms = async () => {
    try {
        const response = await axios.get('/api/rooms');
        openRooms.value = response.data;
    } catch (e) {
        console.error("Error al cargar las salas", e);
    } finally {
        loadingRooms.value = false;
    }
};

onMounted(() => {
    fetchRooms();
    //pedimos salas nuevas cada 5 segundos para que se actualice solo
    pullInterval = setInterval(fetchRooms, 5000);
});

onUnmounted(() => {
    //limpiamos el intervalo al salir del componente para no saturar
    if (pullInterval) clearInterval(pullInterval);
});

//Función para llamar a la API y crear una sala
const createRoom = async () => {
    isCreating.value = true;
    try {
        const response = await axios.post('/api/rooms');
        roomCode.value = response.data.code;
        fetchRooms(); //actualizamos la lista al crear
    } catch (error) {
        console.error("Error al crear la sala:", error);
        alert("No se pudo crear la sala.");
    } finally {
        isCreating.value = false;
    }
};

//Función para unirse a una sala existente (admite codigo por parametro o del input)
const joinRoom = async (codeFromList = null) => {
    const finalCode = codeFromList || joinCode.value.trim();
    if (!finalCode) {
        alert("Por favor, introduce un código de sala.");
        return;
    }

    if (codeFromList) joiningCode.value = finalCode;
    else isJoining.value = true;

    try {
        await axios.post('/api/rooms/join', { code: finalCode });
        //Si la unión es exitosa, redirigimos al jugador a la partida
        router.push({ name: 'game.1vs1.play', params: { code: finalCode } });
    } catch (error) {
        console.error("Error al unirse a la sala:", error);
        alert(error.response?.data?.message || "No se pudo unir a la sala. Verifica el código.");
    } finally {
        isJoining.value = false;
        joiningCode.value = null;
    }
};

//Función para que el creador de la sala comience la partida
const startGame = (code) => {
    router.push({ name: 'game.1vs1.play', params: { code: code } });
};
</script>
