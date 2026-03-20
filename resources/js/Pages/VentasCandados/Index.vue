<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    ventas: Array,
    stats:  Object,
})

function cancelar(id) {
    if (confirm('¿Cancelar esta venta? El stock será restaurado.')) {
        router.delete(route('ventas-candados.destroy', id))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>🛒 Ventas de Candados</template>

        <div class="max-w-7xl mx-auto">

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-gray-700">{{ stats.total_ventas }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total Ventas</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-blue-500">{{ stats.total_unidades }}</p>
                    <p class="text-xs text-gray-400 mt-1">Unidades Vendidas</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">Q{{ Number(stats.total_ingresos).toFixed(2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total Ingresos</p>
                </div>
            </div>

            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Historial de Ventas</h2>
                <Link :href="route('ventas-candados.create')"
                      class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium">
                    + Nueva Venta
                </Link>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-left">Candado</th>
                            <th class="px-4 py-3 text-left">Cliente</th>
                            <th class="px-4 py-3 text-left">Contrato</th>
                            <th class="px-4 py-3 text-left">Cantidad</th>
                            <th class="px-4 py-3 text-left">Precio Unit.</th>
                            <th class="px-4 py-3 text-left">Total</th>
                            <th class="px-4 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="v in ventas" :key="v.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                                {{ new Date(v.fecha_venta).toLocaleDateString('es-GT') }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ v.candado?.tipo ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ v.cliente ? v.cliente.nombre + ' ' + v.cliente.apellido : '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ v.contrato ? 'Bodega #' + v.contrato.bodega?.numero : '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-700 font-medium">{{ v.cantidad }}</td>
                            <td class="px-4 py-3 text-gray-600">Q{{ Number(v.precio_unitario).toFixed(2) }}</td>
                            <td class="px-4 py-3 font-bold text-green-600">Q{{ Number(v.total).toFixed(2) }}</td>
                            <td class="px-4 py-3">
                                <button @click="cancelar(v.id)"
                                        class="text-red-600 hover:underline text-xs">
                                    Cancelar
                                </button>
                            </td>
                        </tr>
                        <tr v-if="ventas.length === 0">
                            <td colspan="8" class="text-center py-10 text-gray-400">
                                No hay ventas registradas aún.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>