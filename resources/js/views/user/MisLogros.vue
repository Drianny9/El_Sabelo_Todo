<template>
    <div class="min-h-screen text-white overflow-x-hidden relative"
         style="background: linear-gradient(180deg, #9333EA 0%, #7C3AED 35%, #6D28D9 100%);">

        <!-- Decorative assets -->
        <img src="/images/Home/Rayo.webp" alt=""
            class="absolute right-6 top-8 w-20 md:w-28 select-none pointer-events-none drop-shadow-xl opacity-60 rotate-12" />
        <img src="/images/Home/Corazon_lila.webp" alt=""
            class="absolute left-4 top-32 w-16 md:w-20 select-none pointer-events-none drop-shadow-xl opacity-50 -rotate-12" />

        <div class="max-w-5xl mx-auto px-4 py-8 relative z-10">

            <!-- Cabecera -->
            <div class="flex items-center gap-4 mb-10">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center shadow-[0_6px_0_#b45309] border-2 border-yellow-300">
                    <i class="pi pi-trophy text-3xl text-yellow-800"></i>
                </div>
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-white leading-none uppercase tracking-wider drop-shadow-lg">Mis Logros</h1>
                    <p class="text-purple-200 mt-2 text-base font-bold">Completa retos para desbloquear logros y ganar puntos extra</p>
                </div>
            </div>

            <!-- Cargando -->
            <div v-if="loadingLogros" class="flex justify-center py-20">
                <ProgressSpinner style="width: 60px; height: 60px" />
            </div>

            <div v-else>
                <!-- ===== TARJETAS DE ESTADÍSTICAS ===== -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-10">
                    <!-- Puntuación Total -->
                    <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgba(0,0,0,0.15)] border border-white/80">
                        <span class="text-purple-500 text-xs font-black uppercase tracking-widest block mb-3">Puntuación Total</span>
                        <div class="flex items-center gap-3">
                            <img src="/images/Home/Medio_monedas.webp" alt="Moneda" class="w-10 h-10 object-contain drop-shadow-md" />
                            <span class="text-4xl font-black text-purple-800">{{ userScore }}</span>
                        </div>
                    </div>
                    <!-- Logros Desbloqueados -->
                    <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgba(0,0,0,0.15)] border border-white/80">
                        <span class="text-purple-500 text-xs font-black uppercase tracking-widest block mb-3">Logros Desbloqueados</span>
                        <span class="text-4xl font-black text-purple-800">{{ misLogros.length }}<span class="text-xl text-purple-400 font-bold"> / {{ allLogros.length }}</span></span>
                        <!-- Barra de progreso -->
                        <div class="mt-3 h-3 bg-purple-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full transition-all duration-700 shadow-sm"
                                 :style="{ width: allLogros.length > 0 ? (misLogros.length / allLogros.length * 100) + '%' : '0%' }">
                            </div>
                        </div>
                    </div>
                    <!-- Retos Pendientes -->
                    <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgba(0,0,0,0.15)] border border-white/80">
                        <span class="text-purple-500 text-xs font-black uppercase tracking-widest block mb-3">Retos Pendientes</span>
                        <span class="text-4xl font-black text-purple-800">{{ logrosPendientes.length }}</span>
                        <span class="text-purple-400 text-sm font-bold block mt-1">por desbloquear</span>
                    </div>
                </div>

                <!-- ===== SECCIÓN: RETOS PENDIENTES ===== -->
                <div v-if="logrosPendientes.length > 0" class="mb-10">
                    <h2 class="text-xl font-black text-white mb-5 flex items-center gap-2 uppercase tracking-wider drop-shadow-md">
                        <i class="pi pi-lock text-yellow-400 text-lg"></i>
                        Retos por completar
                        <span class="text-sm text-purple-200 font-bold ml-1 normal-case tracking-normal">({{ logrosPendientes.length }} disponibles)</span>
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div v-for="logro in logrosPendientes" :key="logro.id"
                             class="relative bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgba(0,0,0,0.12)] hover:shadow-[0_12px_40px_rgba(0,0,0,0.2)] transition-all duration-300 hover:-translate-y-1 group">
                            <!-- Puntos pill -->
                            <div v-if="logro.puntos > 0"
                                 class="absolute -top-3 -right-3 bg-gradient-to-b from-yellow-400 to-yellow-500 text-yellow-900 text-xs font-black px-3 py-1 rounded-full shadow-[0_3px_0_#b45309] border border-yellow-300">
                                +{{ logro.puntos }} pts
                            </div>
                            <div class="flex items-start gap-4">
                                <!-- Icono con candado -->
                                <div class="flex-shrink-0 w-14 h-14 rounded-2xl flex items-center justify-center bg-purple-100 text-purple-500 group-hover:bg-purple-200 transition-colors relative">
                                    <i :class="logro.icono || 'pi pi-star'" class="text-2xl"></i>
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-yellow-400 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                                        <i class="pi pi-lock text-[9px] text-yellow-800"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-black text-purple-800 text-base leading-tight group-hover:text-purple-600 transition-colors">{{ logro.nombre }}</h3>
                                    <p class="text-xs mt-2 text-purple-500/80 leading-snug">
                                        <i class="pi pi-flag text-[10px] mr-1 text-yellow-500"></i>{{ logro.descripcion }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== SECCIÓN: LOGROS CONSEGUIDOS ===== -->
                <div v-if="logrosConseguidos.length > 0">
                    <h2 class="text-xl font-black text-white mb-5 flex items-center gap-2 uppercase tracking-wider drop-shadow-md">
                        <i class="pi pi-check-circle text-green-400 text-lg"></i>
                        Logros conseguidos
                        <span class="text-sm text-purple-200 font-bold ml-1 normal-case tracking-normal">({{ logrosConseguidos.length }})</span>
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div v-for="logro in logrosConseguidos" :key="logro.id"
                             class="relative bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgba(0,0,0,0.12)] border-2 border-yellow-400/40 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_rgba(0,0,0,0.2)]">
                            <!-- Puntos pill -->
                            <div v-if="logro.puntos > 0"
                                 class="absolute -top-3 -right-3 bg-gradient-to-b from-yellow-400 to-yellow-500 text-yellow-900 text-xs font-black px-3 py-1 rounded-full shadow-[0_3px_0_#b45309] border border-yellow-300">
                                +{{ logro.puntos }} pts
                            </div>
                            <div class="flex items-center gap-4">
                                <!-- Icono desbloqueado -->
                                <div class="flex-shrink-0 w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-yellow-100 to-yellow-200 text-yellow-600 border border-yellow-300/50">
                                    <i :class="logro.icono || 'pi pi-star'" class="text-2xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-black text-purple-800 text-base leading-tight">{{ logro.nombre }}</h3>
                                    <p class="text-xs mt-1 text-purple-400 leading-snug">{{ logro.descripcion }}</p>
                                    <span class="mt-2 inline-flex items-center gap-1 text-[10px] text-green-600 font-black uppercase tracking-wider bg-green-100 px-2 py-0.5 rounded-full">
                                        <i class="pi pi-check text-[10px]"></i> Desbloqueado
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sin logros aún -->
                <div v-if="logrosConseguidos.length === 0 && allLogros.length > 0" class="text-center py-10 relative">
                    <img src="/images/Home/Rayo.webp" alt=""
                        class="absolute left-1/2 -translate-x-1/2 top-0 w-16 opacity-30 pointer-events-none select-none" />
                    <p class="text-white text-lg font-black drop-shadow-md">Juega una partida para empezar a desbloquear logros 🎮</p>
                </div>
            </div>
        </div>

        <!-- Monedas decorativas base -->
        <div class="relative w-full h-24 mt-4">
            <img src="/images/Home/Monedas_izquierda.webp" alt=""
                class="absolute bottom-0 left-0 h-24 select-none pointer-events-none" />
            <img src="/images/Home/Monedas_derecha.webp" alt=""
                class="absolute bottom-0 right-0 h-24 select-none pointer-events-none" />
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import ProgressSpinner from 'primevue/progressspinner';
import useLogros from '@/composables/logros';

//Usamos el composable que encapsula toda la lógica de logros y comunicación con la API
const {
    allLogros,
    misLogros,
    userScore,
    loading: loadingLogros,
    logrosPendientes,
    logrosConseguidos,
    fetchLogrosData
} = useLogros();

onMounted(() => {
    fetchLogrosData();
});
</script>
