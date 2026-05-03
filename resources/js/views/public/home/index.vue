<template>
    <div class="min-h-screen text-white overflow-x-hidden"
        style="background: linear-gradient(180deg, #9333EA 0%, #7C3AED 35%, #6D28D9 100%);">

        <!-- ===== HERO ===== -->
        <section class="relative text-center pt-20 pb-10 px-4">
            <!-- Rayo decorativo izquierda -->
            <img src="/images/Home/Rayo.webp" alt="Rayo"
                class="absolute left-2 top-10 w-20 md:left-6 md:top-14 md:w-36 select-none pointer-events-none drop-shadow-xl opacity-80 md:opacity-100" />

            <h1 class="text-4xl md:text-6xl font-black tracking-widest uppercase text-white drop-shadow-2xl">
                DEMUESTRA LO QUE SABES
            </h1>
            <p class="text-purple-200 text-lg md:text-xl mt-3 font-medium">
                Pon a prueba tus conocimientos
            </p>
        </section>

        <!-- ===== CARDS DE MODOS ===== -->
        <section class="px-4 md:px-12 pb-10 max-w-5xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Card 1VS1 -->
                <div
                    class="bg-white rounded-3xl p-8 text-center shadow-2xl border-4 border-yellow-400 flex flex-col h-full">
                    <h2 class="text-4xl font-black text-purple-700 italic tracking-wide mb-6">1 VS 1</h2>
                    <!-- Avatares con blobs degradados -->
                    <div class="flex justify-center items-center gap-0 my-6 relative">
                        <!-- Jugador Izquierda -->
                        <div class="relative w-28 h-28 md:w-32 md:h-32 flex items-center justify-center">
                            <!-- Blob degradado de fondo -->
                            <div class="absolute inset-0 rounded-full opacity-80"
                                 style="background: radial-gradient(ellipse at 60% 50%, #c084fc 0%, #a855f7 40%, #7c3aed 70%, transparent 100%);
                                        filter: blur(6px);
                                        transform: scale(1.05) rotate(-5deg);
                                        border-radius: 50% 40% 55% 45% / 45% 55% 40% 50%;">
                            </div>
                            <img src="/images/Home/Logo-izquierda.webp" alt="Jugador 1"
                                class="relative z-10 w-24 h-24 md:w-28 md:h-28 object-contain drop-shadow-lg" />
                        </div>

                        <!-- VS Badge -->
                        <div class="w-14 h-14 rounded-full bg-purple-600 flex items-center justify-center shadow-xl flex-shrink-0 z-20 border-4 border-purple-400 -mx-3">
                            <span class="text-white font-black text-lg">VS</span>
                        </div>

                        <!-- Jugador Derecha -->
                        <div class="relative w-28 h-28 md:w-32 md:h-32 flex items-center justify-center">
                            <!-- Blob degradado de fondo -->
                            <div class="absolute inset-0 rounded-full opacity-80"
                                 style="background: radial-gradient(ellipse at 40% 50%, #c084fc 0%, #a855f7 40%, #7c3aed 70%, transparent 100%);
                                        filter: blur(6px);
                                        transform: scale(1.05) rotate(5deg);
                                        border-radius: 45% 55% 40% 50% / 50% 40% 55% 45%;">
                            </div>
                            <img src="/images/Home/Logo-derecha.webp" alt="Jugador 2"
                                class="relative z-10 w-24 h-24 md:w-28 md:h-28 object-contain drop-shadow-lg" />
                        </div>
                    </div>
                    <button @click="startGame('duel')"
                        class="w-full bg-gradient-to-b from-yellow-300 to-yellow-400 hover:from-yellow-200 hover:to-yellow-300 text-yellow-900 font-black text-2xl py-4 rounded-full border-4 border-yellow-200/50 shadow-[0_8px_0_#b45309] hover:shadow-[0_5px_0_#b45309] hover:translate-y-1 active:shadow-[0_0px_0_#b45309] active:translate-y-3 transition-all duration-150 uppercase tracking-wider mt-auto sans-serif">
                        DUELO
                    </button>
                </div>

                <!-- Card Modo Individual -->
                <div
                    class="bg-white rounded-3xl p-8 text-center shadow-2xl border-4 border-purple-300 relative flex flex-col h-full">
                    <!-- Corazón decorativo esquina -->
                    <img src="/images/Home/Corazon_lila.webp" alt="Corazón"
                        class="absolute -top-4 -right-4 w-24 h-24 md:-top-6 md:-right-22 md:w-40 md:h-40 select-none pointer-events-none drop-shadow-lg" />
                    <h2 class="text-4xl font-black text-purple-700 italic tracking-wide mb-6">MODO INDIVIDUAL</h2>
                    <div class="flex justify-center my-6">
                        <div class="w-32 h-32 rounded-2xl bg-purple-100 border-4 border-purple-200 overflow-hidden">
                            <img src="/images/Home/Avatar_solitario.webp" alt="Avatar Individual"
                                class="w-full h-full object-cover" />
                        </div>
                    </div>
                    <button @click="startGame('solo')"
                        class="w-full bg-gradient-to-b from-yellow-300 to-yellow-400 hover:from-yellow-200 hover:to-yellow-300 text-yellow-900 font-black text-2xl py-4 rounded-full border-4 border-yellow-200/50 shadow-[0_8px_0_#b45309] hover:shadow-[0_5px_0_#b45309] hover:translate-y-1 active:shadow-[0_0px_0_#b45309] active:translate-y-3 transition-all duration-150 uppercase tracking-wider mt-auto sans-serif">
                        ENTRENAR
                    </button>
                </div>
            </div>
        </section>

        <!-- ===== SOCIAL ===== -->
        <section class="px-4 md:px-12 pb-12 max-w-5xl mx-auto">
            <!-- Título con líneas decorativas -->
            <div class="flex items-center gap-4 mb-10">
                <div class="flex-1 h-px bg-white/30"></div>
                <h2 class="text-white font-black text-2xl md:text-3xl tracking-widest uppercase whitespace-nowrap">
                    COMUNIDAD
                </h2>
                <div class="flex-1 h-px bg-white/30"></div>
            </div>

            <div class="bg-black/20 backdrop-blur-sm rounded-3xl p-6 border border-white/10 shadow-xl">
                <!-- Header social -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-5">
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <i class="pi pi-users text-white text-xl"></i>
                        <span class="text-white font-black text-xl tracking-widest uppercase">Social</span>
                    </div>
                    <!-- Buscador -->
                    <div class="flex gap-2 flex-1 w-full">
                        <div class="flex-1 relative">
                            <i class="pi pi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input v-model="busquedaJugador" type="text" placeholder="Buscar jugadores..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white text-gray-700 text-sm outline-none border-0 placeholder:text-gray-400 shadow-inner" />
                        </div>
                        <button
                            class="bg-yellow-400 hover:bg-yellow-300 text-gray-800 font-bold px-5 py-2.5 rounded-xl text-sm transition-colors shadow-[0_4px_0_#b45309] hover:shadow-[0_2px_0_#b45309] hover:translate-y-0.5">
                            Buscar
                        </button>
                    </div>
                </div>

                <!-- Carrusel de sugerencias -->
                <p class="text-white/70 text-sm mb-4 font-medium">Personas que quizá conozcas</p>

                <!-- Estado de carga -->
                <div v-if="socialLoading" class="flex justify-center py-6">
                    <i class="pi pi-spin pi-spinner text-white text-2xl"></i>
                </div>

                <!-- Sin usuarios -->
                <p v-else-if="socialUsers.length === 0" class="text-white/50 text-sm text-center py-4">
                    No hay jugadores disponibles ahora mismo.
                </p>

                <!-- Carrusel -->
                <div v-else class="relative">
                    <!-- Flecha izquierda -->
                    <button @click="scrollCarouselLeft"
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-10 -translate-x-3 w-8 h-8 rounded-full bg-white/20 hover:bg-white/40 text-white font-bold hidden md:flex items-center justify-center transition-all shadow-lg">
                        ‹
                    </button>

                    <!-- Contenedor deslizable -->
                    <div ref="carouselRef"
                        class="grid grid-cols-2 gap-4 max-h-[400px] overflow-y-auto md:max-h-none md:flex md:flex-row md:overflow-x-auto md:overflow-y-visible scroll-smooth snap-x snap-mandatory px-2 pb-2 pt-6"
                        style="scrollbar-width: none; -ms-overflow-style: none;">
                        <div v-for="u in socialUsers" :key="u.id"
                            class="md:snap-start flex-shrink-0 w-full md:w-36 bg-white/10 md:bg-transparent rounded-2xl border-0 md:border md:border-white/15 overflow-hidden transition-transform hover:-translate-y-1 cursor-pointer flex flex-col shadow-lg">
                            
                            <!-- Fondo superior púrpura (Avatar area) -->
                            <div class="h-16 md:h-20 flex items-end justify-center relative bg-white/10">
                                <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden absolute -bottom-8 md:-bottom-10 border-[3px] border-white shadow-sm bg-purple-200">
                                    <img
                                        :src="u.avatar || '/images/Home/Avatar_solitario.webp'"
                                        :alt="u.alias"
                                        class="w-full h-full object-cover" />
                                </div>
                            </div>

                            <!-- Fondo inferior blanco (Info area) -->
                            <div class="bg-white pt-10 md:pt-12 pb-3 px-2 flex-1 flex flex-col items-center">
                                <p class="text-purple-900 font-bold text-sm truncate w-full text-center">{{ u.alias }}</p>
                                <p class="text-gray-500 text-[10px] mb-3">{{ u.puntuacion.toLocaleString() }} pts</p>
                                
                                <button
                                    class="w-[85%] mt-auto bg-gradient-to-b from-yellow-300 to-yellow-400 text-yellow-900 font-black text-xs py-1.5 rounded-full transition-all shadow-[0_3px_0_#b45309] hover:shadow-[0_2px_0_#b45309] hover:translate-y-0.5 active:shadow-none active:translate-y-[3px]">
                                    Añadir
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Flecha derecha -->
                    <button @click="scrollCarouselRight"
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-10 translate-x-3 w-8 h-8 rounded-full bg-white/20 hover:bg-white/40 text-white font-bold hidden md:flex items-center justify-center transition-all shadow-lg">
                        ›
                    </button>
                </div>
            </div>
        </section>

        <!-- ===== MEJORES JUGADORES ===== -->
        <section class="px-4 md:px-12 pb-12 max-w-5xl mx-auto">
            <!-- Título con líneas decorativas -->
            <div class="flex items-center gap-4 mb-10">
                <div class="flex-1 h-px bg-white/30"></div>
                <h2 class="text-white font-black text-2xl md:text-3xl tracking-widest uppercase whitespace-nowrap">
                    MEJORES JUGADORES
                </h2>
                <div class="flex-1 h-px bg-white/30"></div>
            </div>

            <!-- Podio: Oro en el centro más alto, Plata izquierda, Bronce derecha -->
            <div class="flex justify-center items-end gap-4 md:gap-8 mb-10">

                <!-- 2º Plata -->
                <div class="flex flex-col items-center w-1/3 max-w-[200px]">
                    <div class="relative w-full">
                        <img src="/images/Home/Ranking_Plata.webp" alt="2º Puesto" class="w-full drop-shadow-2xl" />
                        <!-- Nombre sobre la placa -->
                        <div class="absolute bottom-[36%] left-1/2 -translate-x-1/2 w-[65%] text-center">
                            <p class="text-violet-700 font-black text-xs md:text-sm truncate leading-tight"
                                style="text-shadow: 0 0 8px rgba(255,255,255,0.6)">
                                {{ topJugadores[1]?.alias || 'Jugador 2' }}
                            </p>
                        </div>
                        <!-- Puntuación dentro del trofeo (escalón inferior) -->
                        <div class="absolute bottom-[18%] left-1/2 -translate-x-1/2 w-[75%] text-center">
                            <p class="text-violet-700 font-black text-xs md:text-sm"
                                style="text-shadow: 0 0 8px rgba(255,255,255,0.6)">
                                {{ topJugadores[1]?.puntuacion?.toLocaleString() || '0' }} pts
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 1º Oro (más grande, más alto) -->
                <div class="flex flex-col items-center w-1/3 max-w-[240px] -mb-4 md:-mb-6">
                    <div class="relative w-full">
                        <img src="/images/Home/Ranking_Oro.webp" alt="1º Puesto" class="w-full drop-shadow-2xl" />
                        <!-- Nombre sobre la placa (más arriba porque la imagen es más alta) -->
                        <div class="absolute bottom-[37%] left-1/2 -translate-x-1/2 w-[65%] text-center">
                            <p class="text-violet-800 font-black text-sm md:text-base truncate leading-tight"
                                style="text-shadow: 0 0 10px rgba(255,255,255,0.7)">
                                {{ topJugadores[0]?.alias || 'Jugador 1' }}
                            </p>
                        </div>
                        <!-- Puntuación dentro del trofeo (escalón inferior) -->
                        <div class="absolute bottom-[20%] left-1/2 -translate-x-1/2 w-[75%] text-center">
                            <p class="text-violet-800 font-black text-sm md:text-base"
                                style="text-shadow: 0 0 10px rgba(255,255,255,0.7)">
                                {{ topJugadores[0]?.puntuacion?.toLocaleString() || '0' }} pts
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 3º Bronce -->
                <div class="flex flex-col items-center w-1/3 max-w-[200px]">
                    <div class="relative w-full">
                        <img src="/images/Home/Ranking_Bronce.webp" alt="3º Puesto" class="w-full drop-shadow-2xl" />
                        <!-- Nombre sobre la placa -->
                        <div class="absolute bottom-[35%] left-1/2 -translate-x-1/2 w-[65%] text-center">
                            <p class="text-violet-700 font-black text-xs md:text-sm truncate leading-tight"
                                style="text-shadow: 0 0 8px rgba(255,255,255,0.6)">
                                {{ topJugadores[2]?.alias || 'Jugador 3' }}
                            </p>
                        </div>
                        <!-- Puntuación dentro del trofeo (escalón inferior) -->
                        <div class="absolute bottom-[18%] left-1/2 -translate-x-1/2 w-[75%] text-center">
                            <p class="text-violet-700 font-black text-xs md:text-sm"
                                style="text-shadow: 0 0 8px rgba(255,255,255,0.6)">
                                {{ topJugadores[2]?.puntuacion?.toLocaleString() || '0' }} pts
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón ver ranking completo -->
            <div class="flex justify-center">
                <button 
  @click="goToRanking"
  class="bg-gradient-to-b from-yellow-300 to-yellow-400 hover:from-yellow-200 text-yellow-900 font-black px-14 py-3.5 rounded-full border-4 border-yellow-200/50 shadow-[0_4px_0_#b45309] hover:shadow-[0_2px_0_#b45309] hover:translate-y-0.5 active:shadow-none active:translate-y-1 transition-all text-base tracking-wide uppercase"
>
    Ver ranking completo
</button>
            </div>
        </section>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import useRanking from '@/composables/ranking';
import useSocialUsers from '@/composables/socialUsers';

const router = useRouter();
const busquedaJugador = ref('');

// Ranking podio
const { topJugadores, fetchTopJugadores } = useRanking();

// Carrusel social
const { socialUsers, loading: socialLoading, fetchSocialUsers } = useSocialUsers();
const carouselRef = ref(null);

const scrollCarouselLeft  = () => carouselRef.value?.scrollBy({ left: -300, behavior: 'smooth' });
const scrollCarouselRight = () => carouselRef.value?.scrollBy({ left:  300, behavior: 'smooth' });

const startGame = (mode) => {
    if (mode === 'solo') {
        router.push({ name: 'game.individual' });
    } else if (mode === 'duel') {
        router.push({ name: 'game.1vs1.lobby' });
    }
};

const goToRanking = () => {
    router.push({ name: 'game.individual.ranking' });
};

onMounted(() => {
    fetchTopJugadores();
    fetchSocialUsers();
});
</script>
