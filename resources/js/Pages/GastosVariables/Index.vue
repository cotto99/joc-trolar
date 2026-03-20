<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    gastos: Array,
    stats:  Object,
})

function eliminar(id) {
    if (confirm('¿Eliminar este gasto?')) {
        router.delete(route('gastos-variables.destroy', id))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>📊 Gastos Variables</template>

        <div class="max-w-7xl mx-auto">

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-blue-600">Q{{ Number(stats.total_mes).toFixed(2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Gasto del Mes Actual</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-gray-700">Q{{ Number(stats.total_general).toFixed(2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total General</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-gray-700">{{ gastos.length }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total Registros</p>
                </div>
            </div>

            <!-- Resumen por categoría -->
            <div v-if="Object.keys(stats.categorias).length > 0"
                 class="bg-white rounded-xl shadow p-5 mb-6">
                <h3 class="font-semibold text-gray-700 mb-3">📂 Por Categoría</h3>
                <div class="flex flex-wrap gap-2">
                    <div v-for="(monto, categoria) in stats.categorias" :key="categoria"
                         class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        <span class="text-gray-600 font-medium">{{ categoria ?? 'Sin categoría' }}</span>
                        <span class="ml-2 text-blue-600 font-bold">Q{{ Number(monto).toFixed(2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Header tabla -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Registro de Gastos</h2>
                <Link :href="route('gastos-variables.create')"
                      class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium">
                    + Nuevo Gasto
                </Link>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-left">Descripción</th>
                            <th class="px-4 py-3 text-left">Categoría</th>
                            <th class="px-4 py-3 text-left">Comprobante</th>
                            <th class="px-4 py-3 text-left">Monto</th>
                            <th class="px-4 py-3 text-left">Registrado por</th>
                            <th class="px-4 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="g in gastos" :key="g.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                                {{ new Date(g.fecha_gasto).toLocaleDateString('es-GT') }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ g.descripcion }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">
                                    {{ g.categoria ?? 'Sin categoría' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ g.comprobante ?? '—' }}</td>
                            <td class="px-4 py-3 font-bold text-red-600">Q{{ Number(g.monto).toFixed(2) }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ g.user?.name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <Link :href="route('gastos-variables.edit', g.id)"
                                          class="text-yellow-600 hover:underline text-xs">Editar</Link>
                                    <button @click="eliminar(g.id)"
                                            class="text-red-600 hover:underline text-xs">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="gastos.length === 0">
                            <td colspan="7" class="text-center py-10 text-gray-400">
                                No hay gastos variables registrados.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>