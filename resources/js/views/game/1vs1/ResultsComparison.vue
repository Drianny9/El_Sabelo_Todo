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
                                <p class="font-semibold text-white">{{ player1Name }}</p>
                                <p class="text-4xl font-bold text-yellow-400">{{ player1Score }}</p>
                            </div>
                            <div class="p-4 bg-gray-700 rounded-lg">
                                <p class="font-semibold text-white">{{ player2Name }}</p>
                                <p class="text-4xl font-bold text-yellow-400">{{ player2Score }}</p>
                            </div>
                        </div>
                        <div class="mt-8">
                            <h3 v-if="winStatus === 'win'" class="text-2xl font-bold text-green-400">¡Has ganado!</h3>
                            <h3 v-else-if="winStatus === 'lose'" class="text-2xl font-bold text-red-400">Has perdido.</h3>
                            <h3 v-else class="text-2xl font-bold text-blue-400">¡Es un empate!</h3>
                        </div>

                        <!-- Notificación de Logros Desbloqueados -->
                        <div v-if="nuevosLogros.length > 0" class="mt-8 space-y-3">
                            <div v-for="(logro, index) in nuevosLogros" :key="index"
                                 class="mx-auto max-w-md bg-gradient-to-r from-yellow-900/80 to-amber-900/80 border border-yellow-500/50 rounded-xl p-4 shadow-lg shadow-yellow-900/30 animate-bounce-in"
                                 :style="{ animationDelay: (index * 0.3) + 's' }">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-yellow-500/20 flex items-center justify-center">
                                        <i :class="logro.icono || 'pi pi-star'" class="text-xl text-yellow-400"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-yellow-400 text-xs font-bold uppercase tracking-widest">¡Logro Desbloqueado!</p>
                                        <p class="text-white font-bold text-sm">{{ logro.nombre }}</p>
                                    </div>
                                    <span v-if="logro.puntos" class="ml-auto bg-yellow-500 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full">+{{ logro.puntos }} pts</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <h2 class="text-3xl font-bold mb-4 text-blue-400">Esperando al rival...</h2>
                        <p class="text-lg">Tu puntuación final es: <span class="text-2xl font-bold text-yellow-400">{{ finalScore }}</span></p>
                        <p class="mt-4">Los resultados se mostrarán aquí cuando tu oponente termine la partida.</p>
                        <p class="mt-2">Puedes cerrar esta ventana y volver más tarde usando la misma URL.</p>
                        <ProgressSpinner class="mt-6" />
                    </div>
                    <div class="flex justify-center gap-4 mt-8">
                        <Button @click="goToLobby" label="Volver al Lobby" icon="pi pi-users" class="p-button-info" />
                        <Button @click="router.push({ name: 'home' })" label="Volver al Menú" icon="pi pi-home" class="p-button-secondary" />
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { authStore } from '@/store/auth';
import useRooms from '@/composables/rooms';
import Card from 'primevue/card';
import Button from 'primevue/button';
import ProgressSpinner from 'primevue/progressspinner';

const route = useRoute();
const router = useRouter();
const auth = authStore();

const roomCode = route.params.code;
const finalScore = route.query.score; // Se usa solo para la pantalla de espera

//Usamos el composable que encapsula la comunicación con la API de salas
const { results, nuevosLogros, fetchRoomStatus } = useRooms();

const loading = ref(true);
const error = ref('');
let pollInterval = null;

const userId = computed(() => auth.user.id);

const player1Score = computed(() => {
    if (!results.value || results.value.score_p1 === null) return '...';
    return results.value.score_p1;
});

const player2Score = computed(() => {
    if (!results.value || results.value.score_p2 === null) return '...';
    return results.value.score_p2;
});

const player1Name = computed(() => results.value?.player_1_name || 'Jugador 1');
const player2Name = computed(() => results.value?.player_2_name || 'Jugador 2');

const winStatus = computed(() => {
    if (!results.value || results.value.status !== 'finished') return 'waiting';
    let myScore = results.value.player_1_id === userId.value ? results.value.score_p1 : results.value.score_p2;
    let opponentScore = results.value.player_1_id === userId.value ? results.value.score_p2 : results.value.score_p1;

    if (myScore > opponentScore) return 'win';
    if (myScore < opponentScore) return 'lose';
    return 'tie';
});

let pollAttempts = 0; //agregamos un contador para evitar polling infinito si el rival abandona
const MAX_ATTEMPTS = 12; //12 intentos * 5 segundos = 1 minuto de espera

const fetchResults = async () => {
    try {
        await fetchRoomStatus(roomCode);
        if (results.value.status === 'finished') {
            loading.value = false;
            if (pollInterval) {
                //El otro jugador ha terminado y paramos el temporizador.
                clearInterval(pollInterval);
            }
        } else {
            //si no ha terminado, incrementamos los intentos
            pollAttempts++;
            if (pollAttempts >= MAX_ATTEMPTS) {
                //si el rival no termina en un tiempo razonable, paramos y avisamos
                clearInterval(pollInterval);
                error.value = "Parece que el oponente se ha desconectado. Finalizando espera.";
            }
        }
    } catch (e) {
        error.value = "No se pudieron cargar los resultados. Es posible que no seas parte de esta sala.";
        loading.value = false;
        if (pollInterval) {
            clearInterval(pollInterval);
        }
    }
};

const goToLobby = () => {
    router.push({ name: 'game.1vs1.lobby' });
};

onMounted(() => {
    fetchResults().then(() => {
        if (results.value && results.value.status !== 'finished' && !error.value) {
            loading.value = false; //Dejamos de cargar para mostrar el mensaje de espera
            //Consultamos el estado cada 5 segundos para verificar si el rival ha terminado
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

<style scoped>
.p-card {
    border: 2px solid #4a5568;
}

/* Animación de entrada para los banners de logros desbloqueados */
@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3) translateY(-20px);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.95);
    }
    100% {
        transform: scale(1) translateY(0);
    }
}

.animate-bounce-in {
    animation: bounceIn 0.6s ease-out both;
}
</style>
