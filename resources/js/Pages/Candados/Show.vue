<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    candado: Object,
})

function eliminar() {
    if (confirm('¿Eliminar este candado?')) {
        router.delete(route('candados.destroy', props.candado.id))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>🔐 Detalle Candado</template>

        <div class="max-w-2xl mx-auto space-y-6">

            <!-- Info principal -->
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ candado.tipo }}</h2>
                        <p class="text-sm text-gray-400">{{ candado.codigo ?? 'Sin código' }}</p>
                    </div>
                    <div class="flex gap-2">
                        <Link :href="route('candados.edit', candado.id)"
                              class="bg-yellow-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-yellow-600">
                            ✏️ Editar
                        </Link>
                        <button @click="eliminar"
                                class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-600">
                            🗑️ Eliminar
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-400 mb-1">Precio Costo</p>
                        <p class="font-bold text-gray-700">Q{{ Number(candado.precio_costo).toFixed(2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-400 mb-1">Precio Venta</p>
                        <p class="font-bold text-green-600">Q{{ Number(candado.precio_venta).toFixed(2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-400 mb-1">Stock Actual</p>
                        <p :class="['font-bold text-2xl', candado.stock <= candado.stock_minimo ? 'text-red-500' : 'text-green-600']">
                            {{ candado.stock }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-400 mb-1">Stock Mínimo</p>
                        <p class="font-bold text-gray-700">{{ candado.stock_minimo }}</p>
                    </div>
                </div>
            </div>

            <!-- Historial de ventas -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-700 mb-4">Historial de Ventas</h3>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-3 py-2 text-left">Fecha</th>
                            <th class="px-3 py-2 text-left">Cliente</th>
                            <th class="px-3 py-2 text-left">Cantidad</th>
                            <th class="px-3 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="v in candado.ventas" :key="v.id" class="hover:bg-gray-50">
                            <td class="px-3 py-2 text-gray-500">
                                {{ new Date(v.fecha_venta).toLocaleDateString('es-GT') }}
                            </td>
                            <td class="px-3 py-2 text-gray-700">
                                {{ v.cliente ? v.cliente.nombre + ' ' + v.cliente.apellido : '—' }}
                            </td>
                            <td class="px-3 py-2 text-gray-700">{{ v.cantidad }}</td>
                            <td class="px-3 py-2 font-semibold text-green-600">Q{{ Number(v.total).toFixed(2) }}</td>
                        </tr>
                        <tr v-if="!candado.ventas || candado.ventas.length === 0">
                            <td colspan="4" class="text-center py-6 text-gray-400">Sin ventas registradas.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Link :href="route('candados.index')" class="text-sm text-blue-600 hover:underline block">
                ← Volver a Candados
            </Link>
        </div>
    </AuthenticatedLayout>
</template>