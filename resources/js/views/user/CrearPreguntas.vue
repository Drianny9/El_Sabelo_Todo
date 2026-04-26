<template>
    <div class="crear-preguntas-wrapper">
        <!-- CABECERA -->
        <div class="cp-header">
            <div class="cp-header__icon">
                <i class="pi pi-question-circle"></i>
            </div>
            <div>
                <h1 class="cp-header__title">Proponer una Pregunta</h1>
                <p class="cp-header__sub">Tu pregunta será revisada por un administrador antes de publicarse.</p>
            </div>
        </div>

        <!-- ALERTA ÉXITO -->
        <Transition name="fade">
            <div v-if="enviado" class="cp-alert cp-alert--success">
                <i class="pi pi-check-circle"></i>
                <span>¡Pregunta enviada correctamente! Está pendiente de revisión.</span>
                <button class="cp-alert__close" @click="enviado = false">
                    <i class="pi pi-times"></i>
                </button>
            </div>
        </Transition>

        <!-- ALERTA ERROR -->
        <Transition name="fade">
            <div v-if="errorGeneral" class="cp-alert cp-alert--error">
                <i class="pi pi-exclamation-circle"></i>
                <span>{{ errorGeneral }}</span>
                <button class="cp-alert__close" @click="errorGeneral = ''">
                    <i class="pi pi-times"></i>
                </button>
            </div>
        </Transition>

        <!-- FORMULARIO -->
        <div class="cp-card">
            <form @submit.prevent="enviarPregunta" novalidate>

                <!-- Enunciado -->
                <div class="cp-field" :class="{ 'cp-field--error': errores.enunciado }">
                    <label class="cp-label">
                        <i class="pi pi-align-left"></i> Enunciado de la pregunta
                    </label>
                    <textarea
                        v-model="form.enunciado"
                        class="cp-textarea"
                        placeholder="Escribe aquí la pregunta..."
                        rows="3"
                        maxlength="500"
                    ></textarea>
                    <span v-if="errores.enunciado" class="cp-error-msg">{{ errores.enunciado }}</span>
                    <span class="cp-char-count">{{ form.enunciado.length }} / 500</span>
                </div>

                <!-- Categoría -->
                <div class="cp-field" :class="{ 'cp-field--error': errores.categoria }">
                    <label class="cp-label">
                        <i class="pi pi-tag"></i> Categoría
                    </label>
                    <select v-model="form.categories_id" class="cp-select">
                        <option value="" disabled>Selecciona una categoría...</option>
                        <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
                            {{ cat.name }}
                        </option>
                    </select>
                    <span v-if="errores.categoria" class="cp-error-msg">{{ errores.categoria }}</span>
                </div>

                <!-- Tipo -->
                <div class="cp-field">
                    <label class="cp-label">
                        <i class="pi pi-list"></i> Tipo de pregunta
                    </label>
                    <div class="cp-tipo-btns">
                        <button
                            type="button"
                            class="cp-tipo-btn"
                            :class="{ 'cp-tipo-btn--active': form.tipo === 'multiple' }"
                            @click="cambiarTipo('multiple')"
                        >
                            <i class="pi pi-th-large"></i> Múltiple opción
                        </button>
                        <button
                            type="button"
                            class="cp-tipo-btn"
                            :class="{ 'cp-tipo-btn--active': form.tipo === 'boolean' }"
                            @click="cambiarTipo('boolean')"
                        >
                            <i class="pi pi-check-square"></i> Verdadero / Falso
                        </button>
                    </div>
                </div>

                <!-- Opciones de respuesta -->
                <div class="cp-field" :class="{ 'cp-field--error': errores.opciones }">
                    <label class="cp-label">
                        <i class="pi pi-check-circle"></i> Respuestas
                        <span class="cp-label-hint">(marca la correcta)</span>
                    </label>

                    <div class="cp-opciones">
                        <div
                            v-for="(opcion, index) in form.opciones"
                            :key="index"
                            class="cp-opcion"
                            :class="{ 'cp-opcion--correcta': correctaIndex === index }"
                        >
                            <!-- Radio correcta -->
                            <button
                                type="button"
                                class="cp-radio"
                                :class="{ 'cp-radio--active': correctaIndex === index }"
                                @click="marcarCorrecta(index)"
                                :title="'Marcar como correcta'"
                            >
                                <i class="pi" :class="correctaIndex === index ? 'pi-check-circle' : 'pi-circle'"></i>
                            </button>

                            <!-- Input texto -->
                            <input
                                v-model="opcion.texto"
                                class="cp-opcion-input"
                                :placeholder="form.tipo === 'boolean'
                                    ? (index === 0 ? 'Verdadero' : 'Falso')
                                    : `Opción ${String.fromCharCode(65 + index)}`"
                                :readonly="form.tipo === 'boolean'"
                                maxlength="200"
                            />

                            <!-- Badge correcta -->
                            <span v-if="correctaIndex === index" class="cp-badge-correcta">
                                Correcta
                            </span>
                        </div>
                    </div>

                    <span v-if="errores.opciones" class="cp-error-msg">{{ errores.opciones }}</span>
                </div>

                <!-- Botón enviar -->
                <div class="cp-actions">
                    <button type="button" class="cp-btn cp-btn--secondary" @click="resetFormulario">
                        <i class="pi pi-refresh"></i> Limpiar
                    </button>
                    <button type="submit" class="cp-btn cp-btn--primary" :disabled="enviando">
                        <i class="pi" :class="enviando ? 'pi-spin pi-spinner' : 'pi-send'"></i>
                        {{ enviando ? 'Enviando...' : 'Enviar pregunta' }}
                    </button>
                </div>

            </form>
        </div>

        <!-- INFO -->
        <div class="cp-info">
            <i class="pi pi-info-circle"></i>
            <span>Las preguntas enviadas estarán <strong>pendientes de revisión</strong> y no aparecerán en partidas hasta que un administrador las apruebe.</span>
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
        // category-list es pública (no requiere auth),  es más ligera
        const res = await axios.get('/api/category-list');
        categorias.value = res.data?.data ?? res.data ?? [];
    } catch {
        errorGeneral.value = 'No se pudieron cargar las categorías. Recarga la página.';
    }
});

