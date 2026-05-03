<template>
    <div class="min-h-screen bg-gradient-to-tr from-[#9333EA] via-[#7C3AED] to-[#6D28D9] p-4 md:p-12 pb-20">
        <div class="max-w-6xl mx-auto">
            
            <!-- ===== CABECERA ===== -->
            <div class="flex items-center gap-4 mb-10">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center shadow-[0_6px_0_#b45309] border-2 border-yellow-300">
                    <i class="pi pi-user text-3xl text-yellow-800"></i>
                </div>
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-white leading-none uppercase tracking-wider drop-shadow-lg">Mi Perfil</h1>
                    <p class="text-purple-200 mt-2 text-base font-bold">Gestiona tu información personal y cuenta</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Avatar Section -->
                <div class="col-span-1 lg:col-span-4">
                    <div class="bg-white/10 backdrop-blur-xl rounded-[2.5rem] border-4 border-white/20 p-8 shadow-2xl sticky top-8">
                        <div class="flex flex-col items-center">
                            
                            <div class="relative group">
                                <div class="absolute -inset-1 bg-gradient-to-r from-purple-400 to-blue-400 rounded-full blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                                <div class="relative w-48 h-48 rounded-full border-8 border-white/30 overflow-hidden shadow-2xl bg-purple-900/50">
                                    <img 
                                        :src="user.avatar || 'https://bootdey.com/img/Content/avatar/avatar7.png'" 
                                        alt="Avatar"
                                        class="w-full h-full object-cover transform transition duration-500 group-hover:scale-110"
                                    >
                                </div>
                                <div class="absolute bottom-2 right-2 bg-yellow-400 w-12 h-12 rounded-full border-4 border-purple-800 flex items-center justify-center shadow-lg">
                                    <i class="pi pi-camera text-purple-900 font-bold"></i>
                                </div>
                            </div>

                            <div class="mt-8 w-full space-y-4">
                                <div class="text-center">
                                    <p class="text-yellow-400 font-black uppercase text-xs tracking-[0.3em] mb-1">Nombre de Usuario</p>
                                    <h2 class="text-2xl font-black text-white uppercase tracking-tight">{{ user.alias || 'Jugador' }}</h2>
                                    <p class="text-purple-200 font-medium opacity-70">{{ user.email }}</p>
                                </div>

                                <div class="pt-4 border-t border-white/10">
                                    <FileUpload name="picture" url="/api/users/updateimg" @before-upload="onBeforeUpload"
                                        @upload="onTemplatedUpload($event)" accept="image/*" :maxFileSize="1500000"
                                        @select="onSelectedFiles" mode="basic" :auto="true" chooseLabel="Subir Nueva Foto"
                                        class="custom-upload-btn w-full shadow-lg"
                                    />
                                    <p class="text-[10px] text-center text-purple-200/50 mt-2 font-bold uppercase tracking-widest">Formatos: JPG, PNG • Máx 1.5MB</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Personal Data Section -->
                <div class="col-span-1 lg:col-span-8">
                    <div class="bg-white/10 backdrop-blur-xl rounded-[2.5rem] border-4 border-white/20 p-8 md:p-12 shadow-2xl">
                        
                        <div class="flex items-center gap-4 mb-10">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center divide-white/20">
                                <i class="pi pi-id-card text-white text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-white uppercase tracking-widest">Información Personal</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                            
                            <div class="space-y-4 group">
                                <label class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-purple-200/70 group-hover:text-white transition-colors">
                                    <i class="pi pi-user text-yellow-400"></i> Nombre Real
                                </label>
                                <div class="bg-white/5 border-2 border-white/10 rounded-2xl p-5 text-white font-bold text-lg shadow-inner group-hover:bg-white/10 transition-all">
                                    {{ user.name }}
                                </div>
                            </div>

                            <div class="space-y-4 group">
                                <label class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-purple-200/70 group-hover:text-white transition-colors">
                                    <i class="pi pi-envelope text-yellow-400"></i> Correo Electrónico
                                </label>
                                <div class="bg-white/5 border-2 border-white/10 rounded-2xl p-5 text-white font-bold text-lg shadow-inner group-hover:bg-white/10 transition-all">
                                    {{ user.email }}
                                </div>
                            </div>

                            <div class="space-y-4 group">
                                <label class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-purple-200/70 group-hover:text-white transition-colors">
                                    <i class="pi pi-bookmark text-yellow-400"></i> Primer Apellido
                                </label>
                                <div class="bg-white/5 border-2 border-white/10 rounded-2xl p-5 text-white font-bold text-lg shadow-inner group-hover:bg-white/10 transition-all">
                                    {{ user.surname1 || '---' }}
                                </div>
                            </div>

                            <div class="space-y-4 group">
                                <label class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-purple-200/70 group-hover:text-white transition-colors">
                                    <i class="pi pi-bookmark text-yellow-400"></i> Segundo Apellido
                                </label>
                                <div class="bg-white/5 border-2 border-white/10 rounded-2xl p-5 text-white font-bold text-lg shadow-inner group-hover:bg-white/10 transition-all">
                                    {{ user.surname2 || '---' }}
                                </div>
                            </div>

                        </div>

                        <!-- Stats View -->
                        <div class="mt-16 pt-10 border-t border-white/10">
                            <h3 class="text-sm font-black text-purple-200 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="pi pi-chart-bar text-yellow-400"></i> Estadísticas Globales
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="p-6 rounded-[2rem] bg-gradient-to-br from-yellow-400 to-yellow-600 shadow-xl shadow-yellow-900/20 transform hover:scale-[1.02] transition-transform">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-[10px] font-black uppercase text-yellow-900/70 mb-1 tracking-widest">Puntaje Total</p>
                                            <p class="text-4xl font-black text-yellow-950">{{ user.puntuacion || 0 }}</p>
                                        </div>
                                        <i class="pi pi-star-fill text-yellow-900/30 text-3xl"></i>
                                    </div>
                                    <p class="text-[10px] font-bold text-yellow-900/50 mt-4 uppercase tracking-tighter">Puntos acumulados en todas las partidas</p>
                                </div>

                                <div class="p-6 rounded-[2rem] bg-gradient-to-br from-blue-400 to-blue-600 shadow-xl shadow-blue-900/20 transform hover:scale-[1.02] transition-transform">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-[10px] font-black uppercase text-blue-100/70 mb-1 tracking-widest">Logros Obtenidos</p>
                                            <p class="text-4xl font-black text-white">{{ user.logros_count || 0 }}</p>
                                        </div>
                                        <i class="pi pi-trophy text-blue-200/30 text-3xl"></i>
                                    </div>
                                    <p class="text-[10px] font-bold text-blue-100/50 mt-4 uppercase tracking-tighter">Objetivos especiales desbloqueados</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { usePrimeVue } from 'primevue/config';
import useUsers from "@/composables/users";
import { authStore } from "@/store/auth";

const auth = authStore();
const $primevue = usePrimeVue();
const { getUser, user } = useUsers();

onMounted(() => {
    getUser(auth.user.id)
})

const onBeforeUpload = (event) => {
    event.formData.append('id', user.value.id)
};

const onTemplatedUpload = (event) => {
    // Recargar usuario para actualizar avatar
    getUser(auth.user.id);
};

const onSelectedFiles = (event) => {
    // Lógica adicional si es necesaria
};
</script>

<style>
/* Personalización de PrimeVue FileUpload para encajar con el diseño */
.custom-upload-btn .p-button {
    background: linear-gradient(to bottom, #fde047, #eab308) !important;
    border: none !important;
    border-bottom: 4px solid #a16207 !important;
    color: #422006 !important;
    font-weight: 900 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.1em !important;
    border-radius: 9999px !important;
    width: 100% !important;
    padding: 1rem !important;
    transition: all 0.2s !important;
}

.custom-upload-btn .p-button:hover {
    transform: translateY(-2px) !important;
    filter: brightness(1.1) !important;
}

.custom-upload-btn .p-button:active {
    transform: translateY(2px) !important;
    border-bottom: 0 !important;
}
</style>
