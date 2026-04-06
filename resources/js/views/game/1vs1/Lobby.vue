<template>
    <div class="flex justify-center items-center min-h-screen bg-gray-900 text-white p-4">
        <Card class="w-full max-w-md bg-gray-800 shadow-lg rounded-lg">
            <template #title>
                <div class="text-center text-2xl font-bold text-yellow-400">
                    Lobby 1vs1
                </div>
            </template>
            <template #content>
                <div class="flex flex-col gap-8 p-4">
                    <!-- Sección para Crear Partida -->
                    <div class="text-center">
                        <h2 class="text-xl mb-4">Crear Nueva Partida</h2>
                        <Button @click="createRoom" label="Crear Partida" icon="pi pi-plus" class="p-button-lg p-button-success" :loading="isCreating"/>
                    </div>

                    <!-- Mostrar el código de la sala una vez creada -->
                    <div v-if="roomCode" class="mt-4">
                        <InviteLink :code="roomCode" @start-game="startGame" />
                    </div>

                    <Divider align="center" type="dashed">
                        <b>O</b>
                    </Divider>

                    <!-- Sección para Unirse a Partida -->
                    <div class="text-center">
                        <h2 class="text-xl mb-4">Unirse a una Partida</h2>
                        <div class="p-inputgroup">
                            <InputText v-model="joinCode" placeholder="Introduce el código de la sala" @keyup.enter="joinRoom" />
                            <Button @click="joinRoom" icon="pi pi-sign-in" class="p-button-info" :loading="isJoining" />
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import InviteLink from '@/components/game/InviteLink.vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Divider from 'primevue/divider';

const router = useRouter();
const roomCode = ref('');
const joinCode = ref('');
const isCreating = ref(false);
const isJoining = ref(false);

//Función para llamar a la API y crear una sala
const createRoom = async () => {
    isCreating.value = true;
    try {
        const response = await axios.post('/api/rooms');
        roomCode.value = response.data.code;
    } catch (error) {
        console.error("Error al crear la sala:", error);
        alert("No se pudo crear la sala.");
    } finally {
        isCreating.value = false;
    }
};

//Función para unirse a una sala existente
const joinRoom = async () => {
    if (!joinCode.value.trim()) {
        alert("Por favor, introduce un código de sala.");
        return;
    }
    isJoining.value = true;
    try {
        await axios.post('/api/rooms/join', { code: joinCode.value });
        //Si la unión es exitosa, redirigimos al jugador a la partida
        router.push({ name: 'game.1vs1.play', params: { code: joinCode.value } });
    } catch (error) {
        console.error("Error al unirse a la sala:", error);
        alert(error.response?.data?.message || "No se pudo unir a la sala. Verifica el código.");
    } finally {
        isJoining.value = false;
    }
};

//Función para que el creador de la sala comience la partida
const startGame = (code) => {
    router.push({ name: 'game.1vs1.play', params: { code: code } });
};
</script>
