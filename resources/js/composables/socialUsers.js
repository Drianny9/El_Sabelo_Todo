import { ref } from 'vue'
import axios from 'axios'

export default function useSocialUsers() {
    const socialUsers = ref([])
    const loading = ref(false)

    const fetchSocialUsers = async () => {
        loading.value = true
        try {
            const { data } = await axios.get('/api/social/users')
            socialUsers.value = data
        } catch (e) {
            console.warn('No se pudieron cargar usuarios sociales:', e)
        } finally {
            loading.value = false
        }
    }

    return { socialUsers, loading, fetchSocialUsers }
}
