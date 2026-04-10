<template>
  <main class="results-container">
    <div class="content-card">
      <h1 class="title">Has terminado</h1>
      
      <p class="subtitle">Has obtenido una puntuación de:</p>
      
      <div class="score-display">
        {{ score }}
      </div>
      
      <div class="stats">
        <p>Has acertado <strong>{{ correctAnswers }}</strong> preguntas</p>
        <p>Has fallado <strong>{{ incorrectAnswers }}</strong> preguntas</p>
      </div>
      
      <div class="actions">
        <button class="action-btn" @click="playAgain">
          Volver a jugar
        </button>
        <button class="action-btn" @click="exitGame">
          Salir
        </button>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref } from 'vue';

// Variables de estado (sustituye estos valores por los reales de tu lógica de juego)
const score = ref(7);
const correctAnswers = ref(8); // Usando los datos del wireframe de ejemplo
const incorrectAnswers = ref(2);

// Funciones para los botones
const playAgain = () => {
  console.log("Reiniciando el juego...");
  // Aquí puedes emitir un evento al componente padre o cambiar la ruta
  // router.push({ name: 'Juego' });
};  

const exitGame = () => {
  console.log("Saliendo al menú principal...");
  // Aquí puedes redirigir al home
  // router.push({ name: 'Home' });
};
</script>

<style scoped>
/* Contenedor principal con fondo morado */
.results-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: calc(100vh - 200px); /* Ajusta si tienes nav/footer */
  background-color: #8331d6;
  padding: 2rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Tarjeta blanca central */
.content-card {
  background-color: #ffffff;
  border-radius: 24px;
  padding: 4rem 3rem;
  text-align: center;
  max-width: 700px;
  width: 100%;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Tipografía de títulos */
.title {
  color: #000000;
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 2rem;
}

.subtitle {
  color: #333333;
  font-size: 1.4rem;
  margin-bottom: 0.5rem;
}

/* El número gigante del centro */
.score-display {
  font-size: 6rem;
  font-weight: 800;
  color: #000000;
  margin: 1rem 0 2rem 0;
  line-height: 1;
}

/* Textos de aciertos y fallos */
.stats {
  color: #444444;
  font-size: 1.2rem;
  margin-bottom: 3.5rem;
  line-height: 1.8;
}

.stats p {
  margin: 0;
}

/* Contenedor de botones (para ponerlos uno al lado del otro) */
.actions {
  display: flex;
  justify-content: center;
  gap: 2rem;
}

/* Estilo unificado para los botones (naranja/amarillo) */
.action-btn {
  background: linear-gradient(180deg, #ffdf60 0%, #fcad21 100%);
  color: #5a2e00;
  font-size: 1.3rem;
  font-weight: 700;
  padding: 1rem 3rem;
  border: 2px solid #fff;
  border-radius: 50px;
  cursor: pointer;
  min-width: 200px;
  box-shadow: 0 6px 0 #cc8110, 0 10px 10px rgba(0, 0, 0, 0.15);
  transition: all 0.1s ease-in-out;
}

/* Animaciones de botón interactivo */
.action-btn:hover {
  filter: brightness(1.05);
  transform: translateY(-2px);
}

.action-btn:active {
  transform: translateY(4px);
  box-shadow: 0 2px 0 #cc8110, 0 4px 4px rgba(0, 0, 0, 0.15);
}

/* Responsive para que en móviles los botones se pongan uno encima del otro */
@media (max-width: 600px) {
  .actions {
    flex-direction: column;
    gap: 1.5rem;
  }
  
  .content-card {
    padding: 3rem 1.5rem;
  }
  
  .title {
    font-size: 2.2rem;
  }
  
  .score-display {
    font-size: 5rem;
  }
}
</style>