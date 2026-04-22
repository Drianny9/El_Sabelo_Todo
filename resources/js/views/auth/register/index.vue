<template>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full">
            <!-- Logo y título -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold">
                    {{ $t('register') }}
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Regístrate para comenzar
                </p>
            </div>

            <!-- Formulario -->
            <Card>
                <template #content>
                    <form @submit.prevent="submitRegister" class="space-y-6">
                        <!-- Name -->
                        <div class="flex flex-col gap-2">
                            <label for="name" class="font-medium">{{ $t('name') }}</label>
                            <InputText
                                id="name"
                                v-model="registerForm.name"
                                placeholder="Nombre completo"
                                :invalid="!!validationErrors?.name"
                            />
                            <small v-if="validationErrors?.name" class="text-red-500">
                                {{ validationErrors.name[0] }}
                            </small>
                        </div>

                        <!-- Surname1 y Surname2 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="surname1" class="font-medium">{{ $t('surname1') }}</label>
                                <InputText
                                    id="surname1"
                                    v-model="registerForm.surname1"
                                    placeholder="Primer apellido"
                                    :invalid="!!validationErrors?.surname1"
                                />
                                <small v-if="validationErrors?.surname1" class="text-red-500">
                                    {{ validationErrors.surname1[0] }}
                                </small>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="surname2" class="font-medium">{{ $t('surname2') }}</label>
                                <InputText
                                    id="surname2"
                                    v-model="registerForm.surname2"
                                    placeholder="Segundo apellido"
                                    :invalid="!!validationErrors?.surname2"
                                />
                                <small v-if="validationErrors?.surname2" class="text-red-500">
                                    {{ validationErrors.surname2[0] }}
                                </small>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                                <label for="alias" class="font-medium">{{ $t('Alias') }}</label>
                                <InputText
                                    id="alias"
                                    v-model="registerForm.alias"
                                    placeholder="Alias"
                                    :invalid="!!validationErrors?.alias"
                                />
                                <small v-if="validationErrors?.alias" class="text-red-500">
                                    {{ validationErrors.alias[0] }}
                                </small>
                            </div>

                        <!-- Email -->
                        <div class="flex flex-col gap-2">
                            <label for="email" class="font-medium">{{ $t('email') }}</label>
                            <InputText
                                id="email"
                                type="email"
                                v-model="registerForm.email"
                                placeholder="tu@email.com"
                                :invalid="!!validationErrors?.email"
                            />
                            <small v-if="validationErrors?.email" class="text-red-500">
                                {{ validationErrors.email[0] }}
                            </small>
                        </div>

                        <!-- Password y Confirm Password -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="password" class="font-medium">{{ $t('password') }}</label>
                                <Password
                                    id="password"
                                    v-model="registerForm.password"
                                    placeholder="••••••••"
                                    toggleMask
                                    :feedback="false"
                                    :invalid="!!validationErrors?.password"
                                    fluid
                                />
                                <small v-if="validationErrors?.password" class="text-red-500">
                                    {{ validationErrors.password[0] }}
                                </small>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="password_confirmation" class="font-medium">{{ $t('confirm_password') }}</label>
                                <Password
                                    id="password_confirmation"
                                    v-model="registerForm.password_confirmation"
                                    placeholder="••••••••"
                                    toggleMask
                                    :feedback="false"
                                    :invalid="!!validationErrors?.password_confirmation"
                                    fluid
                                />
                                <small v-if="validationErrors?.password_confirmation" class="text-red-500">
                                    {{ validationErrors.password_confirmation[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <Button
                            type="submit"
                            :label="$t('register')"
                            :loading="processing"
                            :disabled="processing"
                            class="w-full"
                            size="large"
                        />

                        <!-- Login link -->
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                ¿Ya tienes una cuenta?
                                <router-link
                                    :to="{ name: 'auth.login' }"
                                    class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                >
                                    Inicia sesión aquí
                                </router-link>
                            </p>
                        </div>
                    </form>
                </template>
            </Card>
        </div>
    </div>
</template>

<script setup>
import useAuth from '@/composables/auth';

const { registerForm, validationErrors, processing, submitRegister } = useAuth();
</script>
