<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: { type: Boolean },
    status:           { type: String },
});

const form = useForm({
    email:    '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Mini Bodegas ASERCOM" />

    <div class="min-h-screen flex">

        <!-- Panel izquierdo — decorativo -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-slate-800 to-blue-900 flex-col items-center justify-center p-12 relative overflow-hidden">

            <!-- Círculos decorativos -->
            <div class="absolute top-[-80px] left-[-80px] w-72 h-72 bg-blue-500 opacity-10 rounded-full"></div>
            <div class="absolute bottom-[-60px] right-[-60px] w-96 h-96 bg-blue-400 opacity-10 rounded-full"></div>
            <div class="absolute top-1/2 right-[-40px] w-48 h-48 bg-blue-300 opacity-5 rounded-full"></div>

            <!-- Logo y texto -->
            <div class="relative z-10 text-center">
                <img src="/images/logo.jpeg"
                     alt="Mini Bodegas ASERCOM"
                     class="w-48 mx-auto mb-8 drop-shadow-2xl"
                     onerror="this.style.display='none'" />

                <h1 class="text-4xl font-black text-white tracking-wide mb-2">
                    MINI BODEGAS
                </h1>
                <h2 class="text-2xl font-bold text-blue-400 tracking-widest mb-6">
                    ASERCOM
                </h2>
                <div class="w-16 h-1 bg-blue-400 mx-auto mb-6 rounded-full"></div>
                <p class="text-slate-400 text-sm max-w-xs leading-relaxed">
                    Sistema de control y gestión de bodegas, contratos y clientes.
                </p>
            </div>

            <!-- Stats decorativos -->
            <div class="relative z-10 mt-12 grid grid-cols-3 gap-6 w-full max-w-sm">
                <div class="text-center">
                    <p class="text-2xl font-bold text-white">🏭</p>
                    <p class="text-xs text-slate-400 mt-1">Bodegas</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-white">📄</p>
                    <p class="text-xs text-slate-400 mt-1">Contratos</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-white">💰</p>
                    <p class="text-xs text-slate-400 mt-1">Pagos</p>
                </div>
            </div>
        </div>

        <!-- Panel derecho — formulario -->
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 p-8">
            <div class="w-full max-w-md">

                <!-- Logo móvil -->
                <div class="lg:hidden text-center mb-8">
                    <img src="/images/logo.jpeg"
                         alt="Mini Bodegas ASERCOM"
                         class="w-24 mx-auto mb-3"
                         onerror="this.style.display='none'" />
                    <h1 class="text-2xl font-black text-slate-800">MINI BODEGAS</h1>
                    <p class="text-blue-600 font-bold tracking-widest text-sm">ASERCOM</p>
                </div>

                <!-- Card del formulario -->
                <div class="bg-white rounded-2xl shadow-xl p-8">

                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-slate-800">Bienvenido 👋</h3>
                        <p class="text-slate-500 text-sm mt-1">Ingresa tus credenciales para continuar</p>
                    </div>

                    <!-- Alerta de status -->
                    <div v-if="status"
                         class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-5">

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                Correo electrónico
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">📧</span>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="correo@ejemplo.com"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    :class="{ 'border-red-400': form.errors.email }"
                                />
                            </div>
                            <InputError class="mt-1.5" :message="form.errors.email" />
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-sm font-medium text-slate-700">
                                    Contraseña
                                </label>
                                <Link v-if="canResetPassword"
                                      :href="route('password.request')"
                                      class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                    ¿Olvidaste tu contraseña?
                                </Link>
                            </div>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔒</span>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    :class="{ 'border-red-400': form.errors.password }"
                                />
                            </div>
                            <InputError class="mt-1.5" :message="form.errors.password" />
                        </div>

                        <!-- Recordarme -->
                        <div class="flex items-center gap-2">
                            <Checkbox name="remember" v-model:checked="form.remember"
                                      class="rounded border-gray-300 text-blue-600" />
                            <span class="text-sm text-slate-600">Recordar sesión</span>
                        </div>

                        <!-- Botón -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-gradient-to-r from-slate-800 to-blue-700 text-white py-3 rounded-xl font-semibold text-sm hover:from-slate-700 hover:to-blue-600 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Ingresando...</span>
                            <span v-else>Ingresar al Sistema →</span>
                        </button>

                    </form>
                </div>

                <!-- Footer -->
                <p class="text-center text-xs text-slate-400 mt-6">
                    © {{ new Date().getFullYear() }} Mini Bodegas ASERCOM · Todos los derechos reservados
                </p>
            </div>
        </div>
    </div>
</template>
