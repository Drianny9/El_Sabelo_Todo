<template>
    <div class="p-4 bg-gray-700 rounded-lg text-center">
        <h2 class="text-lg font-semibold">¡Partida Creada!</h2>
        <p class="my-2">Comparte este código con tu amigo:</p>
        <div class="p-3 bg-gray-900 rounded-md my-3">
            <span class="text-2xl font-bold text-yellow-400 tracking-widest">{{ code }}</span>
        </div>
        <div class="flex gap-2 justify-center">
            <Button @click="copyToClipboard" icon="pi pi-copy" :label="copyLabel" />
            <Button @click="$emit('startGame', code)" label="Empezar Partida" icon="pi pi-play" class="p-button-success" />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Button from 'primevue/button';

const props = defineProps({
    code: {
        type: String,
        required: true,
    },
});

defineEmits(['startGame']);

const copyLabel = ref('Copiar');

//Función para copiar el código al portapapeles
const copyToClipboard = () => {
    navigator.clipboard.writeText(props.code).then(() => {
        copyLabel.value = '¡Copiado!';
        setTimeout(() => {
            copyLabel.value = 'Copiar';
        }, 2000); //El mensaje de "Copiado" desaparecerá después de 2 segundos
    }).catch(err => {
        console.error('Error al copiar el código: ', err);
        alert('No se pudo copiar el código.');
    });
};
</script>
