<template>
    <div class="bg-[#6A3EBE] min-h-[calc(100vh-100px)] text-white font-bold p-8 flex flex-col justify-center">
        <div class="text-center mb-12">
            <h1 class="text-5xl tracking-wider">DEMUESTRA LO QUE SABES</h1>
            <p class="text-purple-300 text-xl mt-2">Pon a prueba tus conocimientos</p>
        </div>

        <div class="flex flex-col lg:flex-row justify-center items-center gap-8">
            <!-- Card 1: 1 VS 1 -->
            <div
                class="bg-white/90 text-gray-800 rounded-3xl p-8 w-full max-w-md text-center border-4 border-purple-400 shadow-2xl">
                <h2 class="text-4xl mb-4 text-purple-800">1 VS 1</h2>
                <div class="flex justify-center items-center gap-4 my-6">
                    <!-- Reemplaza este 'src' con tu propia imagen -->
                    <img src="m" alt="Avatar 1" class="w-24 h-24 rounded-full border-4 border-purple-300">
                    <span class="text-4xl text-purple-600">VS</span>
                    <!-- Reemplaza este 'src' con tu propia imagen -->
                    <img src=" " alt="Avatar 2" class="w-24 h-24 rounded-full border-4 border-purple-300">
                </div>
                <Button label="DUELO"
                    class="w-full !bg-yellow-500 !hover:bg-yellow-600 !text-gray-900 !text-2xl !py-4 !rounded-2xl !border-b-8 !border-yellow-700"
                    @click="startGame('duel')" />
            </div>

            <!-- Card 2: Modo Individual -->
            <div
                class="bg-white/90 text-gray-800 rounded-3xl p-8 w-full max-w-md text-center border-4 border-purple-400 shadow-2xl relative">
                <!-- Reemplaza este 'src' con tu propia imagen -->
                <img src=" " alt=-"Gem" class="absolute -top-8 -right-8 w-24 h-24 transform rotate-12" />
                <h2 class="text-4xl mb-4 text-purple-800">MODO INDIVIDUAL</h2>
                <div class="flex justify-center items-center my-6">
                    <!-- Reemplaza este 'src' con tu propia imagen -->
                    <img src="/images/avatars/Avatar_solitario.webp" alt="Avatar Individual">
                </div>
                <Button label="ENTRENAR"
                    class="w-full !bg-yellow-500 !hover:bg-yellow-600 !text-gray-900 !text-2xl !py-4 !rounded-2xl !border-b-8 !border-yellow-700"
                    @click="startGame('solo')" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { authStore } from "@/store/auth";
import { useRouter } from "vue-router";

const router = useRouter();

const startGame = (mode) => {
    if (mode === 'solo') {
        router.push('/game/individual');
    } else if (mode === 'duel') {
        //Logica del modo 1vs1
    }

}


</script>
