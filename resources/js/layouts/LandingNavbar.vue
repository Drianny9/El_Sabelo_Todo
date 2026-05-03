<template>
    <div class="w-full z-50 transition-all duration-300" :style="{ backgroundColor: isAppRoute ? '#1a103d' : '#9333EA' }">
        <nav class="mx-4 mt-2 px-6 py-3 flex items-center justify-between rounded-2xl shadow-xl"
             style="background: linear-gradient(135deg, #7B2FF2 0%, #5B1DA8 50%, #4A1590 100%); border: 2px solid rgba(255,255,255,0.15);">
            <!-- Logo / Branding -->
            <router-link to="/" class="flex items-center gap-3 group">
                <img src="/images/Logo_ElSabeloTodo.svg" alt="El Sabelotodo" class="h-10 w-auto group-hover:scale-110 transition-transform drop-shadow-lg" />
                <span class="text-white font-black text-xl md:text-2xl tracking-wider uppercase drop-shadow-md hidden sm:inline">
                    EL SABELOTODO
                </span>
            </router-link>

            <!-- Mobile Menu Button -->
            <button v-if="!isDesktop" @click="visibleMobileMenu = true"
                class="p-2 rounded-lg hover:bg-white/10 transition-colors text-white">
                <i class="pi pi-bars text-2xl"></i>
            </button>

            <!-- Desktop Menu -->
            <div v-if="isDesktop" class="flex items-center gap-2">
                <!-- Nav Links -->
                <router-link v-for="link in navLinks" :key="link.route" :to="link.route"
                    class="text-white/80 hover:text-white font-bold text-sm uppercase tracking-widest px-4 py-2 rounded-lg hover:bg-white/10 transition-all">
                    {{ link.label }}
                </router-link>

                <!-- Separator -->
                <div class="w-px h-6 bg-white/20 mx-2"></div>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <!-- Puntuación del usuario logueado (CLICABLE) -->
                    <router-link v-if="authStore().user?.name" to="/app/mis-logros"
                         class="flex items-center gap-2 px-4 py-1.5 rounded-full hover:bg-white/10 transition-all cursor-pointer group" 
                         title="Ver mi perfil y logros">
                        <div class="w-6 h-6 rounded-full bg-yellow-400 flex items-center justify-center shadow-md border border-yellow-300 group-hover:scale-110 transition-transform">
                            <i class="pi pi-star-fill text-yellow-700 text-[10px]"></i>
                        </div>
                        <span class="text-white font-black text-sm tracking-wide">{{ (authStore().user?.puntuacion || 0).toLocaleString() }}</span>
                    </router-link>

                    <template v-if="!authStore().user?.name">
                        <router-link to="/login">
                            <button
                                class="bg-gradient-to-b from-yellow-300 to-yellow-500 hover:from-yellow-200 hover:to-yellow-400 text-yellow-900 font-black text-sm py-2 px-5 rounded-full border-2 border-yellow-200/50 shadow-[0_3px_0_#b45309] hover:shadow-[0_1px_0_#b45309] hover:translate-y-0.5 active:shadow-none active:translate-y-1 transition-all uppercase tracking-wider">
                                Login
                            </button>
                        </router-link>
                        <router-link to="/register">
                            <button
                                class="bg-gradient-to-b from-yellow-300 to-yellow-500 hover:from-yellow-200 hover:to-yellow-400 text-yellow-900 font-black text-sm py-2 px-5 rounded-full border-2 border-yellow-200/50 shadow-[0_3px_0_#b45309] hover:shadow-[0_1px_0_#b45309] hover:translate-y-0.5 active:shadow-none active:translate-y-1 transition-all uppercase tracking-wider">
                                Registro
                            </button>
                        </router-link>
                    </template>

                    <div v-else class="flex items-center gap-2">
                        <button type="button" @click="toggle"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-full hover:bg-white/10 transition-colors text-white">
                            <Avatar :image="authStore().user.avatar" :label="authStore().user.name[0]" shape="circle"
                                size="small" class="!bg-white !text-purple-900 font-black shadow-sm" />
                            <span class="text-sm font-bold hidden xl:inline uppercase tracking-wider">{{ authStore().user?.name }}</span>
                            <i class="pi pi-chevron-down text-xs"></i>
                        </button>
                        <Menu ref="menu" :model="items" popup />
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div v-if="visibleMobileMenu" class="fixed inset-0 z-50 lg:hidden">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="visibleMobileMenu = false"></div>

            <!-- Panel -->
            <div class="absolute right-0 top-0 h-full w-full sm:w-80 shadow-2xl"
                style="background: linear-gradient(180deg, #7B2FF2 0%, #4A1590 100%);" @click.stop>
                <!-- Header -->
                <div class="flex items-center justify-between p-5 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <img src="/images/Logo_ElSabeloTodo.svg" alt="logo" class="h-8 w-auto drop-shadow-md" />
                        <span class="text-white font-black text-lg tracking-wider uppercase">Menú</span>
                    </div>
                    <button @click="visibleMobileMenu = false"
                        class="p-2 rounded-lg hover:bg-white/10 transition-colors text-white">
                        <i class="pi pi-times text-xl"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="flex flex-col gap-2 p-4 h-[calc(100%-5rem)] overflow-y-auto">
                    <!-- Puntuación mobile (CLICABLE) -->
                    <router-link v-if="authStore().user?.name" to="/app/mis-logros"
                         @click="visibleMobileMenu = false"
                         class="flex items-center gap-3 p-3 bg-white/10 rounded-xl mb-2 hover:bg-white/20 active:scale-95 transition-all cursor-pointer">
                        <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center shadow-md border border-yellow-300">
                            <i class="pi pi-star-fill text-yellow-700 text-sm"></i>
                        </div>
                        <span class="text-white font-black text-lg">{{ (authStore().user?.puntuacion || 0).toLocaleString() }} pts</span>
                    </router-link>

                    <!-- Nav Links -->
                    <div class="flex flex-col gap-1">
                        <router-link v-if="authStore().isAdmin" to="/admin" @click="visibleMobileMenu = false"
                            class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/10 transition-colors text-white/80 hover:text-white">
                            <i class="pi pi-cog"></i>
                            <span class="font-bold uppercase tracking-wider text-sm">Admin Panel</span>
                        </router-link>
                        <router-link v-for="link in navLinks" :key="link.route" :to="link.route"
                            @click="visibleMobileMenu = false"
                            class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/10 transition-colors text-white/80 hover:text-white">
                            <i :class="link.icon"></i>
                            <span class="font-bold uppercase tracking-wider text-sm">{{ link.label }}</span>
                        </router-link>
                    </div>

                    <!-- Actions -->
                    <div class="mt-auto border-t border-white/10 -mx-4 px-4 pt-4">
                        <template v-if="!authStore().user?.name">
                            <router-link to="/login" @click="visibleMobileMenu = false" class="block mb-3">
                                <button class="w-full bg-gradient-to-b from-yellow-300 to-yellow-500 text-yellow-900 font-black py-3 rounded-full border-2 border-yellow-200/50 shadow-[0_4px_0_#b45309] uppercase tracking-wider text-sm">
                                    Iniciar Sesión
                                </button>
                            </router-link>
                            <router-link to="/register" @click="visibleMobileMenu = false" class="block">
                                <button class="w-full bg-purple-500/50 hover:bg-purple-400/60 text-white font-black py-3 rounded-full border border-purple-300/30 uppercase tracking-wider text-sm">
                                    Registrarse
                                </button>
                            </router-link>
                        </template>
                        <div v-else class="flex flex-col gap-2">
                            <router-link to="/app/profile" @click="visibleMobileMenu = false"
                                class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/10 transition-colors text-white/80 hover:text-white">
                                <i class="pi pi-user"></i>
                                <span class="font-bold uppercase tracking-wider text-sm">Perfil</span>
                            </router-link>
                            <button @click="handleLogout"
                                class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-red-500/20 transition-colors text-red-300 hover:text-red-200">
                                <i class="pi pi-sign-out"></i>
                                <span class="font-bold uppercase tracking-wider text-sm">Cerrar Sesión</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useLayout } from "@/composables/layout.js";
