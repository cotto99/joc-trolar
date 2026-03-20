<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
    categorias: Array,
})

const form = useForm({
    descripcion:  '',
    monto:        '',
    fecha_gasto:  '',
    categoria:    '',
    comprobante:  '',
})

function submit() {
    form.post(route('gastos-variables.store'))
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>📊 Nuevo Gasto Variable</template>

        <div class="max-w-xl mx-auto">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Registrar Gasto Variable</h2>

                <form @submit.prevent="submit" class="space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                        <input v-model="form.descripcion" type="text"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                               placeholder="Ej: Compra de escobas y detergente">
                        <p v-if="form.errors.descripcion" class="text-red-500 text-xs mt-1">{{ form.errors.descripcion }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Monto (Q) *</label>
                            <input v-model="form.monto" type="number" step="0.01"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                   placeholder="0.00">
                            <p v-if="form.errors.monto" class="text-red-500 text-xs mt-1">{{ form.errors.monto }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                            <input v-model="form.fecha_gasto" type="date"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <p v-if="form.errors.fecha_gasto" class="text-red-500 text-xs mt-1">{{ form.errors.fecha_gasto }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                            <select v-model="form.categoria"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">Sin categoría</option>
                                <option v-for="cat in categorias" :key="cat" :value="cat">
                                    {{ cat }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Comprobante / Factura</label>
                            <input v-model="form.comprobante" type="text"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ej: FAC-0001">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium disabled:opacity-50">
                            Guardar Gasto
                        </button>
                        <Link :href="route('gastos-variables.index')"
                              class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 text-sm">
                            Cancelar
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>