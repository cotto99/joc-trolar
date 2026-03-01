<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({ pagos: Object, mes: String })

const pagarModal       = ref(null)
const pagoSeleccionado = ref(null)
const fechaPago        = ref(new Date().toISOString().split('T')[0])
const notas            = ref('')
const procesando       = ref(false)
const filtroEstado     = ref('todos')
const mesSeleccionado  = ref(props.mes || new Date().toISOString().slice(0, 7))

function cambiarMes() {
    router.get(route('pagos.index'), { mes: mesSeleccionado.value }, { preserveState: true })
}

function abrirPagar(p) {
    pagarModal.value = p.id
    pagoSeleccionado.value = p
    fechaPago.value = new Date().toISOString().split('T')[0]
    notas.value = ''
}

function confirmarPago() {
    if (!pagoSeleccionado.value) return
    procesando.value = true
    router.patch(
        route('pagos.pagar', pagoSeleccionado.value.id),
        { fecha_pago: fechaPago.value, notas: notas.value },
        {
            preserveScroll: true,
            onSuccess: () => {
                pagarModal.value = null
                pagoSeleccionado.value = null
                procesando.value = false
            },
            onError: () => { procesando.value = false }
        }
    )
}

function fmt(val) {
    return new Intl.NumberFormat('es-GT', { style: 'currency', currency: 'GTQ' }).format(val || 0)
}

function diasRestantes(fecha) {
    const hoy  = new Date()
    const venc = new Date(fecha)
    return Math.ceil((venc - hoy) / (1000 * 60 * 60 * 24))
}

function estadoPagoClass(pago) {
    if (pago.estado === 'pagado') return 'bg-green-100 text-green-700'
    const dias = diasRestantes(pago.fecha_vencimiento)
    if (dias < 0)  return 'bg-red-100 text-red-700'
    if (dias <= 5) return 'bg-orange-100 text-orange-700'
    return 'bg-yellow-100 text-yellow-700'
}

function etiquetaEstado(pago) {
    if (pago.estado === 'pagado') return '✅ Pagado'
    const dias = diasRestantes(pago.fecha_vencimiento)
    if (dias < 0)   return `⛔ Vencido (${Math.abs(dias)}d)`
    if (dias === 0) return '🔴 Vence hoy'
    if (dias <= 5)  return `⚠️ Vence en ${dias}d`
    return '⏳ Pendiente'
}
</script>

<template>
    <Head title="Pagos" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-bold text-slate-800">💰 Pagos</h1>
        </template>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">

                <!-- Selector de mes -->
                <div class="flex items-center gap-3">
                    <label class="text-sm font-medium text-gray-600 whitespace-nowrap">📅 Mes:</label>
                    <input type="month" v-model="mesSeleccionado" @change="cambiarMes"
                           class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                </div>

                <!-- Divisor -->
                <div class="hidden md:block w-px h-8 bg-gray-200"></div>

                <!-- Filtro estado -->
                <div class="flex gap-2 flex-wrap">
                    <button v-for="f in ['todos', 'pendiente', 'pagado', 'vencido']" :key="f"
                            @click="filtroEstado = f"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium capitalize transition-colors"
                            :class="filtroEstado === f
                                ? 'bg-slate-700 text-white'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                        <span v-if="f === 'todos'">📋 Todos</span>
                        <span v-else-if="f === 'pendiente'">⏳ Pendientes</span>
                        <span v-else-if="f === 'pagado'">✅ Pagados</span>
                        <span v-else-if="f === 'vencido'">⛔ Vencidos</span>
                    </button>
                </div>

                <!-- Resumen rápido -->
                <div class="md:ml-auto flex gap-3 text-xs">
                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full font-medium">
                        ⏳ {{ pagos.data?.filter(p => p.estado === 'pendiente').length }} pendientes
                    </span>
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">
                        ✅ {{ pagos.data?.filter(p => p.estado === 'pagado').length }} pagados
                    </span>
                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full font-medium">
                        ⛔ {{ pagos.data?.filter(p => p.estado === 'vencido').length }} vencidos
                    </span>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="bg-white rounded-xl shadow overflow-hidden overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">Cliente</th>
                        <th class="text-left px-4 py-3">Bodega</th>
                        <th class="text-left px-4 py-3">Contrato</th>
                        <th class="text-center px-4 py-3">Vencimiento</th>
                        <th class="text-right px-4 py-3">Monto</th>
                        <th class="text-center px-4 py-3">Estado</th>
                        <th class="text-center px-4 py-3">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="p in pagos.data" :key="p.id">
                        <tr v-if="filtroEstado === 'todos' || p.estado === filtroEstado"
                            class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">
                                {{ p.cliente?.nombre }} {{ p.cliente?.apellido }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-bold">
                                    {{ p.bodega?.numero }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-500">
                                {{ p.contrato?.numero_contrato }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600">
                                {{ p.fecha_vencimiento }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-bold text-green-600">
                                {{ fmt(p.monto) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span :class="estadoPagoClass(p)"
                                      class="px-2 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                    {{ etiquetaEstado(p) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button v-if="p.estado !== 'pagado'"
                                        @click="abrirPagar(p)"
                                        class="bg-green-500 text-white px-3 py-1 rounded-lg text-xs hover:bg-green-600">
                                    ✓ Pagar
                                </button>
                                <span v-else class="text-gray-400 text-xs">{{ p.fecha_pago }}</span>
                            </td>
                        </tr>
                    </template>
                    <tr v-if="!pagos.data?.length">
                        <td colspan="7" class="text-center py-8 text-gray-400">
                            No hay pagos para {{ mesSeleccionado }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Paginación -->
            <div v-if="pagos.last_page > 1" class="px-4 py-3 border-t flex gap-2 justify-end text-sm flex-wrap">
                <a v-for="p in pagos.links" :key="p.label"
                   :href="p.url" v-html="p.label"
                   class="px-3 py-1 rounded border"
                   :class="p.active ? 'bg-slate-700 text-white' : 'text-gray-600 hover:bg-gray-50'" />
            </div>
        </div>

        <!-- Modal pago -->
        <div v-if="pagarModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
                <div class="bg-green-600 text-white p-5 rounded-t-xl">
                    <h3 class="font-bold text-lg">💰 Registrar Pago</h3>
                    <p class="text-green-100 text-sm mt-1">
                        {{ pagoSeleccionado?.cliente?.nombre }}
                        {{ pagoSeleccionado?.cliente?.apellido }}
                        — Bodega {{ pagoSeleccionado?.bodega?.numero }}
                    </p>
                </div>
                <div class="p-5 space-y-4">
                    <div class="bg-green-50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-500">Monto a cobrar</p>
                        <p class="text-2xl font-bold text-green-600">{{ fmt(pagoSeleccionado?.monto) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Fecha de pago *</label>
                        <input v-model="fechaPago" type="date"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Notas</label>
                        <textarea v-model="notas" rows="2"
                                  class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                  placeholder="Número de recibo, transferencia, etc."></textarea>
                    </div>
                </div>
                <div class="p-5 border-t flex gap-3">
                    <button @click="pagarModal = null"
                            class="flex-1 border border-gray-300 text-gray-600 py-2 rounded-lg text-sm hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button @click="confirmarPago()" :disabled="procesando"
                            class="flex-1 bg-green-600 text-white py-2 rounded-lg text-sm hover:bg-green-700 disabled:opacity-50">
                        {{ procesando ? 'Guardando...' : '✓ Confirmar Pago' }}
                    </button>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>