<template>
    <!-- Contenedor Principal con estilo condicional -->
    <aside 
        :class="[
            props.sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            props.isCollapsed ? 'w-[70px]' : 'w-64',
            'fixed left-0 top-0 z-50 flex h-screen flex-col overflow-hidden transition-all duration-300 ease-in-out lg:static lg:translate-x-0 shadow-lg lg:shadow-none',
            isAdmin 
                ? 'bg-white border-r border-gray-200' 
                : 'bg-[#1a103d]/95 backdrop-blur-xl border-r border-white/10'
        ]"
    >
        <!-- Sidebar Header (Logo Section) -->
        <div class="flex items-center justify-center p-4 shrink-0 transition-all duration-300"
             :class="[
                props.isCollapsed ? 'h-16' : 'h-24',
                isAdmin ? 'border-b border-gray-100' : 'mb-4 relative overflow-hidden'
             ]">
            
            <!-- Glow effect only for User (not for Admin) -->
            <div v-if="!isAdmin" class="absolute -top-10 -left-10 w-32 h-32 bg-purple-600/20 blur-3xl rounded-full"></div>
            
            <router-link to="/" class="relative z-10 flex items-center gap-3 overflow-hidden whitespace-nowrap transition-all duration-300 w-full justify-center">
                <img src="/images/Logo_ElSabeloTodo.svg" alt="Logo" 
                     class="transition-all duration-300 object-contain" 
                     :class="[
                        props.isCollapsed ? 'h-8 w-8' : 'h-16 w-auto max-w-full',
                        !isAdmin ? 'drop-shadow-[0_0_15px_rgba(168,85,247,0.4)] hover:scale-105' : ''
                     ]"/>
            </router-link>
        </div>

        <!-- Sidebar Menu -->
        <div class="flex flex-1 flex-col overflow-y-auto overflow-x-hidden p-3 gap-1 scrollbar-hide">
            <template v-for="(item, index) in menuModel" :key="index">
                
                <!-- Group Label -->
                <div v-if="item.label && item.items" 
                     class="px-3 mt-4 mb-2 text-xs font-semibold uppercase tracking-wider whitespace-nowrap transition-all duration-300"
                     :class="[
                        props.isCollapsed ? 'hidden' : 'opacity-100',
                        isAdmin ? 'text-gray-400' : 'text-purple-300/40 text-[10px] tracking-[0.25em]'
                     ]">
                    {{ item.label }}
                </div>

                <template v-if="item.items">
                     <!-- Submenu Items -->
                     <template v-for="(subItem, subIndex) in item.items" :key="subItem.label">
                        <router-link :to="subItem.route" v-if="subItem.route" custom v-slot="{ href, navigate, isActive }">
                            <a :href="href" @click="navigate" 
                               v-tooltip.right="props.isCollapsed ? subItem.label : ''"
                               class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200"
                               :class="[
                                   isAdmin 
                                    ? (isActive ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900')
                                    : (isActive 
                                        ? 'bg-gradient-to-r from-purple-600 to-blue-600 shadow-lg shadow-purple-900/50 text-white rounded-xl' 
                                        : 'text-purple-100/60 hover:text-white hover:bg-white/5')
                               ]"
                            >
                                <i class="text-lg shrink-0 transition-colors" 
                                   :class="[
                                        subItem.icon, 
                                        isAdmin 
                                            ? (isActive ? 'text-blue-600' : 'text-gray-500')
                                            : (isActive ? 'text-white' : 'text-purple-400 group-hover:text-purple-300')
                                   ]"></i>
                                
                                <span class="whitespace-nowrap transition-all duration-300 origin-left"
                                      :class="[props.isCollapsed ? 'hidden' : 'w-auto opacity-100']">
                                    {{ subItem.label }}
                                </span>

                                <!-- Indicator Bar ONLY for USER layout -->
                                <div v-if="!isAdmin && isActive" class="absolute left-0 top-0 w-1 h-full bg-yellow-400 rounded-r-md"></div>
                                <span v-if="isAdmin && isActive" class="absolute right-2 w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            </a>
                        </router-link>
                     </template>
                </template>

                 <!-- Single Item -->
                 <template v-else-if="item.route">
                     <router-link :to="item.route" custom v-slot="{ href, navigate, isActive }">
                        <a :href="href" @click="navigate" 
                           v-tooltip.right="props.isCollapsed ? item.label : ''"
                           class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200"
                           :class="[
                               isAdmin 
                                ? (isActive ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900')
                                : (isActive 
                                    ? 'bg-gradient-to-r from-purple-600 to-blue-600 shadow-lg shadow-purple-900/50 text-white rounded-xl' 
                                    : 'text-purple-100/60 hover:text-white hover:bg-white/5')
                           ]"
                        >
                            <i class="text-lg shrink-0 transition-colors" 
                               :class="[
                                    item.icon, 
                                    isAdmin 
                                        ? (isActive ? 'text-blue-600' : 'text-gray-500')
                                        : (isActive ? 'text-white' : 'text-purple-400 group-hover:text-purple-300')
                               ]"></i>
                            <span class="whitespace-nowrap transition-all duration-300 origin-left"
                                  :class="[props.isCollapsed ? 'hidden' : 'w-auto opacity-100']">
                                {{ item.label }}
                            </span>
                        </a>
                     </router-link>
                </template>
            </template>
        </div>

        <!-- Sidebar Footer -->
        <div class="mt-auto p-3 transition-all duration-300" :class="isAdmin ? 'border-t border-gray-100' : 'border-t border-white/5'">
            <router-link to="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200"
                         :class="isAdmin ? 'text-gray-600 hover:bg-gray-50' : 'text-purple-300/50 hover:text-white hover:bg-white/5 group'">
                <i class="pi pi-home text-lg shrink-0" :class="!isAdmin ? 'group-hover:scale-110' : ''"></i>
                <span class="whitespace-nowrap transition-all duration-300 origin-left" :class="[props.isCollapsed ? 'hidden' : 'w-auto opacity-100']">
                    {{ isAdmin ? 'Volver a Home' : 'Cerrar Sesión' }}
                </span>
            </router-link>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div v-if="props.sidebarOpen" @click="emit('toggleSidebar')" class="lg:hidden fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAbility } from '@casl/vue';

