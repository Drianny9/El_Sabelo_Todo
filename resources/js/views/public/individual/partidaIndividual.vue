<template>
  <main class="game-container">
    
    <div v-if="!isGameOver && currentQuestion" class="content-card">
      
      <div class="card-header">
        <h2 class="category-title">CIENCIA</h2> 
        <div class="timer" :class="{ 'timer-warning': timeLeft <= 5 }">
          {{ timeLeft }}
        </div>
      </div>

      <div class="question-container">
        <h3 class="question-text">{{ currentQuestion.enunciado }}</h3>
      </div>

      <div class="options-grid" :class="{ 'boolean-grid': currentQuestion.tipo === 'boolean' }">
        <button 
          v-for="(opcion, index) in currentQuestion.opciones" 
          :key="index"
          class="option-button"
          :class="getButtonClass(opcion)"
          :disabled="isAnswering"
          @click="selectAnswer(opcion)"
        >
          {{ opcion.texto }}
        </button>
      </div>

    </div>

    <div v-if="isGameOver" class="content-card results-card">
      <h2 class="title">¡PARTIDA TERMINADA!</h2>
      <p class="score-text">Has acertado <strong>{{ score }}</strong> de <strong>{{ questions.length }}</strong> preguntas.</p>
      <button class="play-again-button" @click="resetGame">VOLVER A JUGAR</button>
    </div>

  </main>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

// 1. Simulación de los datos que recibirías de tu API de Laravel
const questions = ref([
  {
    id: 1,
    tipo: 'multiple',
    enunciado: '¿Cuál es el elemento químico más abundante en el universo?',
    opciones: [
      { texto: 'Hidrógeno', es_correcta: true },
      { texto: 'Oxígeno', es_correcta: false },
      { texto: 'Carbono', es_correcta: false },
      { texto: 'Helio', es_correcta: false },
    ]
  },
  {
    id: 2,
    tipo: 'boolean',
    enunciado: '¿La velocidad de la luz es aproximadamente 300.000 km/s?',
    opciones: [
      { texto: 'Verdadero', es_correcta: true },
      { texto: 'Falso', es_correcta: false },
    ]
  }
]);

// 2. Variables de Estado
const currentQuestionIndex = ref(0);
const score = ref(0);
const isGameOver = ref(false);
const isAnswering = ref(false); // Bloquea los botones mientras se muestra el resultado
const selectedOption = ref(null);

// Temporizador
const timeLeft = ref(15);
let timerInterval = null;

// Pregunta actual computada
const currentQuestion = computed(() => questions.value[currentQuestionIndex.value]);

// 3. Lógica del Temporizador
const startTimer = () => {
  timeLeft.value = 15;
  clearInterval(timerInterval);
  timerInterval = setInterval(() => {
    if (timeLeft.value > 0) {
      timeLeft.value--;
    } else {
      // Se acabó el tiempo = fallo
      handleTimeOut();
    }
  }, 1000);
};

const stopTimer = () => {
  clearInterval(timerInterval);
};

// 4. Lógica de Respuesta
const selectAnswer = (opcion) => {
  if (isAnswering.value) return;
  
  stopTimer();
  isAnswering.value = true;
  selectedOption.value = opcion;

  if (opcion.es_correcta) {
    score.value++;
  }

  // Esperar 1.5 segundos para mostrar si es correcta/incorrecta antes de pasar a la siguiente
  setTimeout(() => {
    nextQuestion();
  }, 1500);
};

const handleTimeOut = () => {
  isAnswering.value = true;
  selectedOption.value = null; // No seleccionó nada
  
  setTimeout(() => {
    nextQuestion();
  }, 1500);
};

const nextQuestion = () => {
  isAnswering.value = false;
  selectedOption.value = null;

  if (currentQuestionIndex.value < questions.value.length - 1) {
    currentQuestionIndex.value++;
    startTimer();
  } else {
    isGameOver.value = true;
  }
};

