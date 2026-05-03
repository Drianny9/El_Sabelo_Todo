<script setup>
import { onMounted, watch, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import useGame from '@/composables/games';
import ProgressSpinner from 'primevue/progressspinner';

const route = useRoute();
const router = useRouter();

// Obtenemos el código de la sala de los parámetros de la ruta, si existe.
const props = defineProps({
    code: {
        type: String,
        default: null
    }
});

const respondido = ref(false);
const opcionSeleccionada = ref(null);

const {
    loading,
    gameover,
    puntuacion,
    currentQuestion,
    preguntaActualIndex,
    questions,
    nuevosLogros,
    fetchQuestions,
    procesarRespuesta,
    avanzarPregunta,
    reiniciarPartida
} = useGame(props.code); // Pasamos el código de la sala al composable

onMounted(fetchQuestions);

// Observador para redirigir automáticamente cuando la partida 1vs1 termina
watch(gameover, (newValue) => {
    // Si el juego ha terminado y tenemos un código de sala (estamos en 1vs1)
    if (newValue && props.code) {
        //ponemos un timeout pequeño para que el jugador vea la transición y no se le corte de golpe
        setTimeout(() => {
            router.push({ 
                name: 'game.1vs1.results', 
                params: { code: props.code },
                query: { score: puntuacion.value } // Pasamos la puntuación en la query
            });
        }, 1500);
    }
});

const manejarSeleccionRespuesta = (option) => {
    if (respondido.value) return;

    opcionSeleccionada.value = option;
    respondido.value = true;
    procesarRespuesta(option);
};

const siguientePregunta = () => {
    avanzarPregunta();
    opcionSeleccionada.value = null;
    respondido.value = false;
};

const claseOpcion = (option) => {
    if (!respondido.value) {
        return 'from-green-400 to-green-500 hover:from-green-300 hover:to-green-400 text-green-950 border-green-300 shadow-[0_6px_0_#15803d] hover:shadow-[0_2px_0_#15803d]';
    }
    if (option.id === opcionSeleccionada.value.id) {
        return option.es_correcta 
            ? 'from-green-500 to-green-600 text-white border-green-400 shadow-[0_2px_0_#15803d] ring-4 ring-green-300 translate-y-1' 
            : 'from-red-500 to-red-600 text-white border-red-400 shadow-[0_2px_0_#991b1b] ring-4 ring-red-300 translate-y-1';
    }
    if (option.es_correcta) {
        return 'from-green-500 to-green-600 text-white border-green-400 shadow-[0_2px_0_#15803d] ring-4 ring-green-300';
    }
    return 'from-gray-200 to-gray-300 text-gray-700 border-gray-200 shadow-[0_2px_0_#4b5563] opacity-60 translate-y-1';
};

</script>

<template>
    <div class="relative min-h-screen bg-gradient-to-b from-purple-900 via-indigo-900 to-purple-900 text-white flex items-center justify-center p-4 overflow-hidden">
        
        <!-- Elementos 3D de fondo -->
        <img src="/images/Imagenes-Fondo/Interrogante.webp" alt="Interrogante" class="absolute top-10 left-10 w-32 md:w-48 opacity-40 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />
        <img src="/images/Imagenes-Fondo/trofeo.webp" alt="Trofeo" class="absolute bottom-10 right-10 w-40 md:w-56 opacity-30 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />
        
        <!-- Contenedor Principal Premium "Tarjeta" -->
        <div class="relative w-full max-w-3xl bg-white text-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.5)] rounded-[3rem] border-8 border-purple-300/30 overflow-hidden">
            
            <!-- Encabezado / Avatares 1vs1 -->
            <div class="bg-gradient-to-r from-purple-100 to-white px-8 py-6 relative border-b-2 border-gray-100">
                <div v-if="code" class="text-center">
                    <h1 class="text-xl md:text-2xl font-black sans-serif text-purple-700 tracking-wider uppercase mb-4 shadow-sm">1 VS 1 - Partida en Duelo</h1>
                    
                    <!-- Avatares 1VS1 -->
                    <div class="flex justify-center items-center gap-4 md:gap-8 mt-2">
                        <div class="relative">
                             <div class="w-20 h-20 md:w-28 md:w-28 shadow-xl rounded-full bg-purple-100 border-4 border-yellow-400 overflow-hidden z-10">
                                <img src="/images/Home/Avatar_solitario.webp" alt="Jugador 1" class="w-full h-full object-cover" />
                             </div>
                             <div class="absolute -bottom-2 -left-2 bg-purple-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md z-20">Tú</div>
                        </div>
                        
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-gray-300 to-gray-500 flex items-center justify-center shadow-inner flex-shrink-0 border-2 border-gray-400 z-0 scale-110">
                            <span class="text-white font-black text-2xl italic drop-shadow-md tracking-tighter">VS</span>
                        </div>
                        
                        <div class="relative">
                            <div class="w-20 h-20 md:w-28 md:w-28 shadow-xl rounded-full bg-purple-100 border-4 border-gray-300 overflow-hidden z-10">
                                <img src="/images/Home/Avatar_solitario.webp" alt="Jugador 2" class="w-full h-full object-cover" />
                            </div>
                            <div class="absolute -bottom-2 -right-2 bg-gray-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md z-20">Rival</div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center">
                    <h1 class="text-2xl md:text-3xl font-black sans-serif text-purple-700 tracking-wider uppercase drop-shadow-sm">El Sabelotodo - Partida Individual</h1>
                </div>
            </div>

            <div class="p-8 md:p-10">
                <!-- Estado de Carga -->
                <div v-if="loading" class="flex flex-col items-center justify-center p-10">
                    <ProgressSpinner />
                    <p class="mt-4 text-purple-800 font-bold sans-serif text-lg animate-pulse">Cargando la magia...</p>
                </div>

                <!-- Estado de Juego Terminado (Individual) -->
                <div v-else-if="gameover && !code" class="text-center py-6">
                    <h2 class="text-4xl font-black text-purple-800 mb-2 uppercase tracking-wide">¡Partida Terminada!</h2>
                    <p class="text-gray-500 font-bold text-lg mb-6 uppercase tracking-wider">Puntuación Final</p>
                    <div class="inline-block bg-gradient-to-br from-yellow-300 to-yellow-500 rounded-[2rem] px-12 py-6 shadow-[0_10px_20px_rgba(234,179,8,0.3)] mb-10 border-4 border-yellow-200">
                        <p class="text-6xl font-black text-yellow-900 drop-shadow-md">{{ puntuacion }}</p>
                    </div>

                    <!-- Notificación de Logros Desbloqueados -->
                    <div v-if="nuevosLogros.length > 0" class="mb-8 space-y-3">
                        <div v-for="(logro, index) in nuevosLogros" :key="index"
                             class="mx-auto max-w-md bg-gradient-to-r from-purple-800 to-indigo-900 border-2 border-purple-400 rounded-2xl p-4 shadow-[0_8px_0_#4c1d95] animate-bounce-in text-white relative"
                             :style="{ animationDelay: (index * 0.3) + 's' }">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-yellow-400 flex items-center justify-center shadow-inner border-2 border-yellow-200">
                                    <i :class="logro.icono || 'pi pi-star'" class="text-2xl text-yellow-900 drop-shadow-sm"></i>
                                </div>
                                <div class="text-left flex-1">
                                    <p class="text-yellow-300 text-[10px] font-black uppercase tracking-widest mb-1">¡Logro Desbloqueado!</p>
                                    <p class="text-white font-black text-base leading-tight">{{ logro.nombre }}</p>
                                </div>
                                <span v-if="logro.puntos" class="bg-yellow-400 text-yellow-900 text-xs font-black px-3 py-1.5 rounded-full shadow-sm">+{{ logro.puntos }} pts</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row justify-center gap-4 mt-8">
                        <button @click="reiniciarPartida" class="bg-gradient-to-b from-yellow-300 to-yellow-500 hover:from-yellow-200 hover:to-yellow-400 text-yellow-900 font-black sans-serif px-8 py-4 rounded-full border-2 border-yellow-200 shadow-[0_6px_0_#b45309] hover:shadow-[0_2px_0_#b45309] hover:translate-y-1 transition-all uppercase tracking-wider flex items-center justify-center gap-2">
                            <i class="pi pi-replay font-black"></i> Jugar de Nuevo
                        </button>
                        <button @click="router.push({ name: 'home' })" class="bg-gradient-to-b from-blue-400 to-blue-500 hover:from-blue-300 hover:to-blue-400 text-blue-950 font-black sans-serif px-8 py-4 rounded-full border-4 border-blue-300/50 shadow-[0_8px_0_#1d4ed8] hover:shadow-[0_5px_0_#1d4ed8] hover:translate-y-1 active:shadow-[0_0px_0_#1d4ed8] active:translate-y-3 transition-all duration-150 uppercase tracking-wider flex items-center justify-center gap-2">
                            <i class="pi pi-home font-black"></i> Volver al Menú
                        </button>
                    </div>
                </div>

                <!-- Estado de Juego Terminado (1vs1) -->
                <div v-else-if="gameover && code" class="text-center py-10">
                    <h2 class="text-4xl font-black text-purple-800 mb-2 uppercase tracking-wide">¡Batalla Finalizada!</h2>
                    <p class="text-gray-500 font-bold text-lg mb-6 uppercase tracking-wider">Tu puntuación es:</p>
                    <div class="inline-block bg-gradient-to-br from-yellow-300 to-yellow-500 rounded-[2rem] px-12 py-6 shadow-[0_10px_20px_rgba(234,179,8,0.3)] mb-10 border-4 border-yellow-200">
                        <p class="text-6xl font-black text-yellow-900 drop-shadow-md">{{ puntuacion }}</p>
                    </div>
                    <div>
                        <button @click="() => router.push({ name: 'game.1vs1.results', params: { code: props.code }, query: { score: puntuacion } })" class="bg-gradient-to-b from-green-400 to-green-500 hover:from-green-300 hover:to-green-400 text-green-950 font-black sans-serif px-10 py-5 rounded-full border-4 border-green-300/50 shadow-[0_8px_0_#15803d] hover:shadow-[0_3px_0_#15803d] hover:translate-y-1.5 transition-all text-xl uppercase tracking-wider flex items-center justify-center gap-3 mx-auto">
                            <i class="pi pi-chart-bar font-black"></i> Ver Resultados
                        </button>
                    </div>
                </div>

                <!-- Estado de Juego Activo -->
                <div v-else-if="!gameover && questions.length > 0">
                    <div v-if="currentQuestion">
                        <!-- Estadísticas Puntuación y Pregunta -->
                        <div class="flex justify-between items-center mb-6 px-2">
                            <div class="bg-purple-100 rounded-full px-5 py-2 border-2 border-purple-200 flex items-center gap-2 shadow-inner">
                                <span class="text-purple-600 font-black text-sm uppercase tracking-wider">Puntos</span>
                                <span class="font-black text-purple-900 text-lg">{{ puntuacion }}</span>
                            </div>
                            <div class="bg-gray-100 rounded-full px-5 py-2 border-2 border-gray-200 flex items-center gap-2 shadow-inner">
                                <span class="text-gray-500 font-black text-sm uppercase tracking-wider">Pregunta</span>
                                <span class="font-black text-gray-800 text-lg">{{ preguntaActualIndex + 1 }}<span class="text-sm font-bold text-gray-400 mx-1">/</span>{{ questions.length }}</span>
                            </div>
                        </div>

                        <!-- Enunciado de la Pregunta -->
                        <div class="text-center my-10 bg-gray-50 p-6 rounded-[2rem] shadow-sm border border-gray-100">
                            <p class="text-2xl md:text-3xl font-black text-gray-800 leading-tight">{{ currentQuestion.enunciado }}</p>
                        </div>
                        
                        <!-- Opciones de Respuesta -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                            <button
                                v-for="option in currentQuestion.opciones"
                                :key="option.id"
                                @click="manejarSeleccionRespuesta(option)"
                                :class="[
                                    'w-full font-black sans-serif text-lg md:text-xl py-5 px-6 rounded-full border-4 transition-all duration-200 uppercase tracking-wide bg-gradient-to-b',
                                    respondido ? claseOpcion(option) : 'from-green-400 to-green-500 hover:from-green-300 hover:to-green-400 text-green-950 border-green-300/50 shadow-[0_8px_0_#15803d] hover:shadow-[0_3px_0_#15803d] hover:translate-y-1.5'
                                ]"
                                :disabled="respondido"
                            >
                                {{ option.texto }}
                            </button>
                        </div>

                        <!-- Botón de Siguiente/Finalizar (Solo visible tras responder) -->
                        <div v-if="respondido" class="text-center flex justify-center mt-10 animate-fade-in">
                            <button
                                v-if="preguntaActualIndex < questions.length - 1"
                                @click="siguientePregunta"
                                class="bg-gradient-to-b from-yellow-300 to-yellow-400 hover:from-yellow-200 hover:to-yellow-300 text-yellow-900 font-black sans-serif px-10 py-4 rounded-full border-2 border-yellow-200 shadow-[0_8px_0_#b45309] hover:shadow-[0_3px_0_#b45309] hover:translate-y-1.5 transition-all text-xl uppercase tracking-wider flex items-center gap-3"
                            >
                                Siguiente <i class="pi pi-arrow-right font-black"></i>
                            </button>
                            <button
                                v-else
                                @click="siguientePregunta"
                                class="bg-gradient-to-b from-yellow-300 to-yellow-400 hover:from-yellow-200 hover:to-yellow-300 text-yellow-900 font-black sans-serif px-10 py-4 rounded-full border-2 border-yellow-200 shadow-[0_8px_0_#b45309] hover:shadow-[0_3px_0_#b45309] hover:translate-y-1.5 transition-all text-xl uppercase tracking-wider flex items-center gap-3"
                            >
                                Finalizar <i class="pi pi-check font-black"></i>
                            </button>
                        </div>
                    </div>
                </div>
                 <div v-else-if="!loading && questions.length === 0" class="text-center p-12 bg-red-50 rounded-[2rem] border-2 border-red-200">
                    <h2 class="text-2xl font-black text-red-600 mb-2 uppercase">¡Error de Conexión!</h2>
                    <p class="text-red-400 font-bold">No se pudieron cargar las preguntas del Sabelotodo.<br>Inténtalo de nuevo más tarde.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Utilidades personalizadas extra */
.animate-fade-in {
    animation: fadeIn 0.4s ease-in-out forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
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
