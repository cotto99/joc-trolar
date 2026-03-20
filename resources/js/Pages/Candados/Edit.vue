<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
    candado: Object,
})

const form = useForm({
    codigo:       props.candado.codigo ?? '',
    tipo:         props.candado.tipo,
    precio_costo: props.candado.precio_costo,
    precio_venta: props.candado.precio_venta,
    stock:        props.candado.stock,
    stock_minimo: props.candado.stock_minimo,
})

function submit() {
    form.put(route('candados.update', props.candado.id))
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>🔐 Editar Candado</template>

        <div class="max-w-xl mx-auto">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Editar Candado</h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                            <input v-model="form.codigo" type="text"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo / Marca *</label>
                            <input v-model="form.tipo" type="text"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <p v-if="form.errors.tipo" class="text-red-500 text-xs mt-1">{{ form.errors.tipo }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio Costo (Q) *</label>
                            <input v-model="form.precio_costo" type="number" step="0.01"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio Venta (Q) *</label>
                            <input v-model="form.precio_venta" type="number" step="0.01"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
                            <input v-model="form.stock" type="number"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock Mínimo *</label>
                            <input v-model="form.stock_minimo" type="number"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium disabled:opacity-50">
                            Actualizar
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