// 5. Clases dinámicas para los botones (mostrar verde si acierta, rojo si falla)
const getButtonClass = (opcion) => {
  if (!isAnswering.value) return '';

  if (opcion.es_correcta) {
    return 'correct-answer'; // Siempre muestra la correcta
  }
  if (selectedOption.value === opcion && !opcion.es_correcta) {
    return 'wrong-answer'; // Muestra en rojo la que el usuario falló
  }
  return '';
};

const resetGame = () => {
  currentQuestionIndex.value = 0;
  score.value = 0;
  isGameOver.value = false;
  startTimer();
};

// Iniciar al cargar
onMounted(() => {
  // Aquí harías tu llamada a la API con axios/fetch para llenar 'questions.value'
  startTimer();
});

// Limpiar intervalo al destruir
onUnmounted(() => {
  stopTimer();
});
</script>

<style scoped>
/* Contenedor principal */
.game-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: calc(100vh - 100px);
  background-color: #8331d6;
  padding: 1rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Tarjeta central */
.content-card {
  background-color: #ffffff;
  border-radius: 16px;
  padding: 2.5rem;
  width: 100%;
  max-width: 800px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
  display: flex;
  flex-direction: column;
}

/* Cabecera: Categoría y Temporizador */
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.category-title {
  font-size: 1.5rem;
  font-weight: 800;
  color: #333;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin: 0;
}

.timer {
  font-size: 1.5rem;
  font-weight: bold;
  color: #555;
  background: #f0f0f0;
  padding: 0.5rem 1rem;
  border-radius: 50%;
  min-width: 50px;
  text-align: center;
  transition: color 0.3s;
}

.timer-warning {
  color: #e74c3c;
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.1); }
  100% { transform: scale(1); }
}

/* Pregunta */
.question-container {
  min-height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 2rem;
}

.question-text {
  font-size: 1.8rem;
  text-align: center;
  color: #2c3e50;
  line-height: 1.4;
  margin: 0;
}

/* Cuadrícula de opciones */
.options-grid {
  display: grid;
  grid-template-columns: 1fr 1fr; /* Crea 2 columnas iguales (2x2) */
  gap: 1.5rem;
}

/* Si es Verdadero/Falso, podemos centrarlo o dejarlo igual */
.boolean-grid {
  grid-template-columns: 1fr 1fr;
}

/* Botones de opción */
.option-button {
  background-color: #ffffff;
  border: 2px solid #ddd;
  padding: 1.5rem;
  border-radius: 12px;
  font-size: 1.2rem;
  font-weight: 600;
  color: #333;
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.option-button:hover:not(:disabled) {
  border-color: #8331d6;
  background-color: #f8f4fe;
  transform: translateY(-2px);
}

.option-button:disabled {
  cursor: default;
}

/* Clases dinámicas de feedback */
.correct-answer {
  background-color: #2ecc71 !important;
  border-color: #27ae60 !important;
  color: white !important;
}

.wrong-answer {
  background-color: #e74c3c !important;
  border-color: #c0392b !important;
  color: white !important;
}

/* Pantalla de resultados */
.results-card {
  text-align: center;
  align-items: center;
}

.title {
  font-size: 2.5rem;
  color: #333;
  margin-bottom: 1rem;
}

.score-text {
  font-size: 1.5rem;
  color: #666;
  margin-bottom: 2rem;
}

.play-again-button {
  background: linear-gradient(180deg, #ffdf60 0%, #fcad21 100%);
  color: #5a2e00;
  font-size: 1.2rem;
  font-weight: 800;
  padding: 1rem 3rem;
  border: none;
  border-radius: 50px;
  cursor: pointer;
  text-transform: uppercase;
  box-shadow: 0 4px 0 #cc8110;
  transition: transform 0.1s;
}

.play-again-button:active {
  transform: translateY(4px);
  box-shadow: 0 0 0 #cc8110;
}

/* Responsive */
@media (max-width: 600px) {
  .options-grid {
    grid-template-columns: 1fr; /* 1 sola columna en móviles */
  }
  .question-text {
    font-size: 1.4rem;
  }
  .content-card {
    padding: 1.5rem;
  }
}
</style>