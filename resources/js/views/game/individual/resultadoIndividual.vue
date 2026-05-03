<template>
    <div class="relative min-h-screen bg-gradient-to-b from-purple-900 via-indigo-900 to-purple-900 text-white flex items-center justify-center p-4 overflow-hidden pt-24 pb-20">
        
        <!-- Elementos 3D de fondo -->
        <img src="/images/Imagenes-Fondo/Estrella.webp" alt="Estrella" class="absolute top-10 left-10 w-32 md:w-48 opacity-40 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />
        <img src="/images/Imagenes-Fondo/trofeo.webp" alt="Trofeo" class="absolute bottom-10 right-10 w-40 md:w-56 opacity-30 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />

        <!-- Contenedor Principal Premium "Tarjeta" -->
        <div class="relative w-full max-w-2xl bg-white text-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.5)] rounded-[3rem] border-8 border-purple-300/30 overflow-hidden z-10 flex flex-col">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-100 to-white px-8 py-6 relative border-b-2 border-purple-200 text-center">
                <h1 class="text-3xl md:text-4xl font-black text-purple-700 tracking-wider uppercase drop-shadow-sm">¡HAS TERMINADO!</h1>
                <p class="text-purple-500 font-bold mt-2 uppercase tracking-widest text-sm">Resumen de tu partida</p>
            </div>

            <!-- Content -->
            <div class="p-8 md:p-12 flex flex-col items-center gap-6">
                
                <p class="text-gray-500 font-bold text-xl uppercase tracking-wider text-center">Puntuación Final</p>
                
                <!-- Score Display -->
                <div class="inline-block bg-gradient-to-br from-yellow-300 to-yellow-500 rounded-[2rem] px-16 py-8 shadow-[0_10px_20px_rgba(234,179,8,0.3)] mb-4 border-4 border-yellow-200">
                    <p class="text-7xl md:text-8xl font-black text-yellow-900 drop-shadow-md leading-none">{{ score }}</p>
                </div>

                <!-- Stats -->
                <div class="w-full bg-gray-50 rounded-[2rem] border-2 border-gray-100 p-6 flex justify-around shadow-inner mb-6">
                    <div class="text-center">
                        <p class="text-gray-400 font-black uppercase text-xs tracking-widest mb-1">Aciertos</p>
                        <p class="text-3xl font-black text-green-500">{{ correctAnswers }}</p>
                    </div>
                    <div class="w-1 bg-gray-200 rounded-full"></div>
                    <div class="text-center">
                        <p class="text-gray-400 font-black uppercase text-xs tracking-widest mb-1">Fallos</p>
                        <p class="text-3xl font-black text-red-500">{{ incorrectAnswers }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 w-full mt-4">
                    <button 
                        @click="playAgain" 
                        class="flex-1 bg-gradient-to-b from-yellow-300 to-yellow-500 hover:from-yellow-200 hover:to-yellow-400 text-yellow-900 font-black py-4 rounded-2xl border-2 border-yellow-200 shadow-[0_6px_0_#b45309] hover:shadow-[0_2px_0_#b45309] hover:translate-y-1 transition-all uppercase tracking-widest flex items-center justify-center gap-3"
                    >
                        <i class="pi pi-replay font-black text-xl"></i> Volver a jugar
                    </button>
                    <button 
                        @click="exitGame" 
                        class="flex-1 bg-gradient-to-b from-gray-200 to-gray-300 hover:from-gray-100 hover:to-gray-200 text-gray-700 font-black py-4 rounded-2xl border-2 border-gray-100 shadow-[0_6px_0_#9ca3af] hover:shadow-[0_2px_0_#9ca3af] hover:translate-y-1 transition-all uppercase tracking-widest flex items-center justify-center gap-3"
                    >
                        <i class="pi pi-home font-black text-xl"></i> Salir
                    </button>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// Variables de estado (sustituye estos valores por los reales de tu lógica de juego)
const score = ref(7);
const correctAnswers = ref(8); // Usando los datos del wireframe de ejemplo
const incorrectAnswers = ref(2);

// Funciones para los botones
const playAgain = () => {
  // router.push({ name: 'game.individual.play' }); // Descomentar cuando la ruta esté lista
};  

const exitGame = () => {
  router.push({ name: 'home' });
};
</script>