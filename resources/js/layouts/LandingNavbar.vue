<template>
    <div class="fixed w-full z-50 border-b border-gray-200 dark:border-gray-800 transition-all duration-300"
        :class="isDarkTheme ? 'bg-gray-900' : 'bg-white'">
        <nav class="container mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Logo -->
            <router-link to="/" class="flex items-center gap-2">
                <img src="/images/logo.svg" alt="logo" class="h-10 w-auto" />
            </router-link>

            <!-- Mobile Menu Button -->
            <button v-if="!isDesktop" @click="visibleMobileMenu = true"
                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <i class="pi pi-bars text-2xl"></i>
            </button>

            <!-- Desktop Menu -->
            <div v-if="isDesktop" class="flex items-center gap-6">
                <router-link v-for="link in navLinks" :key="link.route" :to="link.route"
                    class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">
                    {{ link.label }}
                </router-link>

                <!-- Actions -->
                <div class="flex items-center gap-3 pl-6 border-l border-gray-200 dark:border-gray-700">
                    <LocaleSwitcher />

                    <button type="button" @click="toggleDarkMode"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i :class="isDarkTheme ? 'pi-moon' : 'pi-sun'" class="pi text-lg"></i>
                    </button>

                    <template v-if="!authStore().user?.name">
                        <router-link to="/login">
                            <Button label="Login" text size="small" />
                        </router-link>
                        <router-link to="/register">
                            <Button label="Registro" severity="primary" size="small" />
                        </router-link>
                    </template>

                    <div v-else class="flex items-center gap-2">
                        <router-link v-if="authStore().isAdmin" to="/admin">
                            <Button label="Admin Panel" severity="contrast" size="small" />
                        </router-link>
                        <button type="button" @click="toggle"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <Avatar :image="authStore().user.avatar" :label="authStore().user.name[0]" shape="circle"
                                size="small" />
                            <span class="text-sm font-medium hidden xl:inline">{{ authStore().user?.name }}</span>
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
            <div class="absolute inset-0 bg-black/50" @click="visibleMobileMenu = false"></div>

            <!-- Panel -->
            <div class="absolute right-0 top-0 h-full w-full sm:w-80 shadow-2xl"
                :class="isDarkTheme ? 'bg-gray-900 text-white' : 'bg-white text-gray-900'" @click.stop>
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-800">
                    <div class="flex items-center gap-2">
                        <img src="/images/logo.svg" alt="logo" class="h-8" />
                        <span class="font-bold text-lg">Menu</span>
                    </div>
                    <button @click="visibleMobileMenu = false"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i class="pi pi-times text-xl"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="flex flex-col gap-4 p-4 h-[calc(100%-5rem)] overflow-y-auto">
                    <!-- Nav Links -->
                    <div class="flex flex-col gap-1">
                        <router-link v-if="authStore().isAdmin" to="/admin" @click="visibleMobileMenu = false"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <i class="pi pi-cog"></i>
                            <span>Admin Panel</span>
                        </router-link>
                        <router-link v-for="link in navLinks" :key="link.route" :to="link.route"
                            @click="visibleMobileMenu = false"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <i :class="link.icon"></i>
                            <span>{{ link.label }}</span>
                        </router-link>
                    </div>

                    <!-- Actions -->
                    <div class="mt-auto border-t border-gray-200 dark:border-gray-800 -mx-4 px-4 pt-4">
                        <template v-if="!authStore().user?.name">
                            <router-link to="/login" @click="visibleMobileMenu = false">
                                <Button label="Iniciar Sesión" outlined class="w-full" />
                            </router-link>
                            <router-link to="/register" @click="visibleMobileMenu = false">
                                <Button label="Registrarse" class="w-full" />
                            </router-link>
                        </template>
                        <div v-else>
                            <button @click="handleLogout"
                                class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="pi pi-sign-out"></i>
                                <span>Logout</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Spacer -->
    <div class="h-20"></div>
</template>

<script setup>
import { useLayout } from "@/composables/layout.js";
import useAuth from "@/composables/auth";
import { authStore } from "../store/auth";
import LocaleSwitcher from "../components/LocaleSwitcher.vue";
import { ref, computed, onBeforeMount, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const menu = ref();
const visibleMobileMenu = ref(false);
const isScrolled = ref(false);
const isDesktop = ref(window.innerWidth >= 992);

const { processing, logout } = useAuth();
const { toggleDarkMode, isDarkTheme, setDefaultMode } = useLayout();

const navLinks = [
    { label: 'Inicio', route: '/', icon: 'pi pi-home' }
];

const items = computed(() => [
    {
        items: [
            { label: 'Perfil', icon: 'pi pi-user', command: () => router.push('/app/profile') },
            {
                label: 'Panel Admin',
                icon: 'pi pi-cog',
                route: '/admin',
                visible: authStore().user?.roles?.some(r => r.name.includes('admin')) || false
            },
            { label: 'Mis Logros', icon: 'pi pi-th-large', command: () => router.push('/mis-logros') },
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
