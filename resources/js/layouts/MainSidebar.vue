<template>
    <aside 
        :class="[
            props.sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            props.isCollapsed ? 'w-20' : 'w-72',
            'fixed left-0 top-0 z-50 flex h-screen flex-col overflow-hidden transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)] lg:static lg:translate-x-0',
            'bg-[#1a103d]/95 backdrop-blur-xl border-r border-white/10 shadow-[20px_0_50px_rgba(0,0,0,0.3)]'
        ]"
    >
        <!-- Sidebar Header (Logo Section) -->
        <div class="relative flex items-center justify-center p-6 mb-4 overflow-hidden transition-all duration-500"
             :class="props.isCollapsed ? 'h-24' : 'h-32'">
            
            <!-- Background Glow -->
            <div class="absolute -top-10 -left-10 w-32 h-32 bg-purple-600/20 blur-3xl rounded-full"></div>
            
            <router-link to="/" class="relative z-10 flex items-center gap-3 overflow-hidden transition-all duration-500 w-full justify-center">
                <img src="/images/Logo_ElSabeloTodo.svg" alt="Logo" 
                     class="transition-all duration-500 drop-shadow-[0_0_15px_rgba(168,85,247,0.4)]" 
                     :class="props.isCollapsed ? 'h-10 w-10 rotate-0 scale-110' : 'h-20 w-auto hover:scale-105 hover:-rotate-2'"/>
            </router-link>
        </div>

        <!-- Sidebar Menu -->
        <div class="flex flex-1 flex-col overflow-y-auto overflow-x-hidden p-4 gap-2 scrollbar-hide custom-scrollbar">
            <template v-for="(item, index) in menuModel" :key="index">
                
                <!-- Group Label -->
                <div v-if="item.label && item.items" 
                     class="px-4 mt-6 mb-3 text-[10px] font-black text-purple-300/40 uppercase tracking-[0.25em] transition-all duration-300"
                     :class="props.isCollapsed ? 'opacity-0 h-0 my-0 overflow-hidden' : 'opacity-100'">
                    {{ item.label }}
                </div>

                <template v-if="item.items">
                     <!-- Submenu Items -->
                     <template v-for="(subItem, subIndex) in item.items" :key="subItem.label">
                        <router-link :to="subItem.route" v-if="subItem.route" custom v-slot="{ href, navigate, isActive }">
                            <a :href="href" @click="navigate" 
                               v-tooltip.right="props.isCollapsed ? subItem.label : ''"
                               class="group relative flex items-center gap-4 px-4 py-3.5 rounded-[1.25rem] transition-all duration-300 overflow-hidden"
                               :class="[
                                   isActive 
                                   ? 'bg-gradient-to-r from-purple-600 to-blue-600 shadow-lg shadow-purple-900/50 scale-[1.02]' 
                                   : 'text-purple-100/60 hover:text-white hover:bg-white/5'
                               ]"
                            >
                                <!-- Active Indicator Bar -->
                                <div v-if="isActive" class="absolute left-0 top-0 w-1.5 h-full bg-yellow-400"></div>

                                <!-- Icon -->
                                <div class="relative shrink-0 flex items-center justify-center w-8 h-8 rounded-xl transition-all duration-300"
                                     :class="isActive ? 'bg-white/20' : 'group-hover:scale-110'">
                                    <i class="text-xl transition-all duration-300" 
                                       :class="[subItem.icon, isActive ? 'text-white' : 'text-purple-400 group-hover:text-purple-300']"></i>
                                </div>
                                
                                <!-- Label -->
                                <span class="text-sm font-bold tracking-wide transition-all duration-500 whitespace-nowrap"
                                      :class="[props.isCollapsed ? 'opacity-0 translate-x-4' : 'opacity-100 translate-x-0']">
                                    {{ subItem.label }}
                                </span>

                                <!-- Active Glow -->
                                <div v-if="isActive" class="absolute -right-4 -bottom-4 w-12 h-12 bg-white/10 blur-2xl rounded-full"></div>
                            </a>
                        </router-link>
                     </template>
                </template>

                <!-- Single Item -->
                <template v-else-if="item.route">
                     <router-link :to="item.route" custom v-slot="{ href, navigate, isActive }">
                        <a :href="href" @click="navigate" 
                           v-tooltip.right="props.isCollapsed ? item.label : ''"
                           class="group relative flex items-center gap-4 px-4 py-3.5 rounded-[1.25rem] transition-all duration-300 overflow-hidden"
                           :class="[
                               isActive 
                               ? 'bg-gradient-to-r from-purple-600 to-blue-600 shadow-lg shadow-purple-900/50 scale-[1.02]' 
                               : 'text-purple-100/60 hover:text-white hover:bg-white/5'
                           ]"
                        >
                            <div v-if="isActive" class="absolute left-0 top-0 w-1.5 h-full bg-yellow-400"></div>

                            <div class="relative shrink-0 flex items-center justify-center w-8 h-8 rounded-xl transition-all duration-300"
                                 :class="isActive ? 'bg-white/20' : 'group-hover:scale-110'">
                                <i class="text-xl transition-all duration-300" 
                                   :class="[item.icon, isActive ? 'text-white' : 'text-purple-400 group-hover:text-purple-300']"></i>
                            </div>

                            <span class="text-sm font-bold tracking-wide transition-all duration-500 whitespace-nowrap"
                                  :class="[props.isCollapsed ? 'opacity-0 translate-x-4' : 'opacity-100 translate-x-0']">
                                {{ item.label }}
                            </span>
                        </a>
                     </router-link>
                </template>
            </template>
        </div>

        <!-- Footer Button (Back Home) -->
        <div class="mt-auto p-4 border-t border-white/5">
            <router-link to="/" 
                         class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all duration-300 text-purple-300/50 hover:text-white hover:bg-red-500/10 group">
                <div class="w-8 h-8 flex items-center justify-center rounded-xl bg-white/5 group-hover:bg-red-500/20 transition-colors">
                    <i class="pi pi-sign-out text-lg group-hover:scale-110 transition-transform"></i>
                </div>
                <span class="text-xs font-black uppercase tracking-widest whitespace-nowrap transition-all duration-500" 
                      :class="[props.isCollapsed ? 'opacity-0 translate-x-4' : 'opacity-100 translate-x-0']">
                    Cerrar Sesión
                </span>
            </router-link>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div v-if="props.sidebarOpen" @click="emit('toggleSidebar')" class="lg:hidden fixed inset-0 z-40 bg-black/60 backdrop-blur-sm transition-opacity duration-300"></div>
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

.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}
</style>