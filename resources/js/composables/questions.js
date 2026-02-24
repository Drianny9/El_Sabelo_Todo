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
        getError             // Obtiene el mensaje de error de un campo
    } = useValidation()

    //yup.object sirve para decir, este objeto debe tener estas claves, con estos tipos y estas reglas
    const questionSchema = yup.object({
        categories_id: yup.number().required('La categoría es obligatoria.'),
        tipo:
        enunciado:
        opciones: 
    })


}