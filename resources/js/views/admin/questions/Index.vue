<template>
  <div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Preguntas</h1>
        <p class="text-gray-600 mt-1">Gestiona las preguntas del juego</p>
      </div>
      <div class="flex gap-2">
        <Button
          icon="pi pi-refresh"
          label="Actualizar"
          outlined
          @click="loadQuestions"
          :loading="isLoading"
        />
        <Button
          icon="pi pi-plus"
          label="Nueva Pregunta"
          @click="$router.push({ name: 'questions.create' })"
        />
      </div>
    </div>

    <!-- Filtros -->
    <Card class="mb-6">
      <template #content>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="flex flex-col gap-2">
            <label class="font-semibold text-sm">Buscar por ID</label>
            <InputText
              v-model="filters.search_id"
              placeholder="ID de pregunta"
              @input="onFilter"
            />
          </div>
          <div class="flex flex-col gap-2">
            <label class="font-semibold text-sm">Buscar por texto</label>
            <InputText
              v-model="filters.search_title"
              placeholder="Enunciado..."
              @input="onFilter"
            />
          </div>
          <div class="flex flex-col gap-2">
            <label class="font-semibold text-sm">Tipo</label>
            <Select
              v-model="filters.tipo"
              :options="tipoOptions"
              optionLabel="label"
              optionValue="value"
              placeholder="Todos"
              showClear
              @change="onFilter"
            />
          </div>
        </div>
      </template>
    </Card>

    <!-- Tabla -->
    <DataTable
      :value="questions"
      :loading="isLoading"
      paginator
      :rows="10"
      :rowsPerPageOptions="[10, 25, 50]"
      stripedRows
      showGridlines
      responsiveLayout="scroll"
    >
      <!-- ID -->
      <Column field="id" header="ID" sortable style="width: 80px">
        <template #body="{ data }">
          <Tag :value="data.id" severity="info" />
        </template>
      </Column>

      <!-- Pregunta -->
      <Column field="enunciado" header="Pregunta" sortable>
        <template #body="{ data }">
          <div class="max-w-md">
            <p class="font-semibold text-gray-800 line-clamp-2">
              {{ data.enunciado }}
            </p>
          </div>
        </template>
      </Column>

      <!-- Categoría -->
      <Column field="category.name" header="Categoría" sortable style="width: 150px">
        <template #body="{ data }">
          <Tag
            v-if="data.category"
            :value="data.category.name"
            severity="secondary"
          />
        </template>
      </Column>

      <!-- Tipo -->
      <Column field="tipo" header="Tipo" sortable style="width: 120px">
        <template #body="{ data }">
          <Tag
            :value="data.tipo === 'multiple' ? 'Múltiple' : 'V/F'"
            :severity="data.tipo === 'multiple' ? 'success' : 'warning'"
          />
        </template>
      </Column>

      <!-- Estado -->
      <Column field="activa" header="Estado" sortable style="width: 100px">
        <template #body="{ data }">
          <Tag
            :value="data.activa ? 'Activa' : 'Inactiva'"
            :severity="data.activa ? 'success' : 'danger'"
          />
        </template>
      </Column>

      <!-- Opciones -->
      <Column header="Opciones" style="width: 100px">
        <template #body="{ data }">
          <Tag
            :value="data.opciones?.length || 0"
            severity="info"
            icon="pi pi-list"
          />
        </template>
      </Column>

      <!-- Acciones -->
      <Column header="Acciones" style="width: 150px">
        <template #body="{ data }">
          <div class="flex gap-2">
            <Button
              icon="pi pi-eye"
              severity="info"
              text
              rounded
              @click="viewQuestion(data)"
              v-tooltip.top="'Ver detalles'"
            />
            <Button
              icon="pi pi-pencil"
              severity="warning"
              text
              rounded
              @click="editQuestion(data.id)"
              v-tooltip.top="'Editar'"
            />
            <Button
              icon="pi pi-trash"
              severity="danger"
              text
              rounded
              @click="confirmDelete(data.id)"
              v-tooltip.top="'Eliminar'"
            />
          </div>
        </template>
      </Column>

      <!-- Loading Skeleton -->
      <template #loading>
        <div class="flex flex-col gap-2">
          <Skeleton height="3rem" />
          <Skeleton height="3rem" />
          <Skeleton height="3rem" />
        </div>
      </template>

      <!-- Empty State -->
      <template #empty>
        <div class="text-center py-8">
          <i class="pi pi-inbox text-6xl text-gray-300 mb-4"></i>
          <p class="text-gray-500 text-lg">No hay preguntas disponibles</p>
        </div>
      </template>
    </DataTable>

    <!-- Dialog: Ver Detalles -->
    <Dialog
      v-model:visible="detailsDialog"
      modal
      header="Detalles de la Pregunta"
      :style="{ width: '50vw' }"
    >
      <div v-if="selectedQuestion">
        <div class="space-y-4">
          <!-- Info básica -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-semibold text-gray-600">ID</label>
              <p class="text-lg">{{ selectedQuestion.id }}</p>
            </div>
            <div>
              <label class="text-sm font-semibold text-gray-600">Categoría</label>
              <p class="text-lg">{{ selectedQuestion.category?.name }}</p>
            </div>
            <div>
              <label class="text-sm font-semibold text-gray-600">Tipo</label>
              <p class="text-lg">{{ selectedQuestion.tipo === 'multiple' ? 'Múltiple' : 'Verdadero/Falso' }}</p>
            </div>
            <div>
              <label class="text-sm font-semibold text-gray-600">Estado</label>
              <p class="text-lg">{{ selectedQuestion.activa ? 'Activa' : 'Inactiva' }}</p>
            </div>
          </div>

          <!-- Enunciado -->
          <div>
            <label class="text-sm font-semibold text-gray-600">Enunciado</label>
            <p class="text-lg mt-1 p-4 bg-gray-50 rounded-lg">{{ selectedQuestion.enunciado }}</p>
          </div>

          <!-- Opciones -->
          <div>
            <label class="text-sm font-semibold text-gray-600 mb-2 block">Opciones de Respuesta</label>
            <div class="space-y-2">
              <div
                v-for="opcion in selectedQuestion.opciones"
                :key="opcion.id"
                class="flex items-center gap-3 p-3 rounded-lg"
                :class="opcion.es_correcta ? 'bg-green-50 border-2 border-green-500' : 'bg-gray-50'"
              >
                <i
                  :class="opcion.es_correcta ? 'pi pi-check-circle text-green-600' : 'pi pi-times-circle text-gray-400'"
                  class="text-xl"
                ></i>
                <span class="flex-1">{{ opcion.texto }}</span>
                <Tag
                  v-if="opcion.es_correcta"
                  value="Correcta"
                  severity="success"
                />
              </div>
            </div>
          </div>

          <!-- Metadatos -->
          <div class="grid grid-cols-2 gap-4 pt-4 border-t">
            <div>
              <label class="text-sm font-semibold text-gray-600">Creada por</label>
              <p class="text-sm">{{ selectedQuestion.created_by?.name || 'N/A' }}</p>
            </div>
            <div>
              <label class="text-sm font-semibold text-gray-600">Fecha de creación</label>
              <p class="text-sm">{{ formatDate(selectedQuestion.created_at) }}</p>
            </div>
          </div>
        </div>
      </div>

      <template #footer>
        <Button label="Cerrar" @click="detailsDialog = false" />
      </template>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, inject } from 'vue'
import { useRouter } from 'vue-router'
import useQuestions from '@/composables/questions'

const router = useRouter()
const swal = inject('$swal')

const {
  questions,
  isLoading,
  getQuestions,
  deleteQuestion
} = useQuestions()

// Estado
const detailsDialog = ref(false)
const selectedQuestion = ref(null)

const filters = reactive({
  search_id: '',
  search_title: '',
  tipo: null
})

const tipoOptions = [
  { label: 'Múltiple', value: 'multiple' },
  { label: 'Verdadero/Falso', value: 'boolean' }
]

// Métodos
const loadQuestions = async () => {
  await getQuestions(filters)
}

const onFilter = () => {
  loadQuestions()
}

const viewQuestion = (question) => {
  selectedQuestion.value = question
  detailsDialog.value = true
}

const editQuestion = (id) => {
  router.push({ name: 'questions.edit', params: { id } })
}

const confirmDelete = (id) => {
  swal({
    title: '¿Eliminar pregunta?',
    text: 'Esta acción no se puede deshacer',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#ef4444',
  }).then((result) => {
    if (result.isConfirmed) {
      deleteQuestion(id)
    }
  })
}

const formatDate = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Lifecycle
onMounted(() => {
  loadQuestions()
})
</script>