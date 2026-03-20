<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    fletes: Array,
    stats:  Object,
})

const coloresEstado = {
    pendiente:  'bg-yellow-100 text-yellow-700',
    en_camino:  'bg-blue-100 text-blue-700',
    entregado:  'bg-green-100 text-green-700',
}

const iconosEstado = {
    pendiente: '⏳',
    en_camino: '🚛',
    entregado: '✅',
}

function eliminar(id) {
    if (confirm('¿Eliminar este flete?')) {
        router.delete(route('fletes.destroy', id))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>🚛 Fletes</template>

        <div class="max-w-7xl mx-auto">

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-gray-700">{{ stats.total }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total Fletes</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-yellow-500">{{ stats.pendientes }}</p>
                    <p class="text-xs text-gray-400 mt-1">Pendientes</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-blue-500">{{ stats.en_camino }}</p>
                    <p class="text-xs text-gray-400 mt-1">En Camino</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-green-500">{{ stats.entregados }}</p>
                    <p class="text-xs text-gray-400 mt-1">Entregados</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-gray-700">Q{{ Number(stats.monto_total).toFixed(2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Monto Total</p>
                </div>
            </div>

            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Registro de Fletes</h2>
                <Link :href="route('fletes.create')"
                      class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium">
                    + Nuevo Flete
                </Link>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-left">Descripción</th>
                            <th class="px-4 py-3 text-left">Cliente</th>
                            <th class="px-4 py-3 text-left">Bodega</th>
                            <th class="px-4 py-3 text-left">Costo</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="f in fletes" :key="f.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                                {{ new Date(f.fecha_flete).toLocaleDateString('es-GT') }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ f.descripcion_carga }}</td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ f.cliente ? f.cliente.nombre + ' ' + f.cliente.apellido : '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ f.bodega ? '#' + f.bodega.numero : '—' }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-700">
                                Q{{ Number(f.costo).toFixed(2) }}
                            </td>
                            <td class="px-4 py-3">
                                <span :class="['px-2 py-1 rounded-full text-xs font-medium', coloresEstado[f.estado]]">
                                    {{ iconosEstado[f.estado] }} {{ f.estado.replace('_', ' ') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <Link :href="route('fletes.show', f.id)"
                                          class="text-blue-600 hover:underline text-xs">Ver</Link>
                                    <Link :href="route('fletes.edit', f.id)"
                                          class="text-yellow-600 hover:underline text-xs">Editar</Link>
                                    <button @click="eliminar(f.id)"
                                            class="text-red-600 hover:underline text-xs">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="fletes.length === 0">
                            <td colspan="7" class="text-center py-10 text-gray-400">
                                No hay fletes registrados aún.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>