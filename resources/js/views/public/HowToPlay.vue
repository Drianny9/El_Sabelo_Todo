<template>
    <div class="min-h-screen text-white overflow-x-hidden relative pb-20"
         style="background: linear-gradient(180deg, #9333EA 0%, #7C3AED 35%, #6D28D9 100%);">
        
        <!-- Decorative Assets -->
        <img src="/images/Imagenes-Fondo/Estrella.webp" alt="" 
            class="absolute right-[-2rem] top-10 w-32 md:w-48 opacity-40 rotate-12 pointer-events-none select-none" />
        <img src="/images/Imagenes-Fondo/Diamante.webp" alt="" 
            class="absolute left-[-3rem] top-1/2 w-32 md:w-48 opacity-30 -rotate-45 pointer-events-none select-none" />
        
        <div class="max-w-6xl mx-auto px-6 pt-16 relative z-10">
            <!-- Título -->
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-7xl font-black text-white uppercase tracking-tighter drop-shadow-[0_5px_0_rgba(0,0,0,0.2)]">
                    CÓMO JUGAR
                </h1>
                <div class="w-24 h-2 bg-yellow-400 mx-auto mt-4 rounded-full shadow-lg"></div>
            </div>

            <!-- Sección Central: Tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                <!-- Tarjeta 1 -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.3)] border-b-8 border-gray-200 flex flex-col items-center text-center group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-24 h-24 mb-6 flex items-center justify-center bg-purple-100 rounded-3xl group-hover:scale-110 transition-transform">
                        <!-- Placeholder para mando_dorado.webp -->
                        <i class="pi pi-discord text-5xl text-yellow-500"></i> 
                    </div>
                    <h2 class="text-2xl font-black text-purple-900 uppercase mb-4">Elige tu Duelo</h2>
                    <p class="text-purple-700 font-bold leading-relaxed">
                        Selecciona entre Modo Individual para entrenar o 1vs1 para competir con amigos.
                    </p>
                </div>

                <!-- Tarjeta 2: Modo Individual -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.3)] border-b-8 border-gray-200 flex flex-col items-center text-center group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-24 h-24 mb-6 flex items-center justify-center bg-purple-100 rounded-3xl group-hover:scale-110 transition-transform">
                        <i class="pi pi-user text-5xl text-blue-500"></i> 
                    </div>
                    <h2 class="text-2xl font-black text-purple-900 uppercase mb-4">Modo Solitario</h2>
                    <p class="text-purple-700 font-bold leading-relaxed">
                        Juega partidas rápidas para ganar puntos de experiencia, monedas y desbloquear logros exclusivos de sabelotodo.
                    </p>
                </div>

                <!-- Tarjeta 3: Modo 1vs1 -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.3)] border-b-8 border-gray-200 flex flex-col items-center text-center group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-24 h-24 mb-6 flex items-center justify-center bg-purple-100 rounded-3xl group-hover:scale-110 transition-transform">
                        <i class="pi pi-users text-5xl text-orange-500"></i> 
                    </div>
                    <h2 class="text-2xl font-black text-purple-900 uppercase mb-4">Duelo 1vs1</h2>
                    <p class="text-purple-700 font-bold leading-relaxed">
                        Crea una sala, comparte el código con un amigo y competid en tiempo real por ver quién responde mejor y más rápido.
                    </p>
                </div>
            </div>

            <!-- Botón Inferior -->
            <div class="flex justify-center mt-10">
                <router-link to="/">
                    <button class="bg-gradient-to-b from-yellow-300 to-yellow-500 text-yellow-950 font-black text-2xl py-5 px-16 rounded-full border-b-8 border-yellow-700 shadow-2xl hover:brightness-110 active:border-b-0 active:translate-y-2 transition-all uppercase tracking-widest">
                        ¡ENTENDIDO!
                    </button>
                </router-link>
            </div>
        </div>

        <!-- Decorative coins at bottom -->
        <img src="/images/Home/Monedas_izquierda.webp" alt="" 
            class="absolute bottom-0 left-0 w-48 md:w-64 opacity-80 pointer-events-none select-none" />
        <img src="/images/Home/Monedas_derecha.webp" alt="" 
            class="absolute bottom-0 right-0 w-48 md:w-64 opacity-80 pointer-events-none select-none" />
    </div>
</template>

<script setup>
// No logic needed for this static informational page
</script>

<style scoped>
/* Custom shadows and 3D effects if needed */
</style>
