<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
    nombre:        '',
    descripcion:   '',
    monto_mensual: '',
    dia_pago:      1,
})

function submit() {
    form.post(route('gastos-fijos.store'))
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>📌 Nuevo Gasto Fijo</template>

        <div class="max-w-xl mx-auto">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Registrar Gasto Fijo</h2>

                <form @submit.prevent="submit" class="space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input v-model="form.nombre" type="text"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                               placeholder="Ej: Agua, Luz, Seguridad">
                        <p v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción (opcional)</label>
                        <textarea v-model="form.descripcion" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                  placeholder="Detalle adicional del gasto..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Monto Mensual (Q) *</label>
                            <input v-model="form.monto_mensual" type="number" step="0.01"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                   placeholder="0.00">
                            <p v-if="form.errors.monto_mensual" class="text-red-500 text-xs mt-1">{{ form.errors.monto_mensual }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Día de pago *</label>
                            <input v-model="form.dia_pago" type="number" min="1" max="28"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <p v-if="form.errors.dia_pago" class="text-red-500 text-xs mt-1">{{ form.errors.dia_pago }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium disabled:opacity-50">
                            Guardar
                        </button>
                        <Link :href="route('gastos-fijos.index')"
                              class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 text-sm">
                            Cancelar
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>