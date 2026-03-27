<script setup>
import { onMounted, ref } from 'vue';
import useGame from '@/composables/games.js';
import Card from 'primevue/card';
import Button from 'primevue/button';
import ProgressSpinner from 'primevue/progressspinner';
import Divider from 'primevue/divider';

// Importamos toda la lógica del juego desde nuestro composable
const {
    loading,
    gameover,
    puntuacion,
    currentQuestion,
    preguntaActualIndex,
    questions,
    fetchQuestions,
    procesarRespuesta,
    reiniciarPartida
} = useGame();

// Referencia para guardar la opción que el usuario ha seleccionado
const opcionSeleccionada = ref(null);
// Referencia para saber si ya se ha respondido la pregunta actual
const respondido = ref(false);

// Cuando el componente se carga por primera vez, pedimos las preguntas
onMounted(() => {
    fetchQuestions();
});

// Función para manejar la selección de una respuesta
const manejarSeleccionRespuesta = (option) => {
    if (respondido.value) return; // Si ya se respondió, no hacer nada

    opcionSeleccionada.value = option;
    respondido.value = true;
    procesarRespuesta(option); // El composable se encarga de la lógica y de avanzar
};

// Función para pasar a la siguiente pregunta
const siguientePregunta = () => {
    // `procesarRespuesta` ya avanza el índice, así que aquí solo reseteamos el estado visual
    opcionSeleccionada.value = null;
    respondido.value = false;
};

// Clase CSS para los botones de opción, cambia de color si es correcta o incorrecta
const claseOpcion = (option) => {
    if (!respondido.value) {
        return 'bg-blue-500 hover:bg-blue-700';
    }
    // Si esta es la opción que el usuario seleccionó
    if (option.id === opcionSeleccionada.value.id) {
        return option.es_correcta ? 'bg-green-500' : 'bg-red-500';
    }
    // Si no es la que seleccionó, pero es la correcta, la mostramos en verde
    if (option.es_correcta) {
        return 'bg-green-500';
    }
    // Las demás incorrectas se quedan en un color neutro
    return 'bg-gray-400';
};

</script>

<template>
    <div class="flex justify-center items-center min-h-screen bg-gray-900 text-white p-4">
        <Card class="w-full max-w-2xl bg-gray-800 shadow-lg rounded-lg">
            <template #title>
                <div class="text-center text-2xl font-bold text-yellow-400">
                    El Sabelotodo - Partida Individual
                </div>
            </template>
            <template #content>
                <!-- Estado de Carga -->
                <div v-if="loading" class="flex flex-col items-center justify-center p-10">
                    <ProgressSpinner />
                    <p class="mt-4 text-lg">Cargando partida...</p>
                </div>

                <!-- Estado de Juego Terminado -->
                <div v-else-if="gameover" class="text-center p-10">
                    <h2 class="text-3xl font-bold mb-4">¡Partida Terminada!</h2>
                    <p class="text-xl mb-6">Tu puntuación final es:</p>
                    <p class="text-6xl font-bold text-yellow-400 mb-8">{{ puntuacion }}</p>
                    <Button @click="reiniciarPartida" label="Jugar de Nuevo" icon="pi pi-replay" class="p-button-lg p-button-warning" />
                </div>

                <!-- Estado de Juego Activo -->
                <div v-else class="p-4">
                    <!-- Solo mostramos la pregunta si de verdad existe -->
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
                        <div v-if="respondido && !gameover" class="text-center mt-8">
                            <Button @click="siguientePregunta" label="Siguiente Pregunta" icon="pi pi-arrow-right" class="p-button-info" />
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>

<style scoped>
.p-card {
    border: 2px solid #4a5568;
}
</style>
