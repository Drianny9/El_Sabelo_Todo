<template>
    <div class="min-h-screen bg-gray-900 text-white p-6 pb-12">
        <div class="max-w-5xl mx-auto">

            <!-- Cabecera -->
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-900/50">
                    <i class="pi pi-star text-3xl text-yellow-300"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white leading-none">Mis Logros</h1>
                    <p class="text-gray-400 mt-1 text-sm">Completa retos para desbloquear logros y ganar puntos extra</p>
                </div>
            </div>

            <!-- Cargando -->
            <div v-if="loadingLogros" class="flex justify-center py-20">
                <ProgressSpinner style="width: 60px; height: 60px" />
            </div>

            <div v-else>
                <!-- Tarjetas de estadísticas -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <div class="bg-gradient-to-br from-indigo-900 to-blue-900 border border-indigo-700/50 rounded-2xl p-5 shadow-xl">
                        <span class="text-indigo-300 text-xs font-semibold uppercase tracking-widest block mb-2">Puntuación Total</span>
                        <span class="text-4xl font-black text-yellow-400 flex items-center gap-2">
                            <i class="pi pi-star-fill text-3xl"></i>
                            {{ userScore }}
                        </span>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-900 to-teal-900 border border-emerald-700/50 rounded-2xl p-5 shadow-xl">
                        <span class="text-emerald-300 text-xs font-semibold uppercase tracking-widest block mb-2">Logros Desbloqueados</span>
                        <span class="text-4xl font-black text-white">{{ misLogros.length }}<span class="text-xl text-emerald-400 font-medium"> / {{ allLogros.length }}</span></span>
                        <!-- Barra de progreso -->
                        <div class="mt-3 h-2 bg-emerald-950 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-400 rounded-full transition-all duration-700"
                                 :style="{ width: allLogros.length > 0 ? (misLogros.length / allLogros.length * 100) + '%' : '0%' }">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-amber-900 to-orange-900 border border-amber-700/50 rounded-2xl p-5 shadow-xl">
                        <span class="text-amber-300 text-xs font-semibold uppercase tracking-widest block mb-2">Retos Pendientes</span>
                        <span class="text-4xl font-black text-white">{{ logrosPendientes.length }}</span>
                        <span class="text-amber-300 text-sm block mt-1">por desbloquear</span>
                    </div>
                </div>

                <!-- === SECCIÓN: RETOS PENDIENTES === -->
                <div v-if="logrosPendientes.length > 0" class="mb-10">
                    <h2 class="text-xl font-bold text-amber-400 mb-4 flex items-center gap-2">
                        <i class="pi pi-lock text-lg"></i>
                        Retos por completar
                        <span class="text-sm text-gray-500 font-normal ml-1">({{ logrosPendientes.length }} disponibles)</span>
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div v-for="logro in logrosPendientes" :key="logro.id"
                             class="relative p-5 rounded-2xl border border-amber-800/40 bg-gray-800/80 hover:border-amber-600/60 transition-all duration-300 hover:-translate-y-1 shadow-md group">
                            <!-- Puntos del logro -->
                            <div v-if="logro.puntos > 0"
                                 class="absolute -top-3 -right-3 bg-amber-500 text-amber-900 text-xs font-bold px-2 py-1 rounded-full shadow-md border-2 border-gray-900">
                                +{{ logro.puntos }} pts
                            </div>
                            <div class="flex items-start gap-4">
                                <!-- Icono con candado -->
                                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-gray-900 border border-gray-700 text-amber-600 group-hover:text-amber-400 transition-colors relative">
                                    <i :class="logro.icono || 'pi pi-star'" class="text-xl"></i>
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-gray-700 rounded-full flex items-center justify-center border border-gray-600">
                                        <i class="pi pi-lock text-[9px] text-gray-400"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-200 text-base leading-tight group-hover:text-white transition-colors">{{ logro.nombre }}</h3>
                                    <!-- La descripción indica el reto a cumplir -->
                                    <p class="text-xs mt-2 text-amber-400/80 bg-amber-900/20 border border-amber-800/30 rounded-lg px-2 py-1 leading-snug">
                                        <i class="pi pi-flag text-[10px] mr-1"></i>{{ logro.descripcion }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- === SECCIÓN: LOGROS CONSEGUIDOS === -->
                <div v-if="logrosConseguidos.length > 0">
                    <h2 class="text-xl font-bold text-emerald-400 mb-4 flex items-center gap-2">
                        <i class="pi pi-check-circle text-lg"></i>
                        Logros conseguidos
                        <span class="text-sm text-gray-500 font-normal ml-1">({{ logrosConseguidos.length }})</span>
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div v-for="logro in logrosConseguidos" :key="logro.id"
                             class="relative p-5 rounded-2xl border border-indigo-500/40 bg-gradient-to-br from-indigo-900/80 to-slate-800 shadow-lg shadow-indigo-900/20 transition-all duration-300 hover:-translate-y-1">
                            <!-- Puntos del logro -->
                            <div v-if="logro.puntos > 0"
                                 class="absolute -top-3 -right-3 bg-yellow-500 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full shadow-md border-2 border-gray-900">
                                +{{ logro.puntos }} pts
                            </div>
                            <div class="flex items-center gap-4">
                                <!-- Icono desbloqueado -->
                                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-slate-900 text-yellow-400 shadow-inner">
                                    <i :class="logro.icono || 'pi pi-star'" class="text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-indigo-200 text-base leading-tight">{{ logro.nombre }}</h3>
                                    <p class="text-xs mt-1 text-indigo-100/60 leading-snug">{{ logro.descripcion }}</p>
                                    <span class="mt-2 inline-flex items-center gap-1 text-[10px] text-emerald-400 font-semibold uppercase tracking-wider">
                                        <i class="pi pi-check text-[10px]"></i> Desbloqueado
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sin logros aún -->
                <div v-if="logrosConseguidos.length === 0 && allLogros.length > 0" class="text-center py-6 text-gray-600">
                    <p class="text-sm">Juega una partida para empezar a desbloquear logros 🎮</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import ProgressSpinner from 'primevue/progressspinner';

//Estados reactivos para almacenar los datos de la API
const allLogros = ref([]); //Catálogo global: todos los logros del sistema
const misLogros = ref([]); //Solo los logros que tiene desbloqueados el usuario actual
const userScore = ref(0); //Puntuación total acumulada del usuario
const loadingLogros = ref(true);

//Computed: Logros que aún NO tiene el usuario (retos pendientes de completar)
const logrosPendientes = computed(() => {
    return allLogros.value.filter(logro => !hasLogro(logro.id));
});

//Computed: Logros que SÍ tiene el usuario (ya conseguidos)
const logrosConseguidos = computed(() => {
    return allLogros.value.filter(logro => hasLogro(logro.id));
});

//Función que llama a los dos endpoints y rellena el estado del componente
const fetchLogrosData = async () => {
    try {
        //1. Pedimos el catálogo completo de logros del sistema para pintarlos todos
        const resCat = await axios.get('/api/logros');
        allLogros.value = resCat.data;

        //2. Pedimos los logros y la puntuación específica del usuario autenticado
        const resUser = await axios.get('/api/logros/mis-logros');
        misLogros.value = resUser.data.logros;
        userScore.value = resUser.data.puntuacion || 0;
    } catch (error) {
        console.error("Error al cargar los logros:", error);
    } finally {
        loadingLogros.value = false;
    }
};

//Función de ayuda para comprobar si el usuario ya tiene un logro concreto por su id
const hasLogro = (id) => {
    return misLogros.value.some(l => l.id === id);
};

onMounted(() => {
    fetchLogrosData();
});
</script>
