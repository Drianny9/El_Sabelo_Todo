<template>
    <div class="relative min-h-screen bg-gradient-to-b from-purple-900 via-indigo-900 to-purple-900 text-white flex items-center justify-center p-4 overflow-hidden">
        
        <!-- Elementos 3D de fondo -->
        <img src="/images/Home/Rayo.webp" alt="Rayo" class="absolute top-10 left-10 w-32 md:w-48 opacity-40 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />
        <img src="/images/Home/Corazon_lila.webp" alt="Corazon" class="absolute bottom-10 right-10 w-40 md:w-56 opacity-30 select-none pointer-events-none drop-shadow-2xl mix-blend-screen" />
        
        <div class="relative w-full max-w-md bg-white text-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.5)] rounded-[3rem] border-8 border-purple-300/30 overflow-hidden z-10 flex flex-col">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-100 to-white px-8 py-6 relative border-b-2 border-purple-200 text-center">
                <h2 class="text-3xl font-black sans-serif text-purple-700 tracking-wider uppercase drop-shadow-sm">
                    El Sabelotodo
                </h2>
                <p class="mt-2 text-sm text-purple-500 font-bold uppercase tracking-widest">
                    Inicia sesión para jugar
                </p>
            </div>

            <!-- Formulario -->
            <div class="p-8 md:p-10 flex flex-col bg-gray-50/50">
                <form @submit.prevent="submitLogin" class="space-y-6">
                    <!-- Email -->
                    <div class="flex flex-col gap-2">
                        <label for="email" class="text-gray-600 font-black uppercase tracking-wider text-sm">{{ $t('email') }}</label>
                        <InputText
                            id="email"
                            type="email"
                            v-model="loginForm.email"
                            placeholder="tu@email.com"
                            class="w-full bg-white text-gray-900 border-2 border-gray-200 focus:border-purple-500 rounded-xl py-3 px-4 shadow-inner text-lg font-bold placeholder:font-normal placeholder:text-gray-400 transition-colors"
                            :class="{ 'border-red-500 bg-red-50': validationErrors?.email }"
                        />
                        <small v-if="validationErrors?.email" class="text-red-500 font-bold">
                            <div v-for="message in validationErrors.email" :key="message">
                                {{ message }}
                            </div>
                        </small>
                    </div>

                    <!-- Password -->
                    <div class="flex flex-col gap-2">
                        <label for="password" class="text-gray-600 font-black uppercase tracking-wider text-sm">{{ $t('password') }}</label>
                        <Password
                            id="password"
                            v-model="loginForm.password"
                            placeholder="••••••••"
                            :toggleMask="true"
                            :feedback="false"
                            :pt="{
                                root: { class: 'w-full' },
                                input: { class: 'w-full bg-white text-gray-900 border-2 border-gray-200 focus:border-purple-500 rounded-xl py-3 px-4 shadow-inner text-lg font-bold placeholder:font-normal placeholder:text-gray-400 transition-colors' + (validationErrors?.password ? ' border-red-500 bg-red-50' : '') }
                            }"
                            fluid
                        />
                        <small v-if="validationErrors?.password" class="text-red-500 font-bold">
                            <div v-for="message in validationErrors.password" :key="message">
                                {{ message }}
                            </div>
                        </small>
                    </div>

                    <!-- Remember me y Forgot password -->
                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center gap-3">
                            <Checkbox
                                v-model="loginForm.remember"
                                inputId="remember"
                                binary
                            />
                            <label for="remember" class="text-sm cursor-pointer font-bold text-gray-600 uppercase tracking-wide">
                                {{ $t('remember_me') }}
                            </label>
                        </div>
                        <router-link
                            :to="{ name: 'auth.forgot-password' }"
                            class="text-sm font-black text-purple-600 hover:text-purple-800 transition-colors uppercase tracking-wide"
                        >
                            {{ $t('forgot_password') }}
                        </router-link>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 pt-6 border-t-2 border-gray-100">
                        <button
                            type="submit"
                            :disabled="processing"
                            class="w-full bg-gradient-to-b from-yellow-300 to-yellow-500 hover:from-yellow-200 hover:to-yellow-400 text-yellow-900 font-black text-xl py-4 rounded-full border-4 border-yellow-200/50 shadow-[0_6px_0_#b45309] hover:shadow-[0_4px_0_#b45309] hover:translate-y-0.5 active:shadow-none active:translate-y-2 transition-all duration-150 uppercase tracking-wider flex items-center justify-center gap-3"
                        >
                            <span v-if="!processing">{{ $t('login') }}</span>
                            <i v-if="!processing" class="pi pi-sign-in font-black"></i>
                            <i v-else class="pi pi-spin pi-spinner text-2xl"></i>
                        </button>
                    </div>

                    <!-- Register link -->
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-500 font-bold">
                            ¿No tienes una cuenta?
                            <router-link
                                :to="{ name: 'auth.register' }"
                                class="font-black text-blue-500 hover:text-blue-700 transition-colors uppercase tracking-wide ml-1"
                            >
                                Regístrate aquí
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
import Checkbox from 'primevue/checkbox';

const { loginForm, validationErrors, processing, submitLogin } = useAuth();
</script>

<style scoped>
/* Utilidades y ajustes para PrimeVue si son necesarios */
:deep(.p-password) {
    width: 100%;
}
:deep(.p-password-input) {
    width: 100%;
}
</style>
