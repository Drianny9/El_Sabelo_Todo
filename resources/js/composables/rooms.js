import { ref } from 'vue'
import axios from 'axios'

export default function useRooms() {
    //Lista de salas públicas abiertas
    const openRooms = ref([])
    const loadingRooms = ref(false)
    //Código de la sala recién creada
    const roomCode = ref('')
    //Resultados de una partida (status de la sala)
    const results = ref(null)
    //Logros recién desbloqueados en esta partida 1vs1
    const nuevosLogros = ref([])

    //Traer las salas públicas abiertas
    const fetchRooms = async () => {
        try {
            const response = await axios.get('/api/rooms')
            openRooms.value = response.data
            return response.data
        } catch (error) {
            console.error("Error al cargar las salas", error)
            throw error
        }
    }

    //Crear una nueva sala
    const createRoom = async (roomData) => {
        try {
            const response = await axios.post('/api/rooms', roomData)
            roomCode.value = response.data.code
            return response.data
        } catch (error) {
            console.error("Error al crear la sala:", error)
            throw error
        }
    }

    //Unirse a una sala por código o nombre
    const joinRoom = async (codeOrName) => {
        try {
            const response = await axios.post('/api/rooms/join', { code: codeOrName.trim() })
            return response.data
        } catch (error) {
            console.error("Error al unirse a la sala:", error)
            throw error
        }
    }

    //Consultar el estado/resultados de una sala
    const fetchRoomStatus = async (code) => {
        try {
            const response = await axios.get(`/api/rooms/${code}/status`)
            results.value = response.data

            //Capturamos los logros nuevos que vienen del getStatus (solo la primera vez)
            if (response.data.nuevos_logros && response.data.nuevos_logros.length > 0 && nuevosLogros.value.length === 0) {
                nuevosLogros.value = response.data.nuevos_logros
            }

            return response.data
        } catch (error) {
            console.error("Error al cargar resultados:", error)
            throw error
        }
    }

    return {
        openRooms,
        loadingRooms,
        roomCode,
        results,
        nuevosLogros,
        fetchRooms,
        createRoom,
        joinRoom,
        fetchRoomStatus
    }
}
