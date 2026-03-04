import { ref } from 'vue'
import * as yup from 'yup'
import axios from 'axios'
import { useToast } from './useToast'
import { useValidation } from './useValidation'
import { initial } from 'lodash'

export default function useQuestions() {
    const questions = ref([])
    const questionList = ref([])
    const initialQuestion = {
        id: null,
        categories_id: null,
        tipo: 'multiple',
        enunciado: '',
        activa: true,
        opciones: [
            { texto: '', es_correcta: false, orden: 1 },
            { texto: '', es_correcta: false, orden: 2 }
        ]
    };
    const question = ref({ ...initialQuestion });
    const isLoading = ref(false);
    const toast = useToast();

    const {
        errors,              // Objeto con los errores de validación por campo
        validate,            // Función que valida los datos contra un schema
        handleRequestError,  // Maneja errores de peticiones HTTP
        clearErrors,         // Limpia todos los errores
        hasError,            // Verifica si un campo tiene error
        getError,            // Obtiene el mensaje de error de un campo
        setFieldError        // Establece un error personalizado en un campo
    } = useValidation()

    //yup.object sirve para decir, este objeto debe tener estas claves, con estos tipos y estas reglas
    const questionSchema = yup.object({
        categories_id: yup.number().required('La categoría es obligatoria.'),
        tipo: yup.string().required().oneOf(['multiple', 'boolean'], 'Tipo invalido'),
        enunciado: yup.string().trim().required('El enunciado es obligatorio').min(10, 'Mínimo 10 caracteres'),
        opciones: yup.array().min(2, 'Debe haber almenos 2 opciones').max(4, 'Máximo 4 opciones')
    })


    //Helper para controlar loading
    //Funcion para asegurar que solo hay una operacion a la vez
    const withLoading = async (fn) => {
        if (isLoading.value) return null // Si ya hay una operación, ignorar esta
        isLoading.value = true //Activamos el loading
        try {
            return await fn() //Ejecutar la funcion que se pasó como parámetro
        } finally {
            isLoading.value = false  //Desactivar loading
        }
    }

    //Helper para Reset Question
    //Resetea la pregunta al estado inicial y limpia errores
    const resetQuestion = () => {
        question.value = { ...initialQuestion }  //Volver al estado inicial la lista de preguntas
        clearErrors() //Limpiamos los errores de validación
    }

    //Helper para Set Question
    //Establece los datos de una pregunta
    const setQuestion = (data = {}) => {
        question.value = {
            id: data.id ?? null,
            categories_id: data.categories_id ?? null,
            tipo: data.tipo ?? 'multiple',
            enunciado: data.enunciado ?? '',
            activa: data.activa ?? true,
            opciones: data.opciones ?? [
                { texto: '', es_correcta: false, orden: 1 },
                { texto: '', es_correcta: false, orden: 2 }
            ]
        }
        clearErrors()
    }

    //Actualizar pregunta en lista local sin recargar desde la API
    //upsert(update/insert)
    const upsertQuestionRecord = (questionRecord) => {
        if (!questionRecord?.id) return //Si no tiene ID, salir

        //Agregar la pregunta al inicio y filtrar los duplicados
        questions.value = [
            questionRecord,
            ...questions.value.filter(item => item.id !== questionRecord.id) //Resto sin duplicados
        ]
    }

    //GET - Obtenemos todas las preguntas
    const getQuestions = async (params = {}) => {
        try {
            const defaultParams = {
                page: 1,                        // Página actual                        
                search_id: '',                  // Buscar por ID
                search_title: '',               // Buscar por nombre
                search_global: '',              // Búsqueda global
                order_column: 'created_at',     // Columna para ordenar
                order_direction: 'desc'         // Dirección: 'asc' o 'desc'
            }

            // Combinar parámetros
            const mergedParams = { ...defaultParams, ...params }
            
            // Eliminar parámetros vacíos, null o "null"
            const cleanParams = Object.fromEntries(
                Object.entries(mergedParams).filter(([_, value]) => 
                    value !== '' && value !== null && value !== 'null' && value !== undefined
                )
            )

            // Convertir a query string: ?page=1&search_global=...
            const query = new URLSearchParams(cleanParams).toString()

            // Hacer petición GET a la API Laravel
            const response = await withLoading(() => axios.get(`/api/questions?${query}`))

            // Si withLoading retorna null (operación en curso), salir
            if (!response) return

            // Guardar las preguntas en el estado reactivo
            // Maneja diferentes estructuras de respuesta (response.data.data o response.data)
            questions.value = response.data?.data ?? response.data ?? []
            return response
        } catch (error) {
            toast.error('Error', 'No se pudieron cargar las preguntas')
            throw error
        }
    }

    //GET - Obtener preguntas por categoría
    const getQuestionsByCategory = async (categoriesId) => {
        try{
            const response = await withLoading(() =>
            axios.get(`/api/questions?categories_id=${categoryId}`)
            )
            questions.value = response.data?.data ?? response.data ?? []
            return response
        } catch (error){
            toast.error('Error', 'No se pudieron cargar las preguntas de esta categoría')
            throw error
        }
    }

    //GET - Obtener una pregunta específica
    const getQuestion = async (id) => {
    if (!id) return null
    try {
      const response = await withLoading(() => axios.get(`/api/questions/${id}`))
      const data = response.data?.data ?? response.data
      setQuestion(data)
      return data
    } catch (error) {
      toast.error('Error', 'No se pudo obtener la pregunta')
      throw error
    }
  }

    //GET - Obtener lista simple de preguntas (sin paginación)
    const getQuestionList = async () => {
        try {
        const response = await axios.get('/api/questions')
        questionList.value = response.data?.data ?? response.data ?? []
        return response
        } catch (error) {
        toast.error('Error', 'No se pudo obtener la lista de preguntas')
        throw error
        }
    }

    //POST - Crear nueva pregunta
    const createQuestion = async () => {
        //Validar los datos antes de enviar
        const { isValid } = validate(questionSchema, question.value)

        if(!isValid) {
            //Si la validación falla, mostrar error y detener
            toast.error('Error de validación', 'Revisa los campos resaltados.')
            throw new Error('Validación')
        }

        // Validar que al menos una opción sea correcta
        const hasCorrectOption = question.value.opciones.some(op => op.es_correcta)
        if (!hasCorrectOption) {
            toast.error('Error', 'Debe marcar al menos una opción como correcta')
            throw new Error('Validación')
        }

        try{
            //Enviar petición POST a la API con withLoading
            const response = await withLoading(() =>
                axios.post('/api/questions', question.value)
            )
            //Obtener los datos de la categoria creada
            const data = response.data?.data ?? response.data
            //Mostrar notificación de exito
            toast.crud.created('Pregunta')
            return data
        }catch (error) {
            //Manejar errores de validación del backend o errores genericos
            if (error.response?.data?.errors) {
                Object.entries(error.response.data.errors).forEach(([field, messages]) => {
                    setFieldError(field, messages[0])
                })
            }
            toast.error('Error', 'No se pudo crear la pregunta')
            throw error
        }
    }


  //Update de preguntas
  const updateQuestion = async () => {
    //VALIDAR los datos antes de enviar
    const { isValid } = await validate(questionSchema, question.value)
    
    if (!isValid) {
      toast.error('Error de validación', 'Revisa los campos resaltados.')
      throw new Error('Validación')
    }

    // Validar que al menos una opción sea correcta
    const hasCorrectOption = question.value.opciones.some(op => op.es_correcta)
    if (!hasCorrectOption) {
      toast.error('Error', 'Debe marcar al menos una opción como correcta')
      throw new Error('Validación')
    }

    try {
      //ENVIAR petición PUT a la API con el ID de la pregunta
      const response = await withLoading(() =>
        axios.put(`/api/questions/${question.value.id}`, question.value)
      )
      
      //OBTENER los datos actualizados
      const data = response.data?.data ?? response.data
      toast.crud.updated('Pregunta')
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

    //Eliminar preguntas
    const deleteQuestion = async (id) => {
        try {
            const response = await withLoading(() => axios.delete(`/api/questions/${id}`))
            questions.value = questions.value.filter(q => q.id !== id)
            toast.crud.deleted('Pregunta')
            return response
        } catch (error) {
            const message = error?.response?.data?.message || 'No se pudo eliminar la pregunta'
            toast.error('Error', message)
            throw error
    }
  }

  // ============================================
  // HELPERS PARA OPCIONES
  // ============================================

  /**
   * Agregar una nueva opción vacía
   */
  const addOption = () => {
    if (question.value.opciones.length >= 4) {
      toast.warning('Máximo alcanzado', 'No puedes agregar más de 4 opciones')
      return
    }
    
    const newOrder = question.value.opciones.length + 1
    question.value.opciones.push({
      texto: '',
      es_correcta: false,
      orden: newOrder
    })
  }

  /**
   * Eliminar una opción por índice
   */
  const removeOption = (index) => {
    if (question.value.opciones.length <= 2) {
      toast.warning('Mínimo requerido', 'Debe haber al menos 2 opciones')
      return
    }
    
    question.value.opciones.splice(index, 1)
    
    // Reordenar
    question.value.opciones.forEach((opcion, i) => {
      opcion.orden = i + 1
    })
  }

  /**
   * Marcar una opción como correcta (y desmarcar las demás si es tipo boolean)
   */
  const markAsCorrect = (index) => {
    // Si es tipo boolean, solo puede haber una correcta
    if (question.value.tipo === 'boolean') {
      question.value.opciones.forEach((opcion, i) => {
        opcion.es_correcta = (i === index)
      })
    } else {
      // Si es multiple, permitir varias correctas
      question.value.opciones[index].es_correcta = !question.value.opciones[index].es_correcta
    }
  }


  return {
    // Estado
    questions,
    question,
    questionList,
    isLoading,
    errors,
    
    // Validación
    hasError,
    getError,
    
    // Métodos de gestión
    resetQuestion,
    setQuestion,
    upsertQuestionRecord,
    
    // CRUD
    getQuestions,
    getQuestionsByCategory,
    getQuestion,
    getQuestionList,
    createQuestion,
    updateQuestion,
    deleteQuestion,
    
    // Helpers de opciones
    addOption,
    removeOption,
    markAsCorrect
  }


}