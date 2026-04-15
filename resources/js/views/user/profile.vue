<template>
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- Avatar Section -->
        <div class="col-span-1 md:col-span-4 lg:col-span-3">
            <Card>
                <template #title>Avatar</template>
                <template #content>
                    <div class="flex flex-col items-center">
                        <!-- File Upload -->
                        <FileUpload name="picture" url="/api/users/updateimg" @before-upload="onBeforeUpload"
                            @upload="onTemplatedUpload($event)" accept="image/*" :maxFileSize="1500000"
                            @select="onSelectedFiles" mode="basic" :auto="true" chooseLabel="Cambiar Avatar"
                            class="w-full" />

                        <div class="mt-4 w-full flex justify-center">
                            <Avatar :image="user.avatar || 'https://bootdey.com/img/Content/avatar/avatar7.png'"
                                class="w-32 h-32" size="xlarge" shape="circle" />
                        </div>
                    </div>
                </template>
            </Card>
        </div>

        <!-- Personal Data Section -->
        <div class="col-span-1 md:col-span-8 lg:col-span-9">
            <Card>
                <template #title>Datos Personales</template>
                <template #content>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="field">
                            <label class="font-bold block mb-2">Nombre</label>
                            <div
                                class="p-3 bg-surface-50 dark:bg-surface-800 rounded border border-surface-200 dark:border-surface-700">
                                {{ user.name }}
                            </div>
                        </div>

                        <div class="field">
                            <label class="font-bold block mb-2">Email</label>
                            <div
                                class="p-3 bg-surface-50 dark:bg-surface-800 rounded border border-surface-200 dark:border-surface-700">
                                {{ user.email }}
                            </div>
                        </div>

                        <div class="field">
                            <label class="font-bold block mb-2">Primer Apellido</label>
                            <div
                                class="p-3 bg-surface-50 dark:bg-surface-800 rounded border border-surface-200 dark:border-surface-700">
                                {{ user.surname1 || '-' }}
                            </div>
                        </div>

                        <div class="field">
                            <label class="font-bold block mb-2">Segundo Apellido</label>
                            <div
                                class="p-3 bg-surface-50 dark:bg-surface-800 rounded border border-surface-200 dark:border-surface-700">
                                {{ user.surname2 || '-' }}
                            </div>
                        </div>
                    </div>
                </template>
            </Card>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { usePrimeVue } from 'primevue/config';
import useUsers from "@/composables/users";
import { authStore } from "@/store/auth";

const auth = authStore();
const $primevue = usePrimeVue();
const { getUser, user } = useUsers();

onMounted(() => {
    getUser(auth.user.id)
})

const onBeforeUpload = (event) => {
    event.formData.append('id', user.value.id)
};

const onTemplatedUpload = (event) => {
    // Recargar usuario para actualizar avatar
    getUser(auth.user.id);
};

const onSelectedFiles = (event) => {
    // Lógica adicional si es necesaria
};
</script>
