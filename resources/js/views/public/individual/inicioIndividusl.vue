<template>
  <main class="game-container">
    <div class="content-card">
      <h1 class="title">DEMUESTRA LO QUE SABES</h1>
      <p class="description">
        En este modo el objetivo es responder las maximas<br>
        preguntas en el menor tiempo posible. Si fallas<br>
        luego se te resta en la puntuacion final
      </p>  
      <button class="play-button" @click="startGame">
        ¡JUGAR!
      </button>
    </div>
  </main>
</template>

<script setup>
import { authStore } from "@/store/auth";

// Función de ejemplo para el botón
const startGame = () => {
  console.log("Iniciando el juego...");
  // Aquí puedes añadir la lógica para empezar la partida
};
</script>

<style scoped>
/* Contenedor principal con el fondo morado del estilo visual */
.game-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: calc(100vh - 200px); /* Ajusta según el tamaño de tu nav/footer real */
  background-color: #8331d6; /* Morado basado en el diseño */
  padding: 2rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Tarjeta central blanca basada en el wireframe */
.content-card {
  background-color: #ffffff;
  border-radius: 24px;
  padding: 4rem 2rem;
  text-align: center;
  max-width: 800px;
  width: 100%;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Estilos de la tipografía */
.title {
  color: #000000;
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 1.5rem;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.description {
  color: #333333;
  font-size: 1.25rem;
  line-height: 1.6;
  margin-bottom: 3rem;
  font-weight: 400;
}

/* Botón estilo "juego" con colores amarillos/naranjas */
.play-button {
  background: linear-gradient(180deg, #ffdf60 0%, #fcad21 100%);
  color: #5a2e00; /* Texto oscuro para contrastar con el amarillo */
  font-size: 1.5rem;
  font-weight: 800;
  padding: 1rem 4rem;
  border: 2px solid #fff;
  border-radius: 50px;
  cursor: pointer;
  text-transform: uppercase;
  box-shadow: 0 6px 0 #cc8110, 0 10px 10px rgba(0, 0, 0, 0.15); /* Efecto 3D de botón de juego */
  transition: all 0.1s ease-in-out;
}

/* Animación al hacer clic o pasar el ratón */
.play-button:hover {
  filter: brightness(1.05);
  transform: translateY(-2px);
}

.play-button:active {
  transform: translateY(4px);
  box-shadow: 0 2px 0 #cc8110, 0 4px 4px rgba(0, 0, 0, 0.15); /* El botón se "hunde" */
}

/* Responsive para pantallas pequeñas */
@media (max-width: 768px) {
  .title {
    font-size: 2rem;
  }
  .description {
    font-size: 1.1rem;
    br {
      display: none; /* Quita los saltos de línea forzados en móvil */
    }
  }
  .content-card {
    padding: 3rem 1.5rem;
  }
}
</style>