<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    candados: Array,
    stats:    Object,
})

function eliminar(id) {
    if (confirm('¿Eliminar este candado del inventario?')) {
        router.delete(route('candados.destroy', id))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>🔐 Candados</template>

        <div class="max-w-7xl mx-auto">

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-gray-700">{{ stats.total_stock }}</p>
                    <p class="text-xs text-gray-400 mt-1">Stock Total</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-red-500">{{ stats.stock_bajo }}</p>
                    <p class="text-xs text-gray-400 mt-1">Stock Bajo</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-blue-500">{{ stats.total_vendidos }}</p>
                    <p class="text-xs text-gray-400 mt-1">Unidades Vendidas</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">Q{{ Number(stats.ingresos_total).toFixed(2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Ingresos Totales</p>
                </div>
            </div>

            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Inventario de Candados</h2>
                <div class="flex gap-2">
                    <Link :href="route('ventas-candados.create')"
                          class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium">
                        🛒 Registrar Venta
                    </Link>
                    <Link :href="route('candados.create')"
                          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium">
                        + Nuevo Candado
                    </Link>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Código</th>
                            <th class="px-4 py-3 text-left">Tipo</th>
                            <th class="px-4 py-3 text-left">Precio Costo</th>
                            <th class="px-4 py-3 text-left">Precio Venta</th>
                            <th class="px-4 py-3 text-left">Stock</th>
                            <th class="px-4 py-3 text-left">Stock Mínimo</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="c in candados" :key="c.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">{{ c.codigo ?? '—' }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ c.tipo }}</td>
                            <td class="px-4 py-3 text-gray-600">Q{{ Number(c.precio_costo).toFixed(2) }}</td>
                            <td class="px-4 py-3 text-gray-600">Q{{ Number(c.precio_venta).toFixed(2) }}</td>
                            <td class="px-4 py-3">
                                <span :class="[
                                    'font-bold text-lg',
                                    c.stock <= c.stock_minimo ? 'text-red-500' : 'text-green-600'
                                ]">{{ c.stock }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ c.stock_minimo }}</td>
                            <td class="px-4 py-3">
                                <span v-if="c.stock <= c.stock_minimo"
                                      class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-medium">
                                    ⚠️ Stock Bajo
                                </span>
                                <span v-else
                                      class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-medium">
                                    ✅ OK
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <Link :href="route('candados.show', c.id)"
                                          class="text-blue-600 hover:underline text-xs">Ver</Link>
                                    <Link :href="route('candados.edit', c.id)"
                                          class="text-yellow-600 hover:underline text-xs">Editar</Link>
                                    <button @click="eliminar(c.id)"
                                            class="text-red-600 hover:underline text-xs">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="candados.length === 0">
                            <td colspan="8" class="text-center py-10 text-gray-400">
                                No hay candados en inventario.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>