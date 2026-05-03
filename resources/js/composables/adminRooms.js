import { ref } from 'vue'
import axios from 'axios'

export default function useAdminRooms() {
    const adminRooms = ref({ data: [], total: 0, current_page: 1, last_page: 1 })
    const loadingAdminRooms = ref(false)

    // Obtener todas las salas (paginadas) con filtro opcional de estado
    const getAdminRooms = async (params = {}) => {
        loadingAdminRooms.value = true
        try {
            const response = await axios.get('/api/admin/rooms', { params })
            adminRooms.value = response.data
            return response.data
        } catch (error) {
            console.error('Error al cargar las salas:', error)
            throw error
        } finally {
            loadingAdminRooms.value = false
        }
    }

    // Eliminar una sala por ID
    const deleteAdminRoom = async (id) => {
        try {
            await axios.delete(`/api/admin/rooms/${id}`)
            // Eliminar localmente sin recargar
            if (adminRooms.value.data) {
                adminRooms.value.data = adminRooms.value.data.filter(r => r.id !== id)
                adminRooms.value.total = Math.max(0, adminRooms.value.total - 1)
            }
        } catch (error) {
            console.error('Error al eliminar la sala:', error)
            throw error
        }
    }

    return {
        adminRooms,
        loadingAdminRooms,
        getAdminRooms,
        deleteAdminRoom
    }
}
