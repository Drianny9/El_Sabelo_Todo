<template>
    <Card>
        <template #title>
            <div class="flex items-center justify-between w-full">
                <span>Gestión de Salas</span>
                <div class="flex items-center gap-2">
                    <!-- Filtro de estado -->
                    <Select
                        v-model="statusFilter"
                        :options="statusOptions"
                        option-label="label"
                        option-value="value"
                        placeholder="Filtrar por estado"
                        size="small"
                        style="min-width: 180px"
                        @change="applyFilter"
                    />
                    <Button
                        label="Actualizar"
                        icon="pi pi-refresh"
                        size="small"
                        outlined
                        severity="secondary"
                        :loading="loadingAdminRooms"
                        @click="refreshRooms"
                    />
                </div>
            </div>
        </template>

        <template #subtitle>
            Visualiza y gestiona todas las salas de juego 1vs1 creadas en el sistema.
        </template>

        <template #content>
            <DataTable
                :value="adminRooms.data || []"
                :loading="loadingAdminRooms"
                data-key="id"
                striped-rows
                size="small"
                :paginator="true"
                :rows="15"
                :total-records="adminRooms.total"
                lazy
                @page="onPage"
            >
                <!-- ID -->
                <Column field="id" header="ID" sortable class="w-[60px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loadingAdminRooms" width="3rem" height="1rem" />
                        <span v-else class="table-cell-id">{{ slotProps.data.id }}</span>
                    </template>
                </Column>

                <!-- Código de sala -->
                <Column field="code" header="Código" class="min-w-[110px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loadingAdminRooms" width="5rem" height="1rem" />
                        <Tag v-else :value="slotProps.data.code" severity="info" />
                    </template>
                </Column>

                <!-- Nombre -->
                <Column field="name" header="Nombre" sortable class="min-w-[180px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loadingAdminRooms" width="10rem" height="1rem" />
                        <div v-else class="flex items-center gap-2">
                            <i class="pi pi-home text-purple-500" />
                            <span class="font-medium">{{ slotProps.data.name || '-' }}</span>
                        </div>
                    </template>
                </Column>

                <!-- Jugador 1 -->
                <Column field="player1" header="Jugador 1" class="min-w-[150px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loadingAdminRooms" width="8rem" height="1rem" />
                        <div v-else class="flex items-center gap-1">
                            <i class="pi pi-user text-blue-500 text-xs" />
                            <span>{{ slotProps.data.player1?.name || '-' }}</span>
                        </div>
                    </template>
                </Column>

                <!-- Jugador 2 -->
                <Column field="player2" header="Jugador 2" class="min-w-[150px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loadingAdminRooms" width="8rem" height="1rem" />
                        <div v-else class="flex items-center gap-1">
                            <i class="pi pi-user text-green-500 text-xs" />
                            <span>{{ slotProps.data.player2?.name || 'Esperando...' }}</span>
                        </div>
                    </template>
                </Column>

                <!-- Marcador -->
                <Column header="Marcador" class="min-w-[120px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loadingAdminRooms" width="5rem" height="1rem" />
                        <span v-else class="font-mono font-semibold text-sm">
                            {{ slotProps.data.score_p1 ?? '-' }}
                            <span class="text-gray-400 mx-1">vs</span>
                            {{ slotProps.data.score_p2 ?? '-' }}
                        </span>
                    </template>
                </Column>

                <!-- Visibilidad -->
                <Column field="is_public" header="Visibilidad" class="w-[110px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loadingAdminRooms" width="5rem" height="1.5rem" />
                        <Tag
                            v-else
                            :value="slotProps.data.is_public ? 'Pública' : 'Privada'"
                            :severity="slotProps.data.is_public ? 'success' : 'warning'"
                            size="small"
                        />
                    </template>
                </Column>

                <!-- Estado -->
                <Column field="status" header="Estado" sortable class="min-w-[110px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loadingAdminRooms" width="6rem" height="1.5rem" />
                        <Tag
                            v-else
                            :value="statusLabel(slotProps.data.status)"
                            :severity="statusSeverity(slotProps.data.status)"
                            size="small"
                        />
                    </template>
                </Column>

                <!-- Fecha de creación -->
                <Column field="created_at" header="Creada" sortable class="min-w-[140px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loadingAdminRooms" width="8rem" height="1rem" />
                        <span v-else class="text-sm text-gray-500">{{ formatDate(slotProps.data.created_at) }}</span>
                    </template>
                </Column>

                <!-- Acciones -->
                <Column header="Acciones" class="w-[80px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loadingAdminRooms" width="3rem" height="2rem" />
                        <Button
                            v-else
                            v-tooltip.top="'Eliminar sala'"
                            icon="pi pi-trash"
                            rounded
                            text
                            severity="danger"
                            size="small"
                            @click="confirmDelete(slotProps.data)"
                        />
                    </template>
                </Column>

                <template #empty>
                    <div class="text-center py-8 text-gray-400">
                        <i class="pi pi-inbox text-4xl mb-3 block opacity-40" />
                        <p>No se encontraron salas</p>
                    </div>
                </template>
            </DataTable>
        </template>
    </Card>

    <!-- Diálogo de confirmación de eliminación -->
    <ConfirmDialog />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import useAdminRooms from '../../../composables/adminRooms'

const confirm = useConfirm()
const { adminRooms, loadingAdminRooms, getAdminRooms, deleteAdminRoom } = useAdminRooms()

const statusFilter = ref('all')
const currentPage = ref(1)

const statusOptions = [
    { label: 'Todas las salas', value: 'all' },
    { label: 'Abiertas',        value: 'open' },
    { label: 'En juego',        value: 'playing' },
    { label: 'Finalizadas',     value: 'finished' },
]

const statusLabel = (status) => {
    const map = { open: 'Abierta', playing: 'En juego', finished: 'Finalizada' }
    return map[status] || status
}

const statusSeverity = (status) => {
    const map = { open: 'success', playing: 'warn', finished: 'secondary' }
    return map[status] || 'secondary'
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('es-ES', {
        year: 'numeric', month: 'short', day: 'numeric',
        hour: '2-digit', minute: '2-digit'
    })
}

const loadRooms = (page = 1) => {
    const params = { page }
    if (statusFilter.value !== 'all') params.status = statusFilter.value
    getAdminRooms(params)
}

const refreshRooms = () => {
    currentPage.value = 1
    loadRooms(1)
}

const applyFilter = () => {
    currentPage.value = 1
    loadRooms(1)
}

const onPage = (event) => {
    currentPage.value = event.page + 1
    loadRooms(currentPage.value)
}

const confirmDelete = (room) => {
    confirm.require({
        message: `¿Seguro que deseas eliminar la sala "${room.name || room.code}"? Esta acción no se puede deshacer.`,
        header: 'Eliminar Sala',
        icon: 'pi pi-exclamation-triangle',
        rejectLabel: 'Cancelar',
        acceptLabel: 'Eliminar',
        acceptClass: 'p-button-danger',
        accept: async () => {
            await deleteAdminRoom(room.id)
        }
    })
}

onMounted(() => {
    loadRooms(1)
})
</script>