import useAuth from "@/composables/auth";
import { authStore } from "../store/auth";

import { ref, computed, onBeforeMount, onMounted, onUnmounted } from "vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const isAppRoute = computed(() => route.path.startsWith('/app')); //Comprobamos si el usuario esta dentro de las rutas /app para que tenga un fondo u otro
const menu = ref();
const visibleMobileMenu = ref(false);
const isScrolled = ref(false);
const isDesktop = ref(window.innerWidth >= 992);

const { processing, logout } = useAuth();
const { setDefaultMode } = useLayout();

const navLinks = [
    { label: 'Inicio', route: '/', icon: 'pi pi-home' },
    { label: 'Cómo Jugar', route: '/como-jugar', icon: 'pi pi-info-circle' },
    { label: 'Ranking', route: '/ranking', icon: 'pi pi-chart-bar' }
];

const items = computed(() => [
    {
        items: [
            { label: 'Perfil', icon: 'pi pi-user', command: () => router.push('/app/profile') },
            {
                label: 'Panel Admin',
                icon: 'pi pi-cog',
                command: () => router.push('/admin'),
                visible: authStore().isAdmin
            },
            { separator: true },
            {
                label: 'Cerrar sesión',
                icon: 'pi pi-power-off',
                class: 'text-red-500',
                command: () => {
                    handleLogout()
                }
            }
        ]
    }
]);

const toggle = (event) => {
    menu.value.toggle(event);
};

const navigateToDashboard = () => {
    visibleMobileMenu.value = false;
    router.push('/app');
}

const handleLogout = () => {
    visibleMobileMenu.value = false;
    logout();
}

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
}

const handleResize = () => {
    isDesktop.value = window.innerWidth >= 992;
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('resize', handleResize);
});

onBeforeMount(() => {
    setDefaultMode()
})
</script>
