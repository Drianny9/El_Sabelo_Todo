import { ref } from 'vue'
import axios from 'axios'

export default function useRanking() {
    //Lista completa del ranking
    const ranking = ref([])
    //Los 3 primeros jugadores (para el podio de la Home)
    const topJugadores = ref([])
    const loading = ref(false)

    //Pedir el ranking completo
    const fetchRanking = async () => {
        loading.value = true
        try {
            const response = await axios.get('/api/ranking')

            // Si usas UserResource::collection, la ruta es response.data.data
            // Si devuelves User::all(), la ruta suele ser solo response.data
            if (response.data.data) {
                ranking.value = response.data.data
            } else {
                ranking.value = response.data
            }

            return ranking.value
        } catch (error) {
            console.error("Error cargando el ranking:", error)
            throw error
        } finally {
            loading.value = false
        }
    }

    //Pedir solo el top 3 de jugadores para el podio
    const fetchTopJugadores = async () => {
        try {
            const respuesta = await axios.get('/api/ranking')
            const data = Array.isArray(respuesta.data) ? respuesta.data : respuesta.data.data ?? []
            topJugadores.value = data.slice(0, 3)
            return topJugadores.value
        } catch (error) {
            console.warn('No se pudo cargar el ranking:', error)
            throw error
        }
    }

    return {
        ranking,
        topJugadores,
        loading,
        fetchRanking,
        fetchTopJugadores
    }
}
