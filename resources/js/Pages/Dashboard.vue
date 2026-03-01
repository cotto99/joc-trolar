<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    bodegas: Array,
    alertas: Array,
    stats:   Object,
})

const modalBodega     = ref(null)
const modalDisponible = ref(null)

function verBodega(bodega) {
    if (bodega.estado === 'ocupada') {
        modalBodega.value = bodega
    } else {
        modalDisponible.value = bodega
    }
}

function irACrearContrato(bodega) {
    router.visit(route('contratos.index'), {
        data: { bodega_id: bodega.id }
    })
}

function fmt(val) {
    return new Intl.NumberFormat('es-GT', { style: 'currency', currency: 'GTQ' }).format(val || 0)
}
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-bold text-slate-800">🏠 Dashboard</h1>
        </template>

        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <p class="text-2xl font-bold text-slate-700">{{ stats.total_bodegas }}</p>
                <p class="text-xs text-gray-500 mt-1">Total Bodegas</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center border-t-4 border-red-500">
                <p class="text-2xl font-bold text-red-600">{{ stats.ocupadas }}</p>
                <p class="text-xs text-gray-500 mt-1">Ocupadas</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center border-t-4 border-blue-500">
                <p class="text-2xl font-bold text-blue-600">{{ stats.disponibles }}</p>
                <p class="text-xs text-gray-500 mt-1">Disponibles</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center border-t-4 border-yellow-500">
                <p class="text-2xl font-bold text-yellow-600">{{ stats.pagos_pendientes }}</p>
                <p class="text-xs text-gray-500 mt-1">Pagos Pendientes</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center border-t-4 border-orange-500 col-span-2 lg:col-span-1">
                <p class="text-2xl font-bold text-orange-600">{{ stats.alertas }}</p>
                <p class="text-xs text-gray-500 mt-1">Alertas</p>
            </div>
        </div>

        <!-- Alertas -->
        <div v-if="alertas.length" class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6">
            <h2 class="font-bold text-orange-800 mb-3">⚠️ Pagos próximos a vencer (5 días)</h2>
            <div class="space-y-2">
                <div v-for="a in alertas" :key="a.id"
                     class="bg-white rounded-lg p-3 flex flex-col lg:flex-row lg:items-center justify-between gap-2 shadow-sm">
                    <div>
                        <span class="font-semibold text-gray-800">{{ a.cliente?.nombre }} {{ a.cliente?.apellido }}</span>
                        <span class="text-gray-500 text-sm ml-2">— Bodega {{ a.bodega?.numero }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-mono font-bold text-orange-700">{{ fmt(a.monto) }}</span>
                        <span class="text-xs px-2 py-1 rounded-full"
                              :class="new Date(a.fecha_vencimiento) < new Date()
                                ? 'bg-red-100 text-red-700'
                                : 'bg-orange-100 text-orange-700'">
                            Vence: {{ a.fecha_vencimiento }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Bodegas -->
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-700">🏭 Bodegas</h2>
            <div class="flex gap-4 text-sm">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span> Ocupada</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span> Disponible</span>
            </div>
        </div>

        <div v-if="!bodegas.length" class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
            <p class="text-4xl mb-3">🏭</p>
            <p class="font-medium">No hay bodegas registradas</p>
            <p class="text-sm mt-1">Andá a <a :href="route('bodegas.index')" class="text-blue-500 underline">Bodegas</a> para crear la primera</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            <button v-for="bodega in bodegas" :key="bodega.id"
                    @click="verBodega(bodega)"
                    class="rounded-xl shadow p-4 text-center transition-all hover:scale-105 hover:shadow-lg cursor-pointer border-2"
                    :class="bodega.estado === 'ocupada'
                        ? 'bg-red-500 border-red-600 text-white'
                        : 'bg-blue-500 border-blue-600 text-white'">
                <p class="text-3xl font-bold">{{ bodega.numero }}</p>
                <p class="text-xs mt-1 opacity-90">{{ bodega.nombre || 'Bodega' }}</p>
                <p class="text-xs mt-2 font-semibold opacity-75">
                    {{ bodega.estado === 'ocupada' ? '🔴 Ocupada' : '🔵 Disponible' }}
                </p>
                <p v-if="bodega.estado === 'ocupada' && bodega.contrato_activo?.cliente" class="text-xs mt-1 opacity-90 truncate">
                    {{ bodega.contrato_activo.cliente.nombre }}
                </p>
            </button>
        </div>

        <!-- Modal bodega OCUPADA -->
        <div v-if="modalBodega" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
                <div class="bg-red-500 text-white p-5 rounded-t-xl">
                    <h3 class="text-xl font-bold">🏭 Bodega {{ modalBodega.numero }}</h3>
                    <p class="text-red-100 text-sm">{{ modalBodega.nombre }}</p>
                </div>
                <div class="p-5 space-y-3">
                    <div v-if="modalBodega.contrato_activo">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-3">Contrato Activo</p>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Cliente:</span>
                                <span class="font-semibold">
                                    {{ modalBodega.contrato_activo.cliente?.nombre }}
                                    {{ modalBodega.contrato_activo.cliente?.apellido }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">N° Contrato:</span>
                                <span class="font-mono">{{ modalBodega.contrato_activo.numero_contrato }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Monto:</span>
                                <span class="font-bold text-green-600">{{ fmt(modalBodega.contrato_activo.monto) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Periodicidad:</span>
                                <span class="capitalize">{{ modalBodega.contrato_activo.periodicidad }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Vigencia:</span>
                                <span>{{ modalBodega.contrato_activo.fecha_inicio }} al {{ modalBodega.contrato_activo.fecha_fin }}</span>
                            </div>
                            <div v-if="modalBodega.contrato_activo.pagos?.length" class="flex justify-between">
                                <span class="text-gray-500">Próximo pago:</span>
                                <span class="font-semibold text-orange-600">
                                    {{ modalBodega.contrato_activo.pagos[0]?.fecha_vencimiento }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-5 border-t flex gap-3">
                    <button @click="modalBodega = null"
                            class="flex-1 border border-gray-300 text-gray-600 py-2 rounded-lg text-sm hover:bg-gray-50">
                        Cerrar
                    </button>
                    <a :href="route('contratos.index')"
                       class="flex-1 bg-slate-700 text-white py-2 rounded-lg text-sm hover:bg-slate-800 text-center">
                        Ver Contratos
                    </a>
                </div>
            </div>
        </div>

        <!-- Modal bodega DISPONIBLE -->
        <div v-if="modalDisponible" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-sm">
                <div class="bg-blue-500 text-white p-5 rounded-t-xl">
                    <h3 class="text-xl font-bold">🏭 Bodega {{ modalDisponible.numero }}</h3>
                    <p class="text-blue-100 text-sm">{{ modalDisponible.nombre || 'Disponible' }}</p>
                </div>
                <div class="p-5 text-center">
                    <p class="text-4xl mb-3">🔵</p>
                    <p class="font-semibold text-gray-700">Esta bodega está disponible</p>
                    <p class="text-sm text-gray-500 mt-1">¿Querés crear un contrato para esta bodega?</p>
                </div>
                <div class="p-5 border-t flex gap-3">
                    <button @click="modalDisponible = null"
                            class="flex-1 border border-gray-300 text-gray-600 py-2 rounded-lg text-sm hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button @click="irACrearContrato(modalDisponible)"
                            class="flex-1 bg-blue-500 text-white py-2 rounded-lg text-sm hover:bg-blue-600">
                        + Crear Contrato
                    </button>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>