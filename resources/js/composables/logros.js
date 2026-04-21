import { ref, computed } from 'vue'
import axios from 'axios'

export default function useLogros() {
    //Catálogo global: todos los logros del sistema
    const allLogros = ref([])
    //Solo los logros que tiene desbloqueados el usuario actual
    const misLogros = ref([])
    //Puntuación total acumulada del usuario
    const userScore = ref(0)
    const loading = ref(false)

    //Computed: Logros que aún NO tiene el usuario (retos pendientes de completar)
    const logrosPendientes = computed(() => {
        return allLogros.value.filter(logro => !hasLogro(logro.id))
    })

    //Computed: Logros que SÍ tiene el usuario (ya conseguidos)
    const logrosConseguidos = computed(() => {
        return allLogros.value.filter(logro => hasLogro(logro.id))
    })

    //Función de ayuda para comprobar si el usuario ya tiene un logro concreto por su id
    const hasLogro = (id) => {
        return misLogros.value.some(l => l.id === id)
    }

    //Pedir el catálogo completo de logros del sistema
    const fetchAllLogros = async () => {
        try {
            const response = await axios.get('/api/logros')
            allLogros.value = response.data
            return response.data
        } catch (error) {
            console.error("Error al cargar el catálogo de logros:", error)
            throw error
        }
    }

    //Pedir los logros y la puntuación específica del usuario autenticado
    const fetchMisLogros = async () => {
        try {
            const response = await axios.get('/api/logros/mis-logros')
            misLogros.value = response.data.logros
            userScore.value = response.data.puntuacion || 0
            return response.data
        } catch (error) {
            console.error("Error al cargar los logros del usuario:", error)
            throw error
        }
    }

    //Función que llama a los dos endpoints y rellena el estado completo
    const fetchLogrosData = async () => {
        loading.value = true
        try {
            await Promise.all([fetchAllLogros(), fetchMisLogros()])
        } catch (error) {
            console.error("Error al cargar los logros:", error)
        } finally {
            loading.value = false
        }
    }

    return {
        allLogros,
        misLogros,
        userScore,
        loading,
        logrosPendientes,
        logrosConseguidos,
        hasLogro,
        fetchAllLogros,
        fetchMisLogros,
        fetchLogrosData
    }
}
