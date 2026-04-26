<template>
    <MainLayout :menuItems="items" />
</template>

<script setup>
import { ref } from 'vue';
import MainLayout from './MainLayout.vue';

const items = ref([
   
    {
        label: 'Contenido',
        items: [
            {
                label: 'Crear Pregunta',
                icon: 'pi pi-plus-circle',
                route: '/app/crear-pregunta'
            },
        ]
    },
    {
        label: 'Cuenta',
        items: [
            {
                label: 'Perfil',
                icon: 'pi pi-user',
                route: '/app/profile'
            },

            {
                label: 'Mis Logros',
                icon: 'pi pi-trophy',
                route: '/app/mis-logros'
            }
        ]
    }
]);
</script>
