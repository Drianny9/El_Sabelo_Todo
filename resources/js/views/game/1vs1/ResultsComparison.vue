<template>
    <div class="flex justify-center items-center min-h-screen bg-gray-900 text-white p-4">
        <Card class="w-full max-w-2xl bg-gray-800 shadow-lg rounded-lg">
            <template #title>
                <div class="text-center text-2xl font-bold text-yellow-400">
                    Resultados de la Partida
                </div>
            </template>
            <template #content>
                <div v-if="loading" class="flex flex-col items-center justify-center p-10">
                    <ProgressSpinner />
                    <p class="mt-4 text-lg">Cargando resultados...</p>
                </div>
                <div v-else-if="error" class="text-center p-10 text-red-400">
                    <h2 class="text-2xl font-bold">Error</h2>
                    <p>{{ error }}</p>
                </div>
                <div v-else-if="results" class="text-center p-6">
                    <div v-if="results.status === 'finished'">
                        <h2 class="text-3xl font-bold mb-4 text-green-400">¡Partida Finalizada!</h2>
                        <div class="grid grid-cols-2 gap-4 text-xl">
                            <div class="p-4 bg-gray-700 rounded-lg">
                                <p class="font-semibold">{{ player1Name }} (Tú)</p>
                                <p class="text-4xl font-bold text-yellow-400">{{ myScore }}</p>
                            </div>
                            <div class="p-4 bg-gray-700 rounded-lg">
                                <p class="font-semibold">{{ player2Name }} (Rival)</p>
                                <p class="text-4xl font-bold text-yellow-400">{{ opponentScore }}</p>
                            </div>
                        </div>
                        <div class="mt-8">
                            <h3 v-if="myScore > opponentScore" class="text-2xl font-bold text-green-400">¡Has ganado!</h3>
                            <h3 v-else-if="myScore < opponentScore" class="text-2xl font-bold text-red-400">Has perdido.</h3>
                            <h3 v-else class="text-2xl font-bold text-blue-400">¡Es un empate!</h3>
                        </div>
                    </div>
                    <div v-else>
                        <h2 class="text-3xl font-bold mb-4 text-blue-400">Esperando al rival...</h2>
                        <p class="text-lg">Tu puntuación final es: <span class="text-2xl font-bold text-yellow-400">{{ finalScore }}</span></p>
                        <p class="mt-4">Los resultados se mostrarán aquí cuando tu oponente termine la partida.</p>
                        <p class="mt-2">Puedes cerrar esta ventana y volver más tarde usando la misma URL.</p>
                        <ProgressSpinner class="mt-6" />
                    </div>
                     <Button @click="goToLobby" label="Volver al Lobby" icon="pi pi-home" class="p-button-info mt-8" />
                </div>
            </template>
        </Card>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { authStore } from '@/store/auth';
import axios from 'axios';
import Card from 'primevue/card';
import Button from 'primevue/button';
import ProgressSpinner from 'primevue/progressspinner';

const route = useRoute();
const router = useRouter();
const auth = authStore();

const roomCode = route.params.code;
const finalScore = route.query.score;

const results = ref(null);
const loading = ref(true);
const error = ref('');
let pollInterval = null;

const userId = computed(() => auth.user.id);

const myScore = computed(() => {
    if (!results.value) return finalScore;
    return results.value.player_1_id === userId.value ? results.value.score_p1 : results.value.score_p2;
});

const opponentScore = computed(() => {
    if (!results.value) return '...';
    return results.value.player_1_id === userId.value ? results.value.score_p2 : results.value.score_p1;
});

const player1Name = computed(() => results.value?.player_1_id === userId.value ? 'Tú' : 'Rival');
const player2Name = computed(() => results.value?.player_2_id === userId.value ? 'Tú' : 'Rival');


const fetchResults = async () => {
    try {
        const response = await axios.get(`/api/rooms/${roomCode}/status`);
        results.value = response.data;
        if (results.value.status === 'finished') {
            loading.value = false;
            if (pollInterval) {
                clearInterval(pollInterval);
            }
        }
    } catch (e) {
        error.value = "No se pudieron cargar los resultados. Es posible que no seas parte de esta sala.";
        loading.value = false;
        if (pollInterval) {
            clearInterval(pollInterval);
        }
        console.error("Error al cargar resultados:", e);
    }
};

const goToLobby = () => {
    router.push({ name: 'game.1vs1.lobby' });
};

onMounted(() => {
    fetchResults().then(() => {
        if (results.value && results.value.status !== 'finished') {
            loading.value = false; //Dejamos de cargar para mostrar el mensaje de espera
            //Consultamos el estado cada 5 segundos
            pollInterval = setInterval(fetchResults, 5000);
        } else {
            loading.value = false;
        }
    });
});

onUnmounted(() => {
    //Limpiamos el intervalo cuando el componente se destruye para evitar fugas de memoria
    if (pollInterval) {
        clearInterval(pollInterval);
    }
});
</script>
