<template>
    <div class="relative min-h-screen bg-gradient-to-b from-purple-900 via-indigo-900 to-purple-900 text-white flex items-center justify-center p-4 overflow-hidden pt-24 pb-20">
        
        <!-- Elementos 3D de fondo -->
        <img src="/images/Imagenes-Fondo/Interrogante.webp" alt="Interrogante" class="absolute top-10 left-10 w-32 md:w-48 opacity-40 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />
        <img src="/images/Imagenes-Fondo/Corona.webp" alt="Corona" class="absolute bottom-10 right-10 w-40 md:w-56 opacity-30 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />

        <!-- Contenedor Principal Premium "Tarjeta" -->
        <div class="relative w-full max-w-3xl bg-white text-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.5)] rounded-[3rem] border-8 border-purple-300/30 overflow-hidden z-10 flex flex-col">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-100 to-white px-8 py-6 relative border-b-2 border-purple-200 text-center">
                <h1 class="text-3xl md:text-4xl font-black text-purple-700 tracking-wider uppercase drop-shadow-sm">RESULTADOS DE LA PARTIDA</h1>
                <p class="text-purple-500 font-bold mt-2 uppercase tracking-widest text-sm">Modo Duelo 1vs1</p>
            </div>

            <!-- Content -->
            <div class="p-8 md:p-12">
                <!-- Loading State -->
                <div v-if="loading" class="flex flex-col items-center justify-center py-12">
                    <div class="relative">
                        <ProgressSpinner style="width: 80px; height: 80px" strokeWidth="4" animationDuration=".5s" />
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="pi pi-search text-2xl text-purple-500"></i>
                        </div>
                    </div>
                    <p class="mt-6 text-xl font-bold text-purple-600 animate-pulse tracking-wide uppercase">Cargando resultados...</p>
                </div>

                <!-- Error State -->
                <div v-else-if="error" class="text-center py-10 bg-red-50 rounded-[2rem] border-4 border-red-100 p-8">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="pi pi-exclamation-triangle text-4xl text-red-500"></i>
                    </div>
                    <h2 class="text-2xl font-black text-red-700 uppercase mb-2">¡Ups! Algo salió mal</h2>
                    <p class="text-red-600 font-bold">{{ error }}</p>
                    <button @click="goToLobby" class="mt-6 px-8 py-3 bg-red-600 text-white font-black rounded-full hover:bg-red-700 transition-colors uppercase tracking-wider">
                        Volver al Lobby
                    </button>
                </div>

                <!-- Results State -->
                <div v-else-if="results" class="flex flex-col items-center gap-8">
                    
                    <!-- Partida Finalizada -->
                    <div v-if="results.status === 'finished'" class="w-full space-y-8">
                        
                        <!-- Status Banner -->
                        <div class="text-center">
                            <h2 v-if="winStatus === 'win'" class="text-4xl md:text-6xl font-black text-green-500 uppercase tracking-tighter drop-shadow-sm animate-bounce-in">¡HAS GANADO! 🏆</h2>
                            <h2 v-else-if="winStatus === 'lose'" class="text-4xl md:text-6xl font-black text-red-500 uppercase tracking-tighter drop-shadow-sm animate-bounce-in">HAS PERDIDO 💀</h2>
                            <h2 v-else class="text-4xl md:text-6xl font-black text-blue-500 uppercase tracking-tighter drop-shadow-sm animate-bounce-in">¡EMPATE! 🤝</h2>
                        </div>

                        <!-- VS Display -->
                        <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 items-center bg-gray-50 p-8 rounded-[2.5rem] border-2 border-gray-100 shadow-inner">
                            
                            <!-- VS Badge -->
                            <div class="hidden md:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 bg-purple-600 text-white font-black text-2xl items-center justify-center rounded-full border-4 border-white shadow-lg z-10">
                                VS
                            </div>

                            <!-- Player 1 -->
                            <div class="flex flex-col items-center gap-4 group">
                                <div class="relative">
                                    <div class="absolute -inset-2 bg-gradient-to-tr from-purple-500 to-blue-500 rounded-full opacity-20 blur-lg group-hover:opacity-40 transition-opacity"></div>
                                    <img :src="player1Avatar" class="relative w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white shadow-xl object-cover" alt="P1 Avatar">
                                    <div v-if="results.score_p1 > results.score_p2" class="absolute -top-4 -right-4 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center border-4 border-white shadow-lg rotate-12">
                                        <i class="pi pi-star-fill text-yellow-900"></i>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p class="text-gray-400 font-black uppercase text-xs tracking-widest mb-1">Creador</p>
                                    <p class="text-2xl font-black text-gray-800 uppercase">{{ player1Name }}</p>
                                    <div class="mt-2 text-5xl font-black text-purple-600 tabular-nums">{{ player1Score }}</div>
                                </div>
                            </div>

                            <!-- Player 2 -->
                            <div class="flex flex-col items-center gap-4 group">
                                <div class="relative">
                                    <div class="absolute -inset-2 bg-gradient-to-tr from-purple-500 to-blue-500 rounded-full opacity-20 blur-lg group-hover:opacity-40 transition-opacity"></div>
                                    <img :src="player2Avatar" class="relative w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white shadow-xl object-cover" alt="P2 Avatar">
                                    <div v-if="results.score_p2 > results.score_p1" class="absolute -top-4 -right-4 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center border-4 border-white shadow-lg rotate-12">
                                        <i class="pi pi-star-fill text-yellow-900"></i>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p class="text-gray-400 font-black uppercase text-xs tracking-widest mb-1">Rival</p>
                                    <p class="text-2xl font-black text-gray-800 uppercase">{{ player2Name }}</p>
                                    <div class="mt-2 text-5xl font-black text-purple-600 tabular-nums">{{ player2Score }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Logros Section -->
                        <div v-if="nuevosLogros.length > 0" class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="h-px flex-1 bg-gray-100"></div>
                                <h3 class="text-gray-400 font-black uppercase tracking-[0.2em] text-xs">Logros Desbloqueados</h3>
                                <div class="h-px flex-1 bg-gray-100"></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div v-for="(logro, index) in nuevosLogros" :key="index"
                                     class="bg-gradient-to-br from-yellow-50 to-orange-50 border-2 border-yellow-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm animate-bounce-in"
                                     :style="{ animationDelay: (index * 0.2) + 's' }">
                                    <div class="w-12 h-12 bg-yellow-400 rounded-xl flex items-center justify-center shadow-[0_4px_0_#b45309] shrink-0 border-2 border-yellow-200">
                                        <i :class="logro.icono || 'pi pi-star'" class="text-2xl text-yellow-900"></i>
                                    </div>
                                    <div>
                                        <p class="text-yellow-800 font-black text-sm uppercase leading-tight">{{ logro.nombre }}</p>
                                        <p class="text-yellow-600 text-xs font-bold mt-0.5">+{{ logro.puntos }} PUNTOS EXTRA</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Esperando al Rival -->
                    <div v-else class="w-full flex flex-col items-center gap-8 py-6">
                        <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center border-4 border-blue-100 animate-pulse">
                            <i class="pi pi-hourglass text-4xl text-blue-500"></i>
                        </div>
                        <div class="text-center space-y-2">
                            <h2 class="text-3xl font-black text-gray-800 uppercase">ESPERANDO AL RIVAL...</h2>
                            <p class="text-gray-500 font-bold max-w-md">Tu puntuación ha sido guardada. Los resultados finales aparecerán cuando tu oponente termine.</p>
                        </div>
                        
                        <div class="bg-gray-50 px-10 py-6 rounded-3xl border-2 border-gray-100 shadow-inner flex flex-col items-center">
                            <p class="text-gray-400 font-black uppercase text-xs tracking-widest mb-1">Tu Puntuación</p>
                            <span class="text-6xl font-black text-purple-600">{{ finalScore }}</span>
                        </div>

                        <div class="w-full max-w-sm h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 animate-[loading_2s_infinite]"></div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 w-full mt-4">
                        <button @click="goToLobby" class="flex-1 bg-gradient-to-b from-yellow-300 to-yellow-400 hover:from-yellow-200 hover:to-yellow-300 text-yellow-900 font-black py-4 rounded-2xl transition-all shadow-[0_6px_0_#b45309] hover:shadow-[0_2px_0_#b45309] hover:translate-y-1 uppercase tracking-widest flex items-center justify-center gap-2">
                            <i class="pi pi-users"></i> Volver al Lobby
                        </button>
                        <button @click="router.push({ name: 'home' })" class="flex-1 bg-gradient-to-b from-blue-400 to-blue-500 hover:from-blue-300 hover:to-blue-400 text-blue-950 font-black py-4 rounded-2xl transition-all border-4 border-blue-300/50 shadow-[0_8px_0_#1d4ed8] hover:shadow-[0_5px_0_#1d4ed8] hover:translate-y-1 active:shadow-[0_0px_0_#1d4ed8] active:translate-y-3 uppercase tracking-widest flex items-center justify-center gap-2">
                            <i class="pi pi-home"></i> Ir al Menú
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { authStore } from '@/store/auth';
import useRooms from '@/composables/rooms';
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

const player1Avatar = computed(() => results.value?.player_1_avatar || '/images/Home/Avatar_solitario.webp');
const player2Avatar = computed(() => results.value?.player_2_avatar || '/images/Home/Avatar_solitario.webp');

const winStatus = computed(() => {
    if (!results.value || results.value.status !== 'finished') return 'waiting';
    let myScore = results.value.player_1_id === userId.value ? results.value.score_p1 : results.value.score_p2;
    let opponentScore = results.value.player_1_id === userId.value ? results.value.score_p2 : results.value.score_p1;

    if (myScore > opponentScore) return 'win';
    if (myScore < opponentScore) return 'lose';
    return 'tie';
});

let pollAttempts = 0; 
const MAX_ATTEMPTS = 60; // Aumentamos a 5 minutos (60 * 5s)

const fetchResults = async () => {
    try {
        await fetchRoomStatus(roomCode);
        if (results.value.status === 'finished') {
            loading.value = false;
            if (pollInterval) {
                clearInterval(pollInterval);
            }
        } else {
            pollAttempts++;
            if (pollAttempts >= MAX_ATTEMPTS) {
                clearInterval(pollInterval);
                error.value = "El tiempo de espera ha expirado. Tu oponente tardó demasiado.";
            }
        }
    } catch (e) {
        error.value = "No se pudieron cargar los resultados.";
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
            loading.value = false;
            pollInterval = setInterval(fetchResults, 5000);
        } else {
            loading.value = false;
        }
    });
});

onUnmounted(() => {
    if (pollInterval) {
        clearInterval(pollInterval);
    }
});
</script>

<style scoped>
@keyframes bounceIn {
    0% { opacity: 0; transform: scale(0.3); }
    50% { opacity: 1; transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); }
}

@keyframes loading {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.animate-bounce-in {
    animation: bounceIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}
</style>
