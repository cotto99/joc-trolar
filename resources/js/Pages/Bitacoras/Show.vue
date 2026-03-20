<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    bitacora: Object,
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

function eliminar() {
    if (confirm('¿Eliminar este registro de bitácora?')) {
        router.delete(route('bitacoras.destroy', props.bitacora.id))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>📓 Detalle Bitácora</template>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow p-6">

                <!-- Encabezado -->
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span :class="['px-2 py-1 rounded-full text-xs font-medium', coloresTipo[bitacora.tipo]]">
                                {{ iconosTipo[bitacora.tipo] }} {{ bitacora.tipo }}
                            </span>
                            <span :class="['px-2 py-1 rounded-full text-xs font-medium', coloresPrioridad[bitacora.prioridad]]">
                                Prioridad {{ bitacora.prioridad }}
                            </span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">{{ bitacora.titulo }}</h2>
                    </div>
                    <div class="flex gap-2">
                        <Link :href="route('bitacoras.edit', bitacora.id)"
                              class="bg-yellow-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-yellow-600">
                            ✏️ Editar
                        </Link>
                        <button @click="eliminar"
                                class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-600">
                            🗑️ Eliminar
                        </button>
                    </div>
                </div>

                <!-- Info -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-400 mb-1">Fecha del evento</p>
                        <p class="font-semibold text-gray-700">
                            {{ new Date(bitacora.fecha_evento).toLocaleString('es-GT') }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-400 mb-1">Registrado por</p>
                        <p class="font-semibold text-gray-700">{{ bitacora.user?.name ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-400 mb-1">Bodega</p>
                        <p class="font-semibold text-gray-700">
                            {{ bitacora.bodega ? '#' + bitacora.bodega.numero : '—' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-400 mb-1">Cliente</p>
                        <p class="font-semibold text-gray-700">
                            {{ bitacora.cliente ? bitacora.cliente.nombre + ' ' + bitacora.cliente.apellido : '—' }}
                        </p>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-xs text-gray-400 mb-2">Descripción</p>
                    <p class="text-gray-700 whitespace-pre-line">{{ bitacora.descripcion }}</p>
                </div>

                <!-- Volver -->
                <Link :href="route('bitacoras.index')"
                      class="text-sm text-blue-600 hover:underline">
                    ← Volver a Bitácoras
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>