// ============================================
// IMPORTS
// ============================================
// ref: Crea variables reactivas que Vue actualiza automáticamente en la vista
import { ref } from 'vue'
// yup: Librería para validar formularios (como verificar que un campo no esté vacío)
import * as yup from 'yup'
// axios: Cliente HTTP para hacer peticiones a la API Laravel
import axios from 'axios'
// useToast: Composable para mostrar notificaciones (éxito, error, etc.)
import { useToast } from './useToast'
// useValidation: Composable para manejar validaciones de formularios
import { useValidation } from './useValidation'

// ============================================
// COMPOSABLE PRINCIPAL
// ============================================
// Este composable encapsula toda la lógica para trabajar con CATEGORÍAS
// Puedes usarlo en cualquier componente Vue que necesite listar, crear, editar o eliminar categorías
export default function useCategories() {
  
  // ============================================
  // ESTADO REACTIVO (Datos que Vue actualiza automáticamente)
  // ============================================
  
  // categories: Array de todas las categorías (para tablas/listas con paginación)
  const categories = ref([])
  
  // categoryList: Array simple de categorías (para dropdowns/selects)
  const categoryList = ref([])
  
  // initialCategory: Estado inicial de una categoría vacía
  const initialCategory = { id: null, name: '' }
  
  // category: Categoría individual (para crear/editar)
  const category = ref({ ...initialCategory })
  
  // isLoading: Bandera que indica si hay una operación en curso (para mostrar spinners)
  const isLoading = ref(false)
  
  // toast: Sistema de notificaciones (se usa para mostrar mensajes al usuario)
  const toast = useToast()

  // ============================================
  // VALIDACIÓN
  // ============================================
  // Extraemos las funciones de validación del composable useValidation
  const {
    errors,              // Objeto con los errores de validación por campo
    validate,            // Función que valida los datos contra un schema
    handleRequestError,  // Maneja errores de peticiones HTTP
    clearErrors,         // Limpia todos los errores
    hasError,            // Verifica si un campo tiene error
    getError             // Obtiene el mensaje de error de un campo
  } = useValidation()

  // ============================================
  // SCHEMA DE VALIDACIÓN (Reglas de validación)
  // ============================================
  // Define las reglas que debe cumplir una categoría
  const categorySchema = yup.object({
    name: yup
      .string()                                    // Debe ser texto
      .trim()                                       // Elimina espacios al inicio y final
      .required('El nombre es obligatorio')        // No puede estar vacío
      .min(3, 'Debe tener al menos 3 caracteres')  // Mínimo 3 caracteres
  })

  // ============================================
  // HELPER: Control de Loading
  // ============================================
  // Esta función asegura que solo haya una operación en curso a la vez
  // Activa isLoading, ejecuta la función, y luego desactiva isLoading
  const withLoading = async (fn) => {
    // Si ya hay una operación en curso, lanzar error
    if (isLoading.value) throw new Error('Operación en curso')
    
    isLoading.value = true  // Activar loading (muestra spinner en la UI)
    try {
      return await fn()     // Ejecutar la función que se pasó como parámetro
    } finally {
      isLoading.value = false  // Desactivar loading siempre (aunque falle)
    }
  }

  // ============================================
  // HELPER: Reset Category
  // ============================================
  // Resetea la categoría al estado inicial y limpia los errores
  // Útil después de crear/editar o al cancelar un formulario
  const resetCategory = () => {
    category.value = { ...initialCategory }  // Volver al estado inicial
    clearErrors()                            // Limpiar errores de validación
  }

  // ============================================
  // HELPER: Set Category
  // ============================================
  // Establece los datos de una categoría (útil para editar)
  // Ejemplo de uso: setCategory({ id: 1, name: 'Ciencia' })
  const setCategory = (data = {}) => {
    category.value = {
      id: data.id ?? null,      // Si no tiene id, usa null
      name: data.name ?? ''     // Si no tiene name, usa string vacío
    }
    clearErrors()  // Limpiar errores al cargar nuevos datos
  }

  // ============================================
  // HELPER: Upsert Category Record
  // ============================================
  // Actualiza o inserta una categoría en el array local de categories
  // Útil para actualizar la lista sin tener que recargar desde la API
  const upsertCategoryRecord = (categoryRecord) => {
    if (!categoryRecord?.id) return  // Si no tiene ID, salir
    
    // Agregar la categoría al inicio y filtrar duplicados
    categories.value = [
      categoryRecord,  // Nueva o actualizada al inicio
      ...categories.value.filter(item => item.id !== categoryRecord.id)  // Resto sin duplicados
    ]
  }

  // ============================================
  // CRUD: GET CATEGORIES (Listar con paginación)
  // ============================================
  // Obtiene todas las categorías con opciones de búsqueda, paginación y ordenamiento
  // Ejemplo de uso: await getCategories({ page: 2, search_global: 'Ciencia' })
  const getCategories = async (params = {}) => {
    // Parámetros por defecto
    const defaultParams = {
      page: 1,                      // Página actual
      search_id: '',                // Buscar por ID
      search_title: '',             // Buscar por nombre
      search_global: '',            // Búsqueda global
      order_column: 'created_at',   // Columna para ordenar
      order_direction: 'desc'       // Dirección: 'asc' o 'desc'
    }

    // Combinar parámetros por defecto con los que se pasan
    // Convertir a query string: ?page=1&search_global=...
    const query = new URLSearchParams({ ...defaultParams, ...params }).toString()
    
    // Hacer petición GET a la API Laravel
    const response = await axios.get(`/api/categories?${query}`)
    
    // Guardar las categorías en el estado reactivo
    // Maneja diferentes estructuras de respuesta (response.data.data o response.data)
    categories.value = response.data?.data ?? response.data.data ?? []
    
    return response
  }

  // ============================================
  // CRUD: GET CATEGORY LIST (Lista simple)
  // ============================================
  // Obtiene una lista simple de categorías (sin paginación)
  // Útil para dropdowns/selects en formularios
  const getCategoryList = async () => {
    try {
      // Petición a un endpoint específico para listas (más ligero, sin paginación)
      const response = await axios.get('/api/category-list')
      
      // Guardar en categoryList (separado de categories)
      categoryList.value = response.data?.data ?? response.data ?? []
      
      return response
    } catch (error) {
      // Manejar errores de la petición
      handleRequestError(error, {
        fallbackMessage: 'No se pudo obtener la lista de categorías',
        onGenericError: (message) => toast.error('Error', message)
      })
    }
  }

  // ============================================
  // CRUD: CREATE CATEGORY (Crear nueva categoría)
  // ============================================
  // Crea una nueva categoría en la base de datos
  // Ejemplo de uso: category.value.name = 'Ciencia'; await createCategory()
  const createCategory = async () => {
    // 1️⃣ VALIDAR los datos antes de enviar
    const { isValid } = await validate(categorySchema, category.value)
    
    if (!isValid) {
      // Si la validación falla, mostrar error y detener
      toast.error('Error de validación', 'Revisa los campos resaltados.')
      throw new Error('Validación')
    }

    try {
      // 2️⃣ ENVIAR petición POST a la API con withLoading (muestra spinner)
      const response = await withLoading(() =>
        axios.post('/api/categories', { name: category.value.name })
      )
      
      // 3️⃣ OBTENER los datos de la categoría creada
      const data = response.data?.data ?? response.data
      
      // 4️⃣ MOSTRAR notificación de éxito
      toast.crud.created('Categoría')
      
      // 5️⃣ RETORNAR los datos (para que el componente pueda usarlos)
      return data
      
    } catch (error) {
      // Manejar errores de validación del backend o errores genéricos
      handleRequestError(error, {
        fallbackMessage: 'No se pudo crear la categoría',
        onValidationError: () =>
          toast.error('Error de validación', 'Revisa los campos resaltados.'),
        onGenericError: (message) => toast.error('Error', message)
      })
    }
  }

  // ============================================
  // CRUD: UPDATE CATEGORY (Actualizar categoría)
  // ============================================
  // Actualiza una categoría existente en la base de datos
  // Ejemplo de uso: setCategory({ id: 1, name: 'Ciencia' }); category.value.name = 'Ciencias'; await updateCategory()
  const updateCategory = async () => {
    // 1️⃣ VALIDAR los datos antes de enviar
    const { isValid } = await validate(categorySchema, category.value)
    
    if (!isValid) {
      toast.error('Error de validación', 'Revisa los campos resaltados.')
      throw new Error('Validación')
    }

    try {
      // 2️⃣ ENVIAR petición PUT a la API con el ID de la categoría
      const response = await withLoading(() =>
        axios.put(`/api/categories/${category.value.id}`, {
          name: category.value.name
        })
      )
      
      // 3️⃣ OBTENER los datos actualizados
      const data = response.data?.data ?? response.data
      
      // 4️⃣ MOSTRAR notificación de éxito
      toast.crud.updated('Categoría')
      
      // 5️⃣ RETORNAR los datos actualizados
      return data
      
    } catch (error) {
      // Manejar errores
      handleRequestError(error, {
        fallbackMessage: 'No se pudo actualizar la categoría',
        onValidationError: () =>
          toast.error('Error de validación', 'Revisa los campos resaltados.'),
        onGenericError: (message) => toast.error('Error', message)
      })
    }
  }

  // ============================================
  // CRUD: DELETE CATEGORY (Eliminar categoría)
  // ============================================
  // Elimina una categoría de la base de datos
  // Ejemplo de uso: await deleteCategory(1)
  const deleteCategory = async (id) => {
    try {
      // 1️⃣ ENVIAR petición DELETE a la API
      const response = await withLoading(() => axios.delete(`/api/categories/${id}`))
      
      // 2️⃣ ELIMINAR la categoría del array local (actualiza la UI sin recargar)
      categories.value = categories.value.filter(item => item.id !== id)
      
      // 3️⃣ MOSTRAR notificación de éxito
      toast.crud.deleted('Categoría')
      
      return response
      
    } catch (error) {
      // Manejar errores (por ejemplo, si la categoría tiene preguntas asociadas)
      handleRequestError(error, {
        fallbackMessage: 'No se pudo eliminar la categoría',
        onGenericError: (message) => toast.error('Error', message)
      })
    }
  }

  // ============================================
  // RETURN: Exportar todo lo que queremos usar en los componentes
  // ============================================
  // Todo lo que pongas aquí estará disponible cuando uses el composable
  // Ejemplo en un componente: const { categories, createCategory } = useCategories()
  return {
    // ESTADO (datos reactivos)
    categories,       // Array de categorías con paginación
    category,         // Categoría individual para crear/editar
    categoryList,     // Lista simple de categorías (para selects)
    isLoading,        // Bandera de carga (para mostrar spinners)
    errors,           // Errores de validación por campo
    
    // VALIDACIÓN (funciones de validación)
    hasError,         // Verifica si un campo tiene error
    getError,         // Obtiene el mensaje de error de un campo
    
    // HELPERS (funciones auxiliares)
    resetCategory,    // Resetea category al estado inicial
    setCategory,      // Establece los datos de una categoría
    upsertCategoryRecord,  // Actualiza/inserta en el array local
    
    // CRUD (operaciones de base de datos)
    getCategories,    // Listar con paginación
    getCategoryList,  // Lista simple sin paginación
    createCategory,   // Crear nueva categoría
    updateCategory,   // Actualizar categoría existente
    deleteCategory    // Eliminar categoría
  }
}