// ──────────────── Métodos ────────────────
function cambiarTipo(nuevoTipo) {
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

    // Construir opciones con es_correcta basado en el radio seleccionado
    const opcionesPayload = form.value.opciones.map((op, i) => ({
        texto:       op.texto.trim(),
        es_correcta: i === correctaIndex.value,
        orden:       i + 1,
    }));

    const payload = {
        enunciado:     form.value.enunciado.trim(),
        categories_id: form.value.categories_id,
        tipo:          form.value.tipo,
        activa:        false,   // <── siempre inactiva hasta revisión del admin
        opciones:      opcionesPayload,
    };

    try {
        await axios.post('/api/questions', payload);
        enviado.value = true;
        resetFormulario();
        enviado.value = true; // resetFormulario lo pone a false, lo volvemos a activar
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } catch (error) {
        const msg = error?.response?.data?.message ?? null;
        const errs = error?.response?.data?.errors ?? null;

        if (errs) {
            // Errores de validación Laravel
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
/* ── Wrapper ── */
.crear-preguntas-wrapper {
    max-width: 760px;
    margin: 0 auto;
    padding: 1.5rem 1rem 3rem;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}

/* ── Header ── */
.cp-header {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1.75rem;
}
.cp-header__icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: linear-gradient(135deg, #6c63ff, #48b9f7);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.6rem;
    flex-shrink: 0;
    box-shadow: 0 4px 16px rgba(108,99,255,.35);
}
.cp-header__title {
    margin: 0 0 .25rem;
    font-size: 1.45rem;
    font-weight: 700;
    color: var(--p-text-color, #1a1a2e);
}
.cp-header__sub {
    margin: 0;
    font-size: .9rem;
    color: var(--p-text-muted-color, #888);
}

/* ── Alerts ── */
.cp-alert {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .9rem 1.1rem;
    border-radius: 10px;
    margin-bottom: 1.25rem;
    font-size: .9rem;
    font-weight: 500;
}
.cp-alert--success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
}
.cp-alert--error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}
.cp-alert__close {
    margin-left: auto;
    background: none;
    border: none;
    cursor: pointer;
    color: inherit;
    opacity: .7;
    transition: opacity .2s;
}
.cp-alert__close:hover { opacity: 1; }

/* ── Card ── */
.cp-card {
    background: var(--p-surface-0, #fff);
    border: 1px solid var(--p-surface-200, #e5e7eb);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
}

/* ── Fields ── */
.cp-field {
    margin-bottom: 1.5rem;
}
.cp-label {
    display: block;
    font-weight: 600;
    font-size: .875rem;
    margin-bottom: .5rem;
    color: var(--p-text-color, #1a1a2e);
}
.cp-label i { margin-right: .35rem; color: #6c63ff; }
.cp-label-hint {
    font-weight: 400;
    font-size: .8rem;
    color: var(--p-text-muted-color, #888);
    margin-left: .4rem;
}
.cp-field--error .cp-textarea,
.cp-field--error .cp-select {
    border-color: #ef4444 !important;
}
.cp-error-msg {
    display: block;
    color: #ef4444;
    font-size: .78rem;
    margin-top: .35rem;
}
.cp-char-count {
    display: block;
    text-align: right;
    font-size: .78rem;
    color: var(--p-text-muted-color, #9ca3af);
    margin-top: .3rem;
}

/* ── Textarea & select ── */
.cp-textarea,
.cp-select {
    width: 100%;
    padding: .7rem 1rem;
    border: 1.5px solid var(--p-surface-300, #d1d5db);
    border-radius: 10px;
    font-size: .9rem;
    font-family: inherit;
    background: var(--p-surface-50, #f9fafb);
    color: var(--p-text-color, #1a1a2e);
    transition: border-color .2s, box-shadow .2s;
    resize: vertical;
    outline: none;
    box-sizing: border-box;
}
.cp-textarea:focus,
.cp-select:focus {
    border-color: #6c63ff;
    box-shadow: 0 0 0 3px rgba(108,99,255,.15);
    background: #fff;
}

/* ── Tipo botones ── */
.cp-tipo-btns {
    display: flex;
    gap: .75rem;
    flex-wrap: wrap;
}
.cp-tipo-btn {
    flex: 1;
    min-width: 160px;
    padding: .7rem 1rem;
    border: 1.5px solid var(--p-surface-300, #d1d5db);
    border-radius: 10px;
    background: var(--p-surface-50, #f9fafb);
    color: var(--p-text-color, #555);
    font-size: .875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
}
.cp-tipo-btn:hover { border-color: #6c63ff; color: #6c63ff; }
.cp-tipo-btn--active {
    border-color: #6c63ff;
    background: linear-gradient(135deg, #ede9ff, #e0f2fe);
    color: #6c63ff;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(108,99,255,.2);
}

/* ── Opciones ── */
.cp-opciones {
    display: flex;
    flex-direction: column;
    gap: .65rem;
}
.cp-opcion {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .6rem .85rem;
    border: 1.5px solid var(--p-surface-200, #e5e7eb);
    border-radius: 10px;
    background: var(--p-surface-50, #f9fafb);
    transition: border-color .2s, background .2s;
}
.cp-opcion--correcta {
    border-color: #22c55e;
    background: #f0fdf4;
}
.cp-radio {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.2rem;
    color: #9ca3af;
    padding: 0;
    line-height: 1;
    transition: color .2s, transform .15s;
    flex-shrink: 0;
}
.cp-radio:hover { transform: scale(1.15); }
.cp-radio--active { color: #22c55e; }
.cp-opcion-input {
    flex: 1;
    border: none;
    background: transparent;
    font-size: .875rem;
    color: var(--p-text-color, #1a1a2e);
    outline: none;
    font-family: inherit;
}
.cp-opcion-input[readonly] {
    color: var(--p-text-muted-color, #6b7280);
    cursor: default;
}
.cp-badge-correcta {
    font-size: .72rem;
    font-weight: 700;
    color: #16a34a;
    background: #dcfce7;
    padding: .2rem .55rem;
    border-radius: 999px;
    white-space: nowrap;
}

/* ── Acciones ── */
.cp-actions {
    display: flex;
    justify-content: flex-end;
    gap: .75rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}
.cp-btn {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .7rem 1.5rem;
    border-radius: 10px;
    font-size: .9rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all .2s;
}
.cp-btn--primary {
    background: linear-gradient(135deg, #6c63ff, #48b9f7);
    color: #fff;
    box-shadow: 0 4px 14px rgba(108,99,255,.35);
}
.cp-btn--primary:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(108,99,255,.45);
}
.cp-btn--primary:disabled {
    opacity: .65;
    cursor: not-allowed;
}
.cp-btn--secondary {
    background: var(--p-surface-100, #f3f4f6);
    color: var(--p-text-color, #374151);
    border: 1.5px solid var(--p-surface-200, #e5e7eb);
}
.cp-btn--secondary:hover {
    background: var(--p-surface-200, #e5e7eb);
}

/* ── Info footer ── */
.cp-info {
    display: flex;
    align-items: flex-start;
    gap: .6rem;
    margin-top: 1.25rem;
    padding: .9rem 1rem;
    background: var(--p-surface-50, #f8fafc);
    border: 1px solid var(--p-surface-200, #e2e8f0);
    border-radius: 10px;
    font-size: .82rem;
    color: var(--p-text-muted-color, #64748b);
}
.cp-info i { color: #6c63ff; margin-top: .05rem; flex-shrink: 0; }

/* ── Transición ── */
.fade-enter-active, .fade-leave-active { transition: opacity .35s, transform .35s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-6px); }
</style>
