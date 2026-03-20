<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    bitacoras: Array,
})

const coloresPrioridad = {
    alta:  'bg-red-100 text-red-700',
    media: 'bg-yellow-100 text-yellow-700',
    baja:  'bg-green-100 text-green-700',
}

const coloresTipo = {
    evento:        'bg-blue-100 text-blue-700',
    visita:        'bg-purple-100 text-purple-700',
    incidente:     'bg-red-100 text-red-700',
    mantenimiento: 'bg-orange-100 text-orange-700',
}

const iconosTipo = {
    evento:        '📅',
    visita:        '👁️',
    incidente:     '⚠️',
    mantenimiento: '🔧',
}

function eliminar(id) {
    if (confirm('¿Eliminar este registro?')) {
        router.delete(route('bitacoras.destroy', id))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>📓 Bitácoras</template>

        <div class="max-w-7xl mx-auto">

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Bitácoras</h2>
                    <p class="text-sm text-gray-500">{{ bitacoras.length }} registros en total</p>
                </div>
                <Link :href="route('bitacoras.create')"
                      class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium">
                    + Nuevo Registro
                </Link>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-left">Tipo</th>
                            <th class="px-4 py-3 text-left">Título</th>
                            <th class="px-4 py-3 text-left">Bodega</th>
                            <th class="px-4 py-3 text-left">Cliente</th>
                            <th class="px-4 py-3 text-left">Prioridad</th>
                            <th class="px-4 py-3 text-left">Registrado por</th>
                            <th class="px-4 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="b in bitacoras" :key="b.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                                {{ new Date(b.fecha_evento).toLocaleDateString('es-GT') }}
                            </td>
                            <td class="px-4 py-3">
                                <span :class="['px-2 py-1 rounded-full text-xs font-medium', coloresTipo[b.tipo]]">
                                    {{ iconosTipo[b.tipo] }} {{ b.tipo }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ b.titulo }}</td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ b.bodega ? '#' + b.bodega.numero : '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ b.cliente ? b.cliente.nombre + ' ' + b.cliente.apellido : '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <span :class="['px-2 py-1 rounded-full text-xs font-medium', coloresPrioridad[b.prioridad]]">
                                    {{ b.prioridad }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ b.user?.name }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <Link :href="route('bitacoras.show', b.id)"
                                          class="text-blue-600 hover:underline text-xs">Ver</Link>
                                    <Link :href="route('bitacoras.edit', b.id)"
                                          class="text-yellow-600 hover:underline text-xs">Editar</Link>
                                    <button @click="eliminar(b.id)"
                                            class="text-red-600 hover:underline text-xs">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="bitacoras.length === 0">
                            <td colspan="8" class="text-center py-10 text-gray-400">
                                No hay registros en la bitácora aún.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>