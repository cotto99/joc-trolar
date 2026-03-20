<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
    codigo:        '',
    tipo:          '',
    precio_costo:  '',
    precio_venta:  '',
    stock:         '',
    stock_minimo:  5,
})

function submit() {
    form.post(route('candados.store'))
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>🔐 Nuevo Candado</template>

        <div class="max-w-xl mx-auto">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Agregar al Inventario</h2>

                <form @submit.prevent="submit" class="space-y-4">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Código (opcional)</label>
                            <input v-model="form.codigo" type="text"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ej: CAND-001">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo / Marca *</label>
                            <input v-model="form.tipo" type="text"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ej: Master Lock 40mm">
                            <p v-if="form.errors.tipo" class="text-red-500 text-xs mt-1">{{ form.errors.tipo }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio Costo (Q) *</label>
                            <input v-model="form.precio_costo" type="number" step="0.01"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                   placeholder="0.00">
                            <p v-if="form.errors.precio_costo" class="text-red-500 text-xs mt-1">{{ form.errors.precio_costo }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio Venta (Q) *</label>
                            <input v-model="form.precio_venta" type="number" step="0.01"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                   placeholder="0.00">
                            <p v-if="form.errors.precio_venta" class="text-red-500 text-xs mt-1">{{ form.errors.precio_venta }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock Inicial *</label>
                            <input v-model="form.stock" type="number"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                   placeholder="0">
                            <p v-if="form.errors.stock" class="text-red-500 text-xs mt-1">{{ form.errors.stock }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock Mínimo *</label>
                            <input v-model="form.stock_minimo" type="number"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                   placeholder="5">
                            <p v-if="form.errors.stock_minimo" class="text-red-500 text-xs mt-1">{{ form.errors.stock_minimo }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium disabled:opacity-50">
                            Guardar Candado
                        </button>
                        <Link :href="route('candados.index')"
                              class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 text-sm">
                            Cancelar
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>