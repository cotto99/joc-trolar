<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    flete: Object,
})

const coloresEstado = {
    pendiente: 'bg-yellow-100 text-yellow-700',
    en_camino: 'bg-blue-100 text-blue-700',
    entregado: 'bg-green-100 text-green-700',
}

function eliminar() {
    if (confirm('¿Eliminar este flete?')) {
        router.delete(route('fletes.destroy', props.flete.id))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>🚛 Detalle Flete</template>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow p-6">

                <!-- Encabezado -->
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ flete.descripcion_carga }}</h2>
                        <span :class="['mt-2 inline-block px-2 py-1 rounded-full text-xs font-medium', coloresEstado[flete.estado]]">
                            {{ flete.estado.replace('_', ' ') }}
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <Link :href="route('fletes.edit', flete.id)"
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
                        <p class="text-xs text-gray-400 mb-1">Fecha</p>
                        <p class="font-semibold text-gray-700">
                            {{ new Date(flete.fecha_flete).toLocaleDateString('es-GT') }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-400 mb-1">Costo</p>
                        <p class="font-semibold text-gray-700">Q{{ Number(flete.costo).toFixed(2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-400 mb-1">Cliente</p>
                        <p class="font-semibold text-gray-700">
                            {{ flete.cliente ? flete.cliente.nombre + ' ' + flete.cliente.apellido : '—' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-400 mb-1">Bodega</p>
                        <p class="font-semibold text-gray-700">
                            {{ flete.bodega ? '#' + flete.bodega.numero : '—' }}
                        </p>
                    </div>
                </div>

                <!-- Notas -->
                <div v-if="flete.notas" class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-xs text-gray-400 mb-1">Notas</p>
                    <p class="text-gray-700">{{ flete.notas }}</p>
                </div>

                <Link :href="route('fletes.index')" class="text-sm text-blue-600 hover:underline">
                    ← Volver a Fletes
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>