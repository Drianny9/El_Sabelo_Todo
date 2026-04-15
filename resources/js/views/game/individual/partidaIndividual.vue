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
        return 'bg-blue-500 hover:bg-blue-700';
    }
    if (option.id === opcionSeleccionada.value.id) {
        return option.es_correcta ? 'bg-green-500' : 'bg-red-500';
    }
    if (option.es_correcta) {
        return 'bg-green-500';
    }
    return 'bg-gray-400';
};

</script>

<template>
    <div class="flex justify-center items-center min-h-screen bg-gray-900 text-white p-4">
        <Card class="w-full max-w-2xl bg-gray-800 shadow-lg rounded-lg">
            <template #title>
                <div class="text-center text-2xl font-bold text-yellow-400">
                    <span v-if="code">Partida 1vs1</span>
                    <span v-else>El Sabelotodo - Partida Individual</span>
                </div>
            </template>
            <template #content>
                <!-- Estado de Carga -->
                <div v-if="loading" class="flex flex-col items-center justify-center p-10">
                    <ProgressSpinner />
                    <p class="mt-4 text-lg">Cargando partida...</p>
                </div>

                <!-- Estado de Juego Terminado (Modo Individual) -->
                <div v-else-if="gameover && !code" class="text-center p-10">
                    <h2 class="text-3xl font-bold mb-4">¡Partida Terminada!</h2>
                    <p class="text-xl mb-6">Tu puntuación final es:</p>
                    <p class="text-6xl font-bold text-yellow-400 mb-8">{{ puntuacion }}</p>

                    <!-- Notificación de Logros Desbloqueados -->
                    <div v-if="nuevosLogros.length > 0" class="mb-8 space-y-3">
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

                    <div class="flex justify-center gap-4">
                        <Button @click="reiniciarPartida" label="Jugar de Nuevo" icon="pi pi-replay" class="p-button-lg p-button-warning" />
                        <Button @click="router.push({ name: 'home' })" label="Volver al Menú" icon="pi pi-home" class="p-button-lg p-button-secondary" />
                    </div>
                </div>

                <!-- Estado de Juego Terminado (Modo 1vs1 - esperando redirección) -->
                <div v-else-if="gameover && code" class="text-center p-10">
                    <h2 class="text-3xl font-bold mb-4">¡Partida Terminada!</h2>
                    <p class="text-xl mb-6">Tu puntuación final es: <span class="text-6xl font-bold text-yellow-400">{{ puntuacion }}</span></p>
                    <Button @click="() => router.push({ name: 'game.1vs1.results', params: { code: props.code }, query: { score: puntuacion } })" label="Ver Resultados" icon="pi pi-chart-bar" class="p-button-lg p-button-success" />
                </div>

                <!-- Estado de Juego Activo -->
                <div v-else-if="!gameover && questions.length > 0" class="p-4">
                    <div v-if="currentQuestion">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg">Puntuación: <span class="font-bold text-yellow-400">{{ puntuacion }}</span></span>
                            <span class="text-lg">Pregunta {{ preguntaActualIndex + 1 }} de {{ questions.length }}</span>
                        </div>
                        <Divider />
                        <div class="text-center my-6">
                            <p class="text-2xl font-semibold">{{ currentQuestion.enunciado }}</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <Button
                                v-for="option in currentQuestion.opciones"
                                :key="option.id"
                                :label="option.texto"
                                @click="manejarSeleccionRespuesta(option)"
                                :class="['p-button-lg text-white font-bold py-3 px-4 rounded transition-colors duration-300', respondido ? claseOpcion(option) : 'bg-blue-500 hover:bg-blue-700']"
                                :disabled="respondido"
                            />
                        </div>
                        <div v-if="respondido" class="text-center mt-8">
                            <Button
                                v-if="preguntaActualIndex < questions.length - 1"
                                @click="siguientePregunta"
                                label="Siguiente Pregunta"
                                icon="pi pi-arrow-right"
                                class="p-button-info"
                            />
                            <Button
                                v-else
                                @click="siguientePregunta"
                                label="Finalizar Partida"
                                icon="pi pi-check"
                                class="p-button-success"
                            />
                        </div>
                        <div v-else-if="respondido && gameover && !code" class="text-center mt-8">
                             <!-- Este bloque no se mostrará si el gameover ya se maneja arriba, pero es un buen seguro -->
                        </div>
                    </div>
                </div>
                 <div v-else-if="!loading && questions.length === 0" class="text-center p-10">
                    <h2 class="text-2xl font-bold">No se pudieron cargar las preguntas.</h2>
                    <p>Inténtalo de nuevo más tarde.</p>
                </div>
            </template>
        </Card>
    </div>
</template>

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
