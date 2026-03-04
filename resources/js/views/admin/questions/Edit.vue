<template>
  <!--Formulario para editar preguntas-->
  <div class="p-6">
    <Card>
      <template #title>
        <div class="flex items-center gap-3">
          <Button
            icon="pi pi-arrow-left"
            text
            rounded
            @click="$router.push({ name: 'questions.index' })"
          />
          <span>Editar Pregunta #{{ $route.params.id }}</span>
        </div>
      </template>

      <template #content>
        <div v-if="isLoading" class="flex justify-center py-8">
          <ProgressSpinner />
        </div>

        <form v-else @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Mismo contenido que Create.vue -->
          <!-- Categoría -->
          <div class="flex flex-col gap-2">
            <label class="font-semibold">Categoría *</label>
            <Select
              v-model="question.categories_id"
              :options="categoryList"
              optionLabel="name"
              optionValue="id"
              placeholder="Selecciona una categoría"
              :class="{ 'p-invalid': hasError('categories_id') }"
            />
            <small v-if="hasError('categories_id')" class="text-red-500">
              {{ getError('categories_id') }}
            </small>
          </div>

          <!-- Tipo -->
          <div class="flex flex-col gap-2">
            <label class="font-semibold">Tipo de Pregunta *</label>
            <Select
              v-model="question.tipo"
              :options="tipoOptions"
              optionLabel="label"
              optionValue="value"
              placeholder="Selecciona el tipo"
            />
          </div>

          <!-- Enunciado -->
          <div class="flex flex-col gap-2">
            <label class="font-semibold">Enunciado *</label>
            <Textarea
              v-model="question.enunciado"
              rows="3"
              placeholder="Escribe la pregunta..."
              :class="{ 'p-invalid': hasError('enunciado') }"
            />
            <small v-if="hasError('enunciado')" class="text-red-500">
              {{ getError('enunciado') }}
            </small>
          </div>

          <!-- Opciones -->
          <div>
            <div class="flex justify-between items-center mb-4">
              <label class="font-semibold">Opciones de Respuesta *</label>
              <Button
                v-if="question.opciones.length < 4"
                icon="pi pi-plus"
                label="Agregar Opción"
                outlined
                size="small"
                @click="addOption"
              />
            </div>

            <div class="space-y-3">
              <div
                v-for="(opcion, index) in question.opciones"
                :key="index"
                class="flex gap-3 items-start p-4 bg-gray-50 rounded-lg"
              >
                <div class="flex-1">
                  <InputText
                    v-model="opcion.texto"
                    :placeholder="`Opción ${index + 1}`"
                    class="w-full"
                  />
                </div>
                <div class="flex gap-2 items-center">
                  <Checkbox
                    v-model="opcion.es_correcta"
                    :binary="true"
                    @change="markAsCorrect(index)"
                  />
                  <label class="text-sm">Correcta</label>
                </div>
                <Button
                  v-if="question.opciones.length > 2"
                  icon="pi pi-trash"
                  severity="danger"
                  text
                  rounded
                  @click="removeOption(index)"
                />
              </div>
            </div>
          </div>

          <!-- Activa -->
          <div class="flex items-center gap-2">
            <Checkbox v-model="question.activa" :binary="true" inputId="activa" />
            <label for="activa">Pregunta activa</label>
          </div>

          <!-- Botones -->
          <div class="flex gap-2 justify-end pt-4 border-t">
            <Button
              label="Cancelar"
              severity="secondary"
              outlined
              @click="$router.push({ name: 'questions.index' })"
            />
            <Button
              type="submit"
              label="Actualizar Pregunta"
              :loading="isLoading"
            />
          </div>
        </form>
      </template>
    </Card>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import useQuestions from '@/composables/questions'
import useCategories from '@/composables/categories'

const router = useRouter()
const route = useRoute()

const {
  question,
  isLoading,
  hasError,
  getError,
  getQuestion,
  updateQuestion,
  addOption,
  removeOption,
  markAsCorrect
} = useQuestions()

const { categoryList, getCategoryList } = useCategories()

const tipoOptions = [
  { label: 'Opción Múltiple', value: 'multiple' },
  { label: 'Verdadero/Falso', value: 'boolean' }
]

const handleSubmit = async () => {
  try {
    await updateQuestion()
    router.push({ name: 'questions.index' })
  } catch (error) {
    console.error('Error al actualizar pregunta:', error)
  }
}

onMounted(async () => {
  await getCategoryList()
  await getQuestion(route.params.id)
})
</script>