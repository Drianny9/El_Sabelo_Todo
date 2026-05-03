<template>
    <div class="flex flex-col h-screen overflow-hidden">
        <!-- ===== Top Navbar (Only for Users) ===== -->
        <LandingNavbar v-if="!isAdmin" />

        <div class="flex flex-1 overflow-hidden relative">
            <!-- ===== Sidebar Start ===== -->
            <MainSidebar 
                :sidebarOpen="sidebarOpen" 
                :isCollapsed="isCollapsed"
                :menuItems="menuItems"
                @toggleSidebar="toggleSidebar"
                @toggleCollapse="toggleCollapse"
            />
            <!-- ===== Sidebar End ===== -->

            <!-- ===== Content Area Start ===== -->
            <div :class="['relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden', isAdmin ? 'bg-slate-50' : 'bg-[#1a103d]']">
                <!-- ===== Header Start (Only for Admin) ===== -->
                <MainHeader v-if="isAdmin"
                    :sidebarOpen="sidebarOpen" 
                    :isCollapsed="isCollapsed"
                    @toggleSidebar="toggleSidebar"
                    @toggleCollapse="toggleCollapse"
                />
            
                <!-- ===== Main Content Start ===== -->
                <main>
                    <div :class="['main-content-wrapper', isAdmin ? '' : 'p-0']">
                        <!-- Breadcrumbs removed as requested -->

                        <!-- Router View -->
                        <div class="router-view-container">
                            <Suspense>
                                <router-view />
                            </Suspense>
                        </div>
                    </div>
                </main>
                <!-- ===== Main Content End ===== -->
            </div>
            <!-- ===== Content Area End ===== -->
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useLayout } from '../composables/layout';
import MainSidebar from './MainSidebar.vue';
import MainHeader from './MainHeader.vue';
import LandingNavbar from './LandingNavbar.vue';

const props = defineProps({
    menuItems: {
        type: Array,
        default: null
    }
});

const route = useRoute();
const { setDefaultMode, isDarkTheme } = useLayout();
const sidebarOpen = ref(true);
const isCollapsed = ref(false);

// Inicializar modo oscuro al montar el layout
onMounted(() => {
    setDefaultMode();
    // Asegurar que el modo oscuro se aplique después de que Pinia esté listo
    setTimeout(() => {
        setDefaultMode();
    }, 100);
});

// Watcher para reaccionar a cambios en el tema
watch(isDarkTheme, (newValue) => {
    // Forzar actualización cuando cambie el tema
    if (typeof document !== 'undefined') {
        const html = document.documentElement;
        if (newValue) {
            html.classList.add('app-dark', 'dark');
            document.body.classList.add('dark');
        } else {
            html.classList.remove('app-dark', 'dark');
            document.body.classList.remove('dark');
        }
    }
}, { immediate: true });

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const toggleCollapse = () => {
    isCollapsed.value = !isCollapsed.value;
};

const isAdmin = computed(() => route.path.startsWith('/admin'));

const pageTitle = computed(() => {
    return route.meta?.pageTitle || route.meta?.breadCrumb || 'Dashboard';
});

const breadcrumbs = computed(() => {
    const currentPath = route.path;
    const isApp = currentPath.startsWith('/app');
    const rootPath = isApp ? '/app' : '/admin';
    
    if (!currentPath || currentPath === rootPath) {
        return [];
    }
    
    let pathArray = currentPath.split("/").filter(Boolean);
    
    // Remover el prefijo (admin o app) del inicio si existe
    if (pathArray[0] === 'admin' || pathArray[0] === 'app') {
        pathArray.shift();
    }
    
    return pathArray.map((path, idx) => {
        const fullPath = rootPath + '/' + pathArray.slice(0, idx + 1).join('/');
        const matchedRoute = route.matched.find(r => r.path === fullPath || r.path === fullPath.replace(/\/$/, ''));
        return {
            label: matchedRoute?.meta?.breadCrumb || matchedRoute?.meta?.pageTitle || path.charAt(0).toUpperCase() + path.slice(1),
            route: fullPath
        };
    });
});
</script>

