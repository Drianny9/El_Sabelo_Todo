<template>
  <div class="ranking-container">
    <h2 class="title">🏆 Tabla de Clasificación</h2>

    <div v-if="loading" class="loader">Cargando ranking...</div>

    <div v-else class="ranking-list">
      <div 
        v-for="(user, index) in ranking" 
        :key="index" 
        class="ranking-item"
        :class="{ 'top-three': index < 3 }"
      >
        <div class="position">
          <span v-if="index === 0">🥇</span>
          <span v-else-if="index === 1">🥈</span>
          <span v-else-if="index === 2">🥉</span>
          <span v-else>{{ index + 1 }}</span>
        </div>

        <div class="user-info">
          <span class="alias">{{ user.alias || 'Anónimo' }}</span>
        </div>

        <div class="score">
          {{ user.puntuacion }} <small>pts</small>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const ranking = ref([]);
const loading = ref(true);

const fetchRanking = async () => {
  try {
    const response = await axios.get('/api/ranking');
    console.log("Respuesta completa de Axios:", response);
    
    // Si usas UserResource::collection, la ruta es response.data.data
    // Si devuelves User::all(), la ruta suele ser solo response.data
    if (response.data.data) {
        ranking.value = response.data.data;
    } else {
        ranking.value = response.data;
    }
    
    console.log("Lo que se guardó en ranking:", ranking.value);
  } catch (error) {
    console.error("Error cargando el ranking:", error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchRanking();
});
</script>

<style scoped>
.ranking-container {
  max-width: 500px;
  margin: 0 auto;
  font-family: sans-serif;
}

.ranking-item {
  display: flex;
  align-items: center;
  padding: 15px;
  margin-bottom: 8px;
  background: #f8f9fa;
  border-radius: 10px;
  transition: transform 0.2s;
}

.top-three {
  background: #fff3cd; /* Color dorado suave para el top */
  border: 1px solid #ffeeba;
  font-weight: bold;
}

.position { width: 40px; font-size: 1.2rem; }
.user-info { flex-grow: 1; text-transform: capitalize; }
.score { font-size: 1.1rem; color: #2c3e50; }
</style>