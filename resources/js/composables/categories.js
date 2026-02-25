import { ref } from 'vue'
import * as yup from 'yup'
import axios from 'axios'
import { useToast } from './useToast'
import { useValidation } from './useValidation'

export default function useCategories() {
  
  const categories = ref([])
  const categoryList = ref([])
  const initialCategory = { id: null, name: '' }
  const category = ref({ ...initialCategory })
  const isLoading = ref(false)
  const toast = useToast()

  const {
    errors,
    validate,
    handleRequestError,
    clearErrors,
    hasError,
    getError
  } = useValidation()

  const categorySchema = yup.object({
    name: yup
      .string()
      .trim()
      .required('El nombre es obligatorio')
      .min(3, 'Debe tener al menos 3 caracteres')
  })

  const withLoading = async (fn) => {
    if (isLoading.value) throw new Error('Operación en curso')
    
    isLoading.value = true
    try {
      return await fn()
    } finally {
      isLoading.value = false
    }
  }

  const resetCategory = () => {
    category.value = { ...initialCategory }
    clearErrors()
  }

  const setCategory = (data = {}) => {
    category.value = {
      id: data.id ?? null,
      name: data.name ?? ''
    }
    clearErrors()
  }

  const upsertCategoryRecord = (categoryRecord) => {
    if (!categoryRecord?.id) return
    
    categories.value = [
      categoryRecord,
      ...categories.value.filter(item => item.id !== categoryRecord.id)
    ]
  }

  const getCategories = async (params = {}) => {
    const defaultParams = {
      page: 1,
      search_id: '',
      search_title: '',
      search_global: '',
      order_column: 'created_at',
      order_direction: 'desc'
    }

    const query = new URLSearchParams({ ...defaultParams, ...params }).toString()
    const response = await axios.get(`/api/categories?${query}`)
    categories.value = response.data?.data ?? response.data.data ?? []
    
    return response
  }

  const getCategoryList = async () => {
    try {
      const response = await axios.get('/api/category-list')
      categoryList.value = response.data?.data ?? response.data ?? []
      
      return response
    } catch (error) {
      handleRequestError(error, {
        fallbackMessage: 'No se pudo obtener la lista de categorías',
        onGenericError: (message) => toast.error('Error', message)
      })
    }
  }

  const createCategory = async () => {
    const { isValid } = await validate(categorySchema, category.value)
    
    if (!isValid) {
      toast.error('Error de validación', 'Revisa los campos resaltados.')
      throw new Error('Validación')
    }

    try {
      const response = await withLoading(() =>
        axios.post('/api/categories', { name: category.value.name })
      )
      
      const data = response.data?.data ?? response.data
      toast.crud.created('Categoría')
      
      return data
      
    } catch (error) {
      handleRequestError(error, {
        fallbackMessage: 'No se pudo crear la categoría',
        onValidationError: () =>
          toast.error('Error de validación', 'Revisa los campos resaltados.'),
        onGenericError: (message) => toast.error('Error', message)
      })
    }
  }

  const updateCategory = async () => {
    const { isValid } = await validate(categorySchema, category.value)
    
    if (!isValid) {
      toast.error('Error de validación', 'Revisa los campos resaltados.')
      throw new Error('Validación')
    }

    try {
      const response = await withLoading(() =>
        axios.put(`/api/categories/${category.value.id}`, {
          name: category.value.name
        })
      )
      
      const data = response.data?.data ?? response.data
      toast.crud.updated('Categoría')
      
      return data
      
    } catch (error) {
      handleRequestError(error, {
        fallbackMessage: 'No se pudo actualizar la categoría',
        onValidationError: () =>
          toast.error('Error de validación', 'Revisa los campos resaltados.'),
        onGenericError: (message) => toast.error('Error', message)
      })
    }
  }

  const deleteCategory = async (id) => {
    try {
      const response = await withLoading(() => axios.delete(`/api/categories/${id}`))
      categories.value = categories.value.filter(item => item.id !== id)
      toast.crud.deleted('Categoría')
      
      return response
      
    } catch (error) {
      handleRequestError(error, {
        fallbackMessage: 'No se pudo eliminar la categoría',
        onGenericError: (message) => toast.error('Error', message)
      })
    }
  }

  return {
    categories,
    category,
    categoryList,
    isLoading,
    errors,
    hasError,
    getError,
    resetCategory,
    setCategory,
    upsertCategoryRecord,
    getCategories,
    getCategoryList,
    createCategory,
    updateCategory,
    deleteCategory
  }
}
