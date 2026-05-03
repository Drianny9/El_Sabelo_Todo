<template>
    <div class="min-h-screen text-white pb-12"
        style="background: linear-gradient(180deg, #9333EA 0%, #7C3AED 35%, #6D28D9 100%);">
        
        <!-- ===== HERO / TITLE ===== -->
        <section class="relative text-center pt-16 pb-8 px-4">
            <h1 class="text-4xl md:text-5xl font-black tracking-widest uppercase text-white drop-shadow-2xl">
                RANKING GLOBAL
            </h1>
            <p class="text-purple-200 text-lg mt-2 font-medium">
                Los mejores sabelotodos de la plataforma
            </p>
        </section>

        <div v-if="loading" class="flex justify-center py-20">
            <i class="pi pi-spin pi-spinner text-white text-4xl"></i>
        </div>

        <div v-else class="max-w-4xl mx-auto px-4">
            <!-- ===== PODIUM ===== -->
            <div class="flex justify-center items-end gap-4 md:gap-8 mb-12 mt-4">
                <!-- 2º Plata -->
                <div class="flex flex-col items-center w-1/3 max-w-[180px]">
                    <div class="relative w-full">
                        <img src="/images/Home/Ranking_Plata.webp" alt="2º Puesto" class="w-full drop-shadow-2xl" />
                        <div class="absolute bottom-[36%] left-1/2 -translate-x-1/2 w-[65%] text-center">
                            <p class="text-violet-700 font-black text-xs md:text-sm truncate leading-tight">
                                {{ ranking[1]?.alias || '???' }}
                            </p>
                        </div>
                        <div class="absolute bottom-[18%] left-1/2 -translate-x-1/2 w-[75%] text-center">
                            <p class="text-violet-700 font-black text-xs md:text-sm">
                                {{ ranking[1]?.puntuacion?.toLocaleString() || '0' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 1º Oro -->
                <div class="flex flex-col items-center w-1/3 max-w-[220px] -mb-4 md:-mb-6">
                    <div class="relative w-full">
                        <img src="/images/Home/Ranking_Oro.webp" alt="1º Puesto" class="w-full drop-shadow-2xl" />
                        <div class="absolute bottom-[37%] left-1/2 -translate-x-1/2 w-[65%] text-center">
                            <p class="text-violet-800 font-black text-sm md:text-base truncate leading-tight">
                                {{ ranking[0]?.alias || '???' }}
                            </p>
                        </div>
                        <div class="absolute bottom-[20%] left-1/2 -translate-x-1/2 w-[75%] text-center">
                            <p class="text-violet-800 font-black text-sm md:text-base">
                                {{ ranking[0]?.puntuacion?.toLocaleString() || '0' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 3º Bronce -->
                <div class="flex flex-col items-center w-1/3 max-w-[180px]">
                    <div class="relative w-full">
                        <img src="/images/Home/Ranking_Bronce.webp" alt="3º Puesto" class="w-full drop-shadow-2xl" />
                        <div class="absolute bottom-[35%] left-1/2 -translate-x-1/2 w-[65%] text-center">
                            <p class="text-violet-700 font-black text-xs md:text-sm truncate leading-tight">
                                {{ ranking[2]?.alias || '???' }}
                            </p>
                        </div>
                        <div class="absolute bottom-[18%] left-1/2 -translate-x-1/2 w-[75%] text-center">
                            <p class="text-violet-700 font-black text-xs md:text-sm">
                                {{ ranking[2]?.puntuacion?.toLocaleString() || '0' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== RANKING LIST ===== -->
            <div class="bg-white rounded-3xl overflow-hidden shadow-2xl border-4 border-white/20 mb-8 text-gray-800">
                <div v-for="(user, index) in ranking" :key="user.id || index" 
                     class="flex items-center px-6 py-4 border-b border-gray-600 last:border-0 hover:bg-gray-50 transition-colors">
                    
                    <div class="w-12 font-black text-xl text-purple-600">
                        {{ index + 1 }}.
                    </div>
                    
                    <div class="flex-grow flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold overflow-hidden border border-purple-200">
                            <img v-if="user.avatar" :src="user.avatar" class="w-full h-full object-cover" />
                            <span v-else>{{ user.alias?.charAt(0).toUpperCase() || '?' }}</span>
                        </div>
                        <span class="font-bold text-lg text-gray-700">{{ user.alias || 'Anónimo' }}</span>
                        <span v-if="user.id === auth.user?.id" class="text-xs bg-purple-100 text-purple-600 px-2 py-0.5 rounded-full font-bold">TÚ</span>
                    </div>

                    <div class="text-right font-black text-purple-800 text-lg">
                        {{ user.puntuacion?.toLocaleString() }} <span class="text-xs font-normal text-gray-400 ml-1">pts</span>
                    </div>
                </div>
            </div>

            <!-- ===== TU POSICIÓN ===== -->
            <div v-if="auth.authenticated" class="mt-8 bg-black/30 backdrop-blur-md rounded-3xl p-6 border border-white/20 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-200 text-sm font-bold uppercase tracking-widest mb-1">Tu Posición</p>
                        <div class="flex items-center gap-4">
                            <span class="text-4xl font-black text-white">{{ userPosition }}.</span>
                            <div>
                                <p class="text-xl font-bold text-white">{{ auth.user.name }}</p>
                                <p class="text-purple-200 text-sm">{{ auth.user.puntuacion?.toLocaleString() }} puntos acumulados</p>
                            </div>
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-16 h-16 rounded-2xl bg-yellow-400 flex items-center justify-center shadow-lg border-2 border-yellow-300 transform rotate-3">
                            <i class="pi pi-star-fill text-yellow-800 text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import useRanking from '@/composables/ranking';
import { authStore } from "@/store/auth";

const auth = authStore();
const { ranking, loading, fetchRanking } = useRanking();

const userPosition = computed(() => {
    if (!auth.user || !ranking.value.length) return '??';
    const index = ranking.value.findIndex(u => u.id === auth.user.id);
    return index !== -1 ? index + 1 : '??';
});

onMounted(() => {
    fetchRanking();
});
</script>