const route = useRoute();
const router = useRouter();
const { can } = useAbility();

const props = defineProps({
    sidebarOpen: {
        type: Boolean,
        default: true
    },
    isCollapsed: {
        type: Boolean,
        default: false
    },
    menuItems: {
        type: Array,
        default: null
    }
});

const emit = defineEmits(['toggleSidebar', 'toggleCollapse']);

// Propiedad computada para saber si estamos en administración
const isAdmin = computed(() => route.path.startsWith('/admin'));

const menuModel = computed(() => {
    if (props.menuItems) {
        return props.menuItems;
    }

    const items = [
        {
            icon: 'pi pi-home',
            label: 'Principal',
            items: [
                { label: 'Dashboard', icon: 'pi pi-compass', route: '/admin', permission: 'all' }
            ]
        },
        {
            label: 'Gestión',
            items: [
                { label: 'Usuarios', icon: 'pi pi-users', route: '/admin/users', permission: 'user-list' },
                { label: 'Roles', icon: 'pi pi-shield', route: '/admin/roles', permission: 'role-list' },
                { label: 'Permisos', icon: 'pi pi-key', route: '/admin/permissions', permission: 'permission-list' }
            ]
        },
        {
            label: 'Contenido',
            items: [
                { label: 'Categorías', icon: 'pi pi-tags', route: '/admin/categories', permission: 'category-list' },
                { label: 'Preguntas', icon: 'pi pi-question-circle', route: '/admin/questions', permission: 'all' }
            ]
        }
    ];

    return items.filter(item => {
        if (item.permission && item.permission !== 'all') {
            if (!can(item.permission)) return false;
        }
        if (item.items) {
            item.items = item.items.filter(child => {
                return !child.permission || child.permission === 'all' || can(child.permission);
            });
            return item.items.length > 0;
        }
        return true;
    });
});
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>