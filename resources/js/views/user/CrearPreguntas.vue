<template>
    <div class="min-h-screen bg-gradient-to-tr from-[#9333EA] via-[#7C3AED] to-[#6D28D9] p-4 md:p-12">
        <div class="max-w-4xl mx-auto">
            <!-- ===== CABECERA ===== -->
            <div class="mb-10 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-white shadow-xl mb-6 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                    <i class="pi pi-question-circle text-purple-600 text-4xl"></i>
                </div>
                <h1 class="text-3xl md:text-5xl font-black tracking-widest uppercase text-white drop-shadow-lg">
                    Proponer una Pregunta
                </h1>
                <p class="text-purple-100 mt-3 font-medium max-w-lg mx-auto">
                    ¿Tienes una idea genial? Tu contribución hace que el juego sea mejor para todos.
                </p>
            </div>

            <!-- ===== ALERTAS ===== -->
            <Transition name="slide-fade">
                <div v-if="enviado" class="mb-8 bg-green-500 text-white p-5 rounded-2xl flex items-center gap-4 shadow-lg border-b-4 border-green-700">
                    <i class="pi pi-check-circle text-2xl"></i>
                    <span class="font-bold">¡Pregunta enviada correctamente! Está en fase de revisión.</span>
                    <button @click="enviado = false" class="ml-auto hover:scale-110 transition-transform">
                        <i class="pi pi-times"></i>
                    </button>
                </div>
            </Transition>

            <Transition name="slide-fade">
                <div v-if="errorGeneral" class="mb-8 bg-red-500 text-white p-5 rounded-2xl flex items-center gap-4 shadow-lg border-b-4 border-red-700">
                    <i class="pi pi-exclamation-circle text-2xl"></i>
                    <span class="font-bold">{{ errorGeneral }}</span>
                    <button @click="errorGeneral = ''" class="ml-auto hover:scale-110 transition-transform">
                        <i class="pi pi-times"></i>
                    </button>
                </div>
            </Transition>

            <!-- ===== FORMULARIO ===== -->
            <div class="bg-white/10 backdrop-blur-md rounded-[2.5rem] shadow-2xl border-4 border-white/20 p-8 md:p-12">
                <form @submit.prevent="enviarPregunta" class="space-y-8 text-white">
                    
                    <!-- Enunciado -->
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-sm font-black uppercase tracking-widest text-purple-200">
                            <i class="pi pi-align-left"></i> Enunciado de la pregunta
                        </label>
                        <div class="relative">
                            <textarea
                                v-model="form.enunciado"
                                class="w-full bg-white/10 border-2 border-white/10 rounded-2xl p-5 text-white placeholder:text-purple-300 focus:border-white focus:bg-white/20 outline-none transition-all resize-none font-medium"
                                placeholder="¿Cuál es la capital de...?"
                                rows="3"
                                maxlength="500"
                            ></textarea>
                            <div class="absolute bottom-4 right-4 flex items-center gap-2 text-[10px] font-bold text-purple-200">
                                {{ form.enunciado.length }} <span class="text-white/30">/</span> 500
                            </div>
                        </div>
                        <p v-if="errores.enunciado" class="text-red-300 text-xs font-bold pl-2">{{ errores.enunciado }}</p>
                    </div>

                    <!-- Categoría -->
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-sm font-black uppercase tracking-widest text-purple-200">
                            <i class="pi pi-tag"></i> Categoría
                        </label>
                        <select v-model="form.categories_id" 
                                class="w-full bg-white/10 border-2 border-white/10 rounded-2xl p-4 text-white focus:border-white focus:bg-white/20 outline-none transition-all appearance-none cursor-pointer">
                            <option value="" disabled class="bg-purple-800 text-white">Selecciona una categoría...</option>
                            <option v-for="cat in categorias" :key="cat.id" :value="cat.id" class="bg-purple-800 text-white">
                                {{ cat.name }}
                            </option>
                        </select>
                        <p v-if="errores.categoria" class="text-red-300 text-xs font-bold pl-2">{{ errores.categoria }}</p>
                    </div>

                    <!-- Tipo -->
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-sm font-black uppercase tracking-widest text-purple-200">
                            <i class="pi pi-list"></i> Tipo de pregunta
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <button
                                type="button"
                                v-for="tipo in [{id: 'multiple', label: 'Opción Múltiple', icon: 'pi pi-th-large'}, {id: 'boolean', label: 'V / F', icon: 'pi pi-check-square'}]"
                                :key="tipo.id"
                                @click="cambiarTipo(tipo.id)"
                                :class="form.tipo === tipo.id 
                                    ? 'bg-white text-purple-700 border-white shadow-lg scale-[1.02]' 
                                    : 'bg-white/5 text-white/70 border-white/10 hover:border-white/30 hover:bg-white/10'"
                                class="flex items-center justify-center gap-3 p-4 rounded-2xl border-2 font-black uppercase tracking-wider transition-all duration-200"
                            >
                                <i :class="tipo.icon"></i>
                                {{ tipo.label }}
                            </button>
                        </div>
                    </div>

                    <!-- Opciones -->
                    <div class="space-y-4">
                        <label class="flex items-center justify-between text-sm font-black uppercase tracking-widest text-purple-200">
                            <div class="flex items-center gap-2">
                                <i class="pi pi-check-circle"></i> Respuestas
                            </div>
                            <span class="text-[10px] text-white/50 normal-case">(Marca la correcta)</span>
                        </label>

                        <div class="space-y-3">
                            <div
                                v-for="(opcion, index) in form.opciones"
                                :key="index"
                                class="group flex items-center gap-4 p-4 rounded-3xl border-2 transition-all duration-200"
                                :class="correctaIndex === index 
                                    ? 'bg-green-500/20 border-green-400 shadow-md' 
                                    : 'bg-white/5 border-white/5 hover:border-white/20 hover:bg-white/10'"
                            >
                                <!-- Radio -->
                                <button
                                    type="button"
                                    @click="marcarCorrecta(index)"
                                    class="w-8 h-8 rounded-full flex items-center justify-center transition-all"
                                    :class="correctaIndex === index 
                                        ? 'bg-green-500 text-white scale-110' 
                                        : 'bg-white/20 text-white/40 group-hover:scale-105'"
                                >
                                    <i class="pi" :class="correctaIndex === index ? 'pi-check' : 'pi-circle'"></i>
                                </button>

                                <!-- Input -->
                                <input
                                    v-model="opcion.texto"
                                    class="flex-grow bg-transparent outline-none font-bold text-white placeholder:text-white/20"
                                    :placeholder="form.tipo === 'boolean'
                                        ? (index === 0 ? 'Verdadero' : 'Falso')
                                        : `Escribe la opción ${index + 1}...`"
                                    :readonly="form.tipo === 'boolean'"
                                />

                                <span v-if="correctaIndex === index" class="text-[10px] font-black uppercase text-green-300 bg-green-500/30 px-3 py-1 rounded-full border border-green-500/40">
                                    Correcta
                                </span>
                            </div>
                        </div>
                        <p v-if="errores.opciones" class="text-red-300 text-xs font-bold pl-2">{{ errores.opciones }}</p>
                    </div>

                    <!-- ACCIONES -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-white/10">
                        <button type="button" @click="resetFormulario"
                                class="flex-1 bg-white/10 hover:bg-white/20 text-white font-black uppercase tracking-widest py-4 rounded-full transition-colors">
                            Limpiar Todo
                        </button>
                        <button type="submit" :disabled="enviando"
                                class="flex-[2] bg-gradient-to-b from-yellow-300 to-yellow-500 hover:from-yellow-200 hover:to-yellow-400 text-yellow-900 font-black text-xl py-4 rounded-full border-b-8 border-yellow-700 active:border-b-0 active:translate-y-2 transition-all uppercase tracking-widest flex items-center justify-center gap-3">
                            <i v-if="enviando" class="pi pi-spinner pi-spin"></i>
                            <span>{{ enviando ? 'Enviando...' : 'Enviar Propuesta' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- INFO FOOTER -->
            <div class="mt-8 bg-white/5 rounded-3xl p-6 flex items-start gap-4">
                <i class="pi pi-info-circle text-purple-200 text-xl mt-1"></i>
                <p class="text-sm text-purple-100 font-medium leading-relaxed">
                    Apreciamos tu contribución. Un administrador revisará tu pregunta para asegurar la calidad del contenido antes de que sea visible para todos.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// ──────────────── Estado ────────────────
const categorias   = ref([]);
const enviando     = ref(false);
const enviado      = ref(false);
const errorGeneral = ref('');
const correctaIndex = ref(0);

const opcionesMultipleVacias = () => [
    { texto: '', es_correcta: false, orden: 1 },
    { texto: '', es_correcta: false, orden: 2 },
    { texto: '', es_correcta: false, orden: 3 },
    { texto: '', es_correcta: false, orden: 4 },
];

const opcionesBooleanVacias = () => [
    { texto: 'Verdadero', es_correcta: false, orden: 1 },
    { texto: 'Falso',     es_correcta: false, orden: 2 },
];

const form = ref({
    enunciado:     '',
    categories_id: '',
    tipo:          'multiple',
    opciones:      opcionesMultipleVacias(),
});

const errores = ref({
    enunciado: '',
    categoria: '',
    opciones:  '',
});

// ──────────────── Ciclo de vida ────────────────
onMounted(async () => {
    try {
        const res = await axios.get('/api/category-list');
        categorias.value = res.data?.data ?? res.data ?? [];
    } catch {
        errorGeneral.value = 'No se pudieron cargar las categorías. Recarga la página.';
    }
});

// ──────────────── Métodos ────────────────
function cambiarTipo(nuevoTipo) {
    if (form.value.tipo === nuevoTipo) return;
    form.value.tipo    = nuevoTipo;
    correctaIndex.value = 0;
    form.value.opciones = nuevoTipo === 'boolean'
        ? opcionesBooleanVacias()
        : opcionesMultipleVacias();
}

function marcarCorrecta(index) {
    correctaIndex.value = index;
}

function resetFormulario() {
    form.value = {
        enunciado:     '',
        categories_id: '',
        tipo:          'multiple',
        opciones:      opcionesMultipleVacias(),
    };
    correctaIndex.value  = 0;
    errores.value        = { enunciado: '', categoria: '', opciones: '' };
    enviado.value        = false;
    errorGeneral.value   = '';
}

function validar() {
    let valido = true;
    errores.value = { enunciado: '', categoria: '', opciones: '' };

    if (!form.value.enunciado.trim()) {
        errores.value.enunciado = 'El enunciado es obligatorio.';
        valido = false;
    }

    if (!form.value.categories_id) {
        errores.value.categoria = 'Selecciona una categoría.';
        valido = false;
    }

    const conTexto = form.value.opciones.filter(o => o.texto.trim().length > 0);
    const minOpciones = form.value.tipo === 'boolean' ? 2 : 4;

    if (conTexto.length < minOpciones) {
        errores.value.opciones = `Rellena las ${minOpciones} opciones de respuesta.`;
        valido = false;
    }

    return valido;
}

async function enviarPregunta() {
    if (!validar()) return;

    enviando.value  = true;
    errorGeneral.value = '';

    const opcionesPayload = form.value.opciones.map((op, i) => ({
        texto:       op.texto.trim(),
        es_correcta: i === correctaIndex.value,
        orden:       i + 1,
    }));

    const payload = {
        enunciado:     form.value.enunciado.trim(),
        categories_id: form.value.categories_id,
        tipo:          form.value.tipo,
        activa:        false,
        opciones:      opcionesPayload,
    };

    try {
        await axios.post('/api/questions', payload);
        enviado.value = true;
        resetFormulario();
        enviado.value = true;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } catch (error) {
        const msg = error?.response?.data?.message ?? null;
        const errs = error?.response?.data?.errors ?? null;

        if (errs) {
            Object.entries(errs).forEach(([key, messages]) => {
                if (key === 'enunciado') errores.value.enunciado = messages[0];
                if (key === 'categories_id') errores.value.categoria = messages[0];
            });
            errorGeneral.value = 'Revisa los campos marcados en rojo.';
        } else {
            errorGeneral.value = msg || 'Error al enviar la pregunta. Inténtalo de nuevo.';
        }
    } finally {
        enviando.value = false;
    }
}
</script>

<style scoped>
.slide-fade-enter-active { transition: all 0.3s ease-out; }
.slide-fade-leave-active { transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1); }
.slide-fade-enter-from, .slide-fade-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}
</style>
