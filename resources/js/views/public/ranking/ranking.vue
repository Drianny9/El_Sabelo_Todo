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
import { onMounted } from 'vue';
import useRanking from '@/composables/ranking';

//Usamos el composable que encapsula toda la lógica del ranking y comunicación con la API
const { ranking, loading, fetchRanking } = useRanking();

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