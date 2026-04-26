<template>
    <div class="relative min-h-screen bg-gradient-to-b from-purple-900 via-indigo-900 to-purple-900 text-white flex items-center justify-center p-4 overflow-hidden pt-24 pb-20">
        
        <!-- Elementos 3D de fondo -->
        <img src="/images/Imagenes-Fondo/Interrogante.webp" alt="Interrogante" class="absolute top-10 left-10 w-32 md:w-48 opacity-40 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />
        <img src="/images/Imagenes-Fondo/Estrella.webp" alt="Estrella" class="absolute bottom-10 right-10 w-40 md:w-56 opacity-30 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />
        
        <!-- Contenedor Principal Premium "Tarjeta" -->
        <div class="relative w-full max-w-2xl bg-white text-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.5)] rounded-[3rem] border-8 border-purple-300/30 overflow-hidden z-10 flex flex-col">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-100 to-white px-8 py-6 relative border-b-2 border-purple-200 text-center">
                <h1 class="text-3xl md:text-4xl font-black text-purple-700 tracking-wider uppercase drop-shadow-sm">DEMUESTRA LO QUE SABES</h1>
                <p class="text-purple-500 font-bold mt-2 uppercase tracking-widest text-sm">Modo Individual</p>
            </div>

            <!-- Content -->
            <div class="p-8 md:p-12 flex flex-col items-center gap-8">

                <!-- Icono central decorativo -->
                <div class="w-24 h-24 rounded-full bg-purple-100 border-4 border-purple-200 flex items-center justify-center shadow-inner">
                    <i class="pi pi-bolt text-5xl text-purple-500"></i>
                </div>

                <!-- Descripción (texto original preservado) -->
                <div class="bg-gray-50 rounded-[2rem] border-2 border-gray-100 p-6 w-full text-center shadow-inner">
                    <p class="text-gray-600 text-lg font-bold leading-relaxed">
                        En este modo el objetivo es responder las maximas<br class="hidden sm:inline">
                        preguntas en el menor tiempo posible.
                    </p>
                </div>

                <!-- Separador -->
                <div class="flex items-center gap-4 w-full">
                    <div class="flex-1 h-1 bg-purple-100 rounded-full"></div>
                    <span class="text-purple-400 font-black uppercase tracking-widest text-sm">¿Listo para jugar?</span>
                    <div class="flex-1 h-1 bg-purple-100 rounded-full"></div>
                </div>

                <!-- Botón ¡Jugar! -->
                <button
                    @click="startGame"
                    class="w-full max-w-xs bg-gradient-to-b from-yellow-300 to-yellow-500 hover:from-yellow-200 hover:to-yellow-400 text-yellow-900 font-black text-2xl py-5 rounded-full border-4 border-yellow-200/50 shadow-[0_8px_0_#b45309] hover:shadow-[0_5px_0_#b45309] hover:translate-y-1 active:shadow-[0_0px_0_#b45309] active:translate-y-3 transition-all duration-150 uppercase tracking-wider flex items-center justify-center gap-3"
                >
                    <i class="pi pi-play-circle font-black"></i> ¡JUGAR!
                </button>

            </div>
        </div>
    </div>
</template>

<script setup>
import { authStore } from "@/store/auth";
import { useRouter } from "vue-router";

const router = useRouter();

// Función de ejemplo para el botón
const startGame = () => {
  router.push('/game/individual/play');
};
</script>