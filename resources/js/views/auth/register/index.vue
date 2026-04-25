<template>
    <div class="relative min-h-screen bg-gradient-to-b from-purple-900 via-indigo-900 to-purple-900 text-white flex items-center justify-center p-4 overflow-hidden pt-12 pb-12">
        
        <!-- Elementos 3D de fondo -->
        <img src="/images/Home/Rayo.webp" alt="Rayo" class="absolute top-10 right-10 w-32 md:w-48 opacity-40 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />
        <img src="/images/Home/Corazon_lila.webp" alt="Corazon" class="absolute bottom-10 left-10 w-40 md:w-56 opacity-30 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />
        
        <div class="relative w-full max-w-2xl bg-white text-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.5)] rounded-[3rem] border-8 border-purple-300/30 overflow-hidden z-10 flex flex-col">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-100 to-white px-8 py-6 relative border-b-2 border-purple-200 text-center">
                <h2 class="text-3xl font-black sans-serif text-purple-700 tracking-wider uppercase drop-shadow-sm">
                    {{ $t('register') }}
                </h2>
                <p class="mt-2 text-sm text-purple-500 font-bold uppercase tracking-widest">
                    Crea tu cuenta de jugador
                </p>
            </div>

            <!-- Formulario -->
            <div class="p-8 md:p-10 flex flex-col bg-gray-50/50">
                <form @submit.prevent="submitRegister" class="space-y-6">
                    <!-- Name -->
                    <div class="flex flex-col gap-2">
                        <label for="name" class="text-gray-600 font-black uppercase tracking-wider text-sm">{{ $t('name') }}</label>
                        <InputText
                            id="name"
                            v-model="registerForm.name"
                            placeholder="Nombre completo"
                            class="w-full bg-white text-gray-900 border-2 border-gray-200 focus:border-purple-500 rounded-xl py-3 px-4 shadow-inner text-lg font-bold placeholder:font-normal placeholder:text-gray-400 transition-colors"
                            :class="{ 'border-red-500 bg-red-50': validationErrors?.name }"
                        />
                        <small v-if="validationErrors?.name" class="text-red-500 font-bold">
                            {{ validationErrors.name[0] }}
                        </small>
                    </div>

                    <!-- Surname1 y Surname2 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="surname1" class="text-gray-600 font-black uppercase tracking-wider text-sm">{{ $t('surname1') }}</label>
                            <InputText
                                id="surname1"
                                v-model="registerForm.surname1"
                                placeholder="Primer apellido"
                                class="w-full bg-white text-gray-900 border-2 border-gray-200 focus:border-purple-500 rounded-xl py-3 px-4 shadow-inner text-lg font-bold placeholder:font-normal placeholder:text-gray-400 transition-colors"
                                :class="{ 'border-red-500 bg-red-50': validationErrors?.surname1 }"
                            />
                            <small v-if="validationErrors?.surname1" class="text-red-500 font-bold">
                                {{ validationErrors.surname1[0] }}
                            </small>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="surname2" class="text-gray-600 font-black uppercase tracking-wider text-sm">{{ $t('surname2') }}</label>
                            <InputText
                                id="surname2"
                                v-model="registerForm.surname2"
                                placeholder="Segundo apellido"
                                class="w-full bg-white text-gray-900 border-2 border-gray-200 focus:border-purple-500 rounded-xl py-3 px-4 shadow-inner text-lg font-bold placeholder:font-normal placeholder:text-gray-400 transition-colors"
                                :class="{ 'border-red-500 bg-red-50': validationErrors?.surname2 }"
                            />
                            <small v-if="validationErrors?.surname2" class="text-red-500 font-bold">
                                {{ validationErrors.surname2[0] }}
                            </small>
                        </div>
                    </div>

                    <!-- Alias y Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="alias" class="text-gray-600 font-black uppercase tracking-wider text-sm">{{ $t('Alias') }}</label>
                            <InputText
                                id="alias"
                                v-model="registerForm.alias"
                                placeholder="Tu alias de jugador"
                                class="w-full bg-white text-gray-900 border-2 border-gray-200 focus:border-purple-500 rounded-xl py-3 px-4 shadow-inner text-lg font-bold placeholder:font-normal placeholder:text-gray-400 transition-colors"
                                :class="{ 'border-red-500 bg-red-50': validationErrors?.alias }"
                            />
                            <small v-if="validationErrors?.alias" class="text-red-500 font-bold">
                                {{ validationErrors.alias[0] }}
                            </small>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="email" class="text-gray-600 font-black uppercase tracking-wider text-sm">{{ $t('email') }}</label>
                            <InputText
                                id="email"
                                type="email"
                                v-model="registerForm.email"
                                placeholder="tu@email.com"
                                class="w-full bg-white text-gray-900 border-2 border-gray-200 focus:border-purple-500 rounded-xl py-3 px-4 shadow-inner text-lg font-bold placeholder:font-normal placeholder:text-gray-400 transition-colors"
                                :class="{ 'border-red-500 bg-red-50': validationErrors?.email }"
                            />
                            <small v-if="validationErrors?.email" class="text-red-500 font-bold">
                                {{ validationErrors.email[0] }}
                            </small>
                        </div>
                    </div>

                    <!-- Password y Confirm Password -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="password" class="text-gray-600 font-black uppercase tracking-wider text-sm">{{ $t('password') }}</label>
                            <Password
                                id="password"
                                v-model="registerForm.password"
                                placeholder="••••••••"
                                toggleMask
                                :feedback="false"
                                :pt="{
                                    root: { class: 'w-full' },
                                    input: { class: 'w-full bg-white text-gray-900 border-2 border-gray-200 focus:border-purple-500 rounded-xl py-3 px-4 shadow-inner text-lg font-bold placeholder:font-normal placeholder:text-gray-400 transition-colors' + (validationErrors?.password ? ' border-red-500 bg-red-50' : '') }
                                }"
                                fluid
                            />
                            <small v-if="validationErrors?.password" class="text-red-500 font-bold">
                                {{ validationErrors.password[0] }}
                            </small>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="password_confirmation" class="text-gray-600 font-black uppercase tracking-wider text-sm">{{ $t('confirm_password') }}</label>
                            <Password
                                id="password_confirmation"
                                v-model="registerForm.password_confirmation"
                                placeholder="••••••••"
                                toggleMask
                                :feedback="false"
                                :pt="{
                                    root: { class: 'w-full' },
                                    input: { class: 'w-full bg-white text-gray-900 border-2 border-gray-200 focus:border-purple-500 rounded-xl py-3 px-4 shadow-inner text-lg font-bold placeholder:font-normal placeholder:text-gray-400 transition-colors' + (validationErrors?.password_confirmation ? ' border-red-500 bg-red-50' : '') }
                                }"
                                fluid
                            />
                            <small v-if="validationErrors?.password_confirmation" class="text-red-500 font-bold">
                                {{ validationErrors.password_confirmation[0] }}
                            </small>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 pt-6 border-t-2 border-gray-100">
                        <button
                            type="submit"
                            :disabled="processing"
                            class="w-full bg-gradient-to-b from-blue-400 to-blue-500 hover:from-blue-300 hover:to-blue-400 text-blue-950 font-black text-xl py-4 rounded-full border-4 border-blue-300/50 shadow-[0_6px_0_#1d4ed8] hover:shadow-[0_4px_0_#1d4ed8] hover:translate-y-0.5 active:shadow-none active:translate-y-2 transition-all duration-150 uppercase tracking-wider flex items-center justify-center gap-3"
                        >
                            <span v-if="!processing">{{ $t('register') }}</span>
                            <i v-if="!processing" class="pi pi-user-plus font-black"></i>
                            <i v-else class="pi pi-spin pi-spinner text-2xl"></i>
                        </button>
                    </div>

                    <!-- Login link -->
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-500 font-bold">
                            ¿Ya tienes una cuenta?
                            <router-link
                                :to="{ name: 'auth.login' }"
                                class="font-black text-purple-600 hover:text-purple-800 transition-colors uppercase tracking-wide ml-1"
                            >
                                Inicia sesión aquí
                            </router-link>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import useAuth from '@/composables/auth';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';

const { registerForm, validationErrors, processing, submitRegister } = useAuth();
</script>

<style scoped>
:deep(.p-password) {
    width: 100%;
}
:deep(.p-password-input) {
    width: 100%;
}
</style>
