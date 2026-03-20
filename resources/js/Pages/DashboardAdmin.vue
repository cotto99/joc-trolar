<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, onMounted, watch, nextTick } from 'vue'
import { Chart, registerables } from 'chart.js'
Chart.register(...registerables)

const props = defineProps({
    filtro:                String,
    mes:                   Number,
    anio:                  Number,
    totalIngresos:         Number,
    totalEgresos:          Number,
    utilidadGeneral:       Number,
    utilidadCandados:      Number,
    ingresosPagos:         Number,
    ingresosVentasCandados:Number,
    gastosVariables:       Number,
    gastosFijos:           Number,
    costoFletes:           Number,
    totalBodegas:          Number,
    bodegasOcupadas:       Number,
    bodegasDisponibles:    Number,
    ocupacionPct:          Number,
    totalClientes:         Number,
    clientesNuevosMes:     Number,
    totalFletes:           Number,
    fletesEntregados:      Number,
    candadosVendidos:      Number,
    pagosPendientes:       Number,
    pagosAtrasados:        Number,
    alertas:               Array,
    graficaMeses:          Array,
    distribucionIngresos:  Array,
    distribucionEgresos:   Array,
    graficaBodegas:        Array,
    ultimasBitacoras:      Array,
})

const filtroActual = ref(props.filtro)
const mesActual    = ref(props.mes)
const anioActual   = ref(props.anio)

const meses = [
    { valor: 1,  label: 'Enero' },
    { valor: 2,  label: 'Febrero' },
    { valor: 3,  label: 'Marzo' },
    { valor: 4,  label: 'Abril' },
    { valor: 5,  label: 'Mayo' },
    { valor: 6,  label: 'Junio' },
    { valor: 7,  label: 'Julio' },
    { valor: 8,  label: 'Agosto' },
    { valor: 9,  label: 'Septiembre' },
    { valor: 10, label: 'Octubre' },
    { valor: 11, label: 'Noviembre' },
    { valor: 12, label: 'Diciembre' },
]

const anios = [2023, 2024, 2025, 2026]

function aplicarFiltro() {
    router.get(route('dashboard'), {
        filtro: filtroActual.value,
        mes:    mesActual.value,
        anio:   anioActual.value,
    }, { preserveScroll: true })
}

// Refs de los canvas
const chartLinea   = ref(null)
const chartDonaIng = ref(null)
const chartDonaEgr = ref(null)
const chartBodegas = ref(null)

let instancias = {}

function destruirGraficas() {
    Object.values(instancias).forEach(c => c?.destroy())
    instancias = {}
}

function iniciarGraficas() {
    destruirGraficas()

    // 1. Gráfica de líneas — Ingresos vs Egresos vs Utilidad
    if (chartLinea.value) {
        instancias.linea = new Chart(chartLinea.value, {
            type: 'line',
            data: {
                labels: props.graficaMeses.map(m => m.mes),
                datasets: [
                    {
                        label: 'Ingresos',
                        data: props.graficaMeses.map(m => m.ingresos),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                    },
                    {
                        label: 'Egresos',
                        data: props.graficaMeses.map(m => m.egresos),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                    },
                    {
                        label: 'Utilidad',
                        data: props.graficaMeses.map(m => m.utilidad),
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.05)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        borderDash: [5, 3],
                    },
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` Q${ctx.raw.toFixed(2)}`
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: { callback: v => 'Q' + v.toLocaleString() }
                    }
                }
            }
        })
    }

    // 2. Dona — Distribución de ingresos
    if (chartDonaIng.value) {
        instancias.donaIng = new Chart(chartDonaIng.value, {
            type: 'doughnut',
            data: {
                labels: props.distribucionIngresos.map(d => d.nombre),
                datasets: [{
                    data: props.distribucionIngresos.map(d => d.valor),
                    backgroundColor: ['#3b82f6', '#8b5cf6'],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` Q${ctx.raw.toFixed(2)}`
                        }
                    }
                }
            }
        })
    }

    // 3. Dona — Distribución de egresos
    if (chartDonaEgr.value) {
        instancias.donaEgr = new Chart(chartDonaEgr.value, {
            type: 'doughnut',
            data: {
                labels: props.distribucionEgresos.map(d => d.nombre),
                datasets: [{
                    data: props.distribucionEgresos.map(d => d.valor),
                    backgroundColor: ['#ef4444', '#f97316', '#eab308', '#ec4899'],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` Q${ctx.raw.toFixed(2)}`
                        }
                    }
                }
            }
        })
    }

    // 4. Barras — Estado de bodegas
    if (chartBodegas.value) {
        instancias.bodegas = new Chart(chartBodegas.value, {
            type: 'bar',
            data: {
                labels: props.graficaBodegas.map(b => b.estado),
                datasets: [{
                    label: 'Bodegas',
                    data: props.graficaBodegas.map(b => b.total),
                    backgroundColor: ['#ef4444', '#3b82f6'],
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        })
    }
}

onMounted(() => nextTick(() => iniciarGraficas()))
watch(() => props.graficaMeses, () => nextTick(() => iniciarGraficas()))

const Q = (val) => `Q${Number(val).toLocaleString('es-GT', { minimumFractionDigits: 2 })}`

const colorUtilidad = (val) => val >= 0 ? 'text-green-600' : 'text-red-500'

const iconosBitacora = {
    evento:        '📅',
    visita:        '👁️',
    incidente:     '⚠️',
    mantenimiento: '🔧',
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>📊 Dashboard</template>

        <div class="max-w-7xl mx-auto space-y-6">

            <!-- ── FILTROS ── -->
            <div class="bg-white rounded-xl shadow p-4 flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-gray-600">Filtrar por:</span>

                <div class="flex gap-2">
                    <button @click="filtroActual = 'diario'; aplicarFiltro()"
                            :class="['px-4 py-2 rounded-lg text-sm font-medium transition',
                                filtroActual === 'diario'
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">
                        📅 Hoy
                    </button>
                    <button @click="filtroActual = 'mensual'; aplicarFiltro()"
                            :class="['px-4 py-2 rounded-lg text-sm font-medium transition',
                                filtroActual === 'mensual'
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">
                        📆 Mensual
                    </button>
                </div>

                <div v-if="filtroActual === 'mensual'" class="flex gap-2">
                    <select v-model="mesActual"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option v-for="m in meses" :key="m.valor" :value="m.valor">{{ m.label }}</option>
                    </select>
                    <select v-model="anioActual"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option v-for="a in anios" :key="a" :value="a">{{ a }}</option>
                    </select>
                    <button @click="aplicarFiltro"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                        Aplicar
                    </button>
                </div>

                <span class="ml-auto text-xs text-gray-400">
                    {{ filtroActual === 'diario' ? 'Mostrando datos de hoy' : `Mostrando ${meses.find(m => m.valor === mes)?.label} ${anio}` }}
                </span>
            </div>

            <!-- ── CARDS PRINCIPALES ── -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <!-- Ingresos -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow p-5 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm opacity-80">Total Ingresos</span>
                        <span class="text-2xl">💰</span>
                    </div>
                    <p class="text-2xl font-bold">{{ Q(totalIngresos) }}</p>
                    <div class="mt-2 text-xs opacity-75 space-y-0.5">
                        <p>Rentas: {{ Q(ingresosPagos) }}</p>
                        <p>Candados: {{ Q(ingresosVentasCandados) }}</p>
                    </div>
                </div>

                <!-- Egresos -->
                <div class="bg-gradient-to-br from-red-500 to-red-700 rounded-xl shadow p-5 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm opacity-80">Total Egresos</span>
                        <span class="text-2xl">📤</span>
                    </div>
                    <p class="text-2xl font-bold">{{ Q(totalEgresos) }}</p>
                    <div class="mt-2 text-xs opacity-75 space-y-0.5">
                        <p>Fijos: {{ Q(gastosFijos) }}</p>
                        <p>Variables: {{ Q(gastosVariables) }}</p>
                    </div>
                </div>

                <!-- Utilidad General -->
                <div :class="[
                    'rounded-xl shadow p-5 text-white',
                    utilidadGeneral >= 0
                        ? 'bg-gradient-to-br from-green-500 to-green-700'
                        : 'bg-gradient-to-br from-orange-500 to-orange-700'
                ]">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm opacity-80">Utilidad General</span>
                        <span class="text-2xl">{{ utilidadGeneral >= 0 ? '📈' : '📉' }}</span>
                    </div>
                    <p class="text-2xl font-bold">{{ Q(utilidadGeneral) }}</p>
                    <p class="mt-2 text-xs opacity-75">
                        {{ utilidadGeneral >= 0 ? '✅ En positivo' : '⚠️ En negativo' }}
                    </p>
                </div>

                <!-- Utilidad Candados -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl shadow p-5 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm opacity-80">Utilidad Candados</span>
                        <span class="text-2xl">🔐</span>
                    </div>
                    <p class="text-2xl font-bold">{{ Q(utilidadCandados) }}</p>
                    <p class="mt-2 text-xs opacity-75">{{ candadosVendidos }} unidades vendidas</p>
                </div>
            </div>

            <!-- ── STATS SECUNDARIOS ── -->
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-gray-700">{{ totalBodegas }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total Bodegas</p>
                    <p class="text-xs text-blue-500 mt-0.5 font-medium">{{ ocupacionPct }}% ocupación</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-red-500">{{ bodegasOcupadas }}</p>
                    <p class="text-xs text-gray-400 mt-1">Ocupadas</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-blue-500">{{ bodegasDisponibles }}</p>
                    <p class="text-xs text-gray-400 mt-1">Disponibles</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-gray-700">{{ totalClientes }}</p>
                    <p class="text-xs text-gray-400 mt-1">Clientes</p>
                    <p class="text-xs text-green-500 mt-0.5 font-medium">+{{ clientesNuevosMes }} este mes</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p class="text-2xl font-bold text-gray-700">{{ totalFletes }}</p>
                    <p class="text-xs text-gray-400 mt-1">Fletes</p>
                    <p class="text-xs text-green-500 mt-0.5 font-medium">{{ fletesEntregados }} entregados</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <p :class="['text-2xl font-bold', pagosAtrasados > 0 ? 'text-red-500' : 'text-gray-700']">
                        {{ pagosAtrasados }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Pagos Atrasados</p>
                    <p class="text-xs text-yellow-500 mt-0.5 font-medium">{{ pagosPendientes }} pendientes</p>
                </div>
            </div>

            <!-- ── GRÁFICA PRINCIPAL + DONA INGRESOS ── -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 bg-white rounded-xl shadow p-5">
                    <h3 class="font-semibold text-gray-700 mb-4">📈 Ingresos vs Egresos — Últimos 6 meses</h3>
                    <canvas ref="chartLinea" height="100"></canvas>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <h3 class="font-semibold text-gray-700 mb-4">💰 Distribución de Ingresos</h3>
                    <canvas ref="chartDonaIng"></canvas>
                    <div class="mt-3 space-y-1">
                        <div v-for="d in distribucionIngresos" :key="d.nombre"
                             class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ d.nombre }}</span>
                            <span class="font-semibold text-gray-700">{{ Q(d.valor) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── DONA EGRESOS + BARRAS BODEGAS ── -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow p-5">
                    <h3 class="font-semibold text-gray-700 mb-4">📤 Distribución de Egresos</h3>
                    <div class="flex gap-6 items-center">
                        <div class="flex-1">
                            <canvas ref="chartDonaEgr"></canvas>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div v-for="d in distribucionEgresos" :key="d.nombre">
                                <p class="text-gray-500 text-xs">{{ d.nombre }}</p>
                                <p class="font-bold text-gray-700">{{ Q(d.valor) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-5">
                    <h3 class="font-semibold text-gray-700 mb-4">🏢 Estado de Bodegas</h3>
                    <canvas ref="chartBodegas" height="180"></canvas>
                    <div class="mt-3">
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-500">Ocupación</span>
                            <span class="font-semibold text-gray-700">{{ ocupacionPct }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-red-500 h-3 rounded-full transition-all duration-500"
                                 :style="{ width: ocupacionPct + '%' }"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── ALERTAS + BITÁCORAS ── -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Alertas de pagos -->
                <div class="bg-white rounded-xl shadow p-5">
                    <h3 class="font-semibold text-gray-700 mb-4">⚠️ Alertas de Pagos</h3>
                    <div v-if="alertas.length > 0" class="space-y-2">
                        <div v-for="a in alertas" :key="a.id"
                             class="flex items-center justify-between bg-yellow-50 border border-yellow-200 rounded-lg px-3 py-2">
                            <div>
                                <p class="text-sm font-medium text-gray-700">
                                    {{ a.contrato?.cliente?.nombre }} {{ a.contrato?.cliente?.apellido }}
                                </p>
                                <p class="text-xs text-gray-400">Bodega #{{ a.contrato?.bodega?.numero }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-yellow-600">{{ Q(a.monto) }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ new Date(a.periodo_mes).toLocaleDateString('es-GT') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-8 text-gray-400">
                        <p class="text-3xl mb-2">✅</p>
                        <p class="text-sm">Sin pagos próximos a vencer</p>
                    </div>
                </div>

                <!-- Últimas bitácoras -->
                <div class="bg-white rounded-xl shadow p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-700">📓 Últimas Bitácoras</h3>
                        <Link :href="route('bitacoras.index')"
                              class="text-xs text-blue-600 hover:underline">Ver todas</Link>
                    </div>
                    <div v-if="ultimasBitacoras.length > 0" class="space-y-2">
                        <div v-for="b in ultimasBitacoras" :key="b.id"
                             class="flex items-start gap-3 py-2 border-b border-gray-100 last:border-0">
                            <span class="text-xl">{{ iconosBitacora[b.tipo] }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-700 truncate">{{ b.titulo }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ b.bodega ? 'Bodega #' + b.bodega.numero : '' }}
                                    · {{ b.user?.name }}
                                </p>
                            </div>
                            <p class="text-xs text-gray-400 whitespace-nowrap">
                                {{ new Date(b.fecha_evento).toLocaleDateString('es-GT') }}
                            </p>
                        </div>
                    </div>
                    <div v-else class="text-center py-8 text-gray-400">
                        <p class="text-sm">Sin registros recientes</p>
                    </div>
                </div>
            </div>

            <!-- ── RESUMEN FINANCIERO ── -->
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold text-gray-700 mb-4">📋 Resumen Financiero del Período</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-xs text-gray-400 mb-1">Ingresos por Rentas</p>
                        <p class="text-lg font-bold text-blue-600">{{ Q(ingresosPagos) }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-xs text-gray-400 mb-1">Ingresos por Candados</p>
                        <p class="text-lg font-bold text-purple-600">{{ Q(ingresosVentasCandados) }}</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4">
                        <p class="text-xs text-gray-400 mb-1">Gastos Fijos</p>
                        <p class="text-lg font-bold text-red-500">{{ Q(gastosFijos) }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-4">
                        <p class="text-xs text-gray-400 mb-1">Gastos Variables</p>
                        <p class="text-lg font-bold text-orange-500">{{ Q(gastosVariables) }}</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <p class="text-xs text-gray-400 mb-1">Costo Fletes</p>
                        <p class="text-lg font-bold text-yellow-600">{{ Q(costoFletes) }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-xs text-gray-400 mb-1">Utilidad Candados</p>
                        <p class="text-lg font-bold text-green-600">{{ Q(utilidadCandados) }}</p>
                    </div>
                    <div class="md:col-span-2 rounded-lg p-4"
                         :class="utilidadGeneral >= 0 ? 'bg-green-100' : 'bg-red-100'">
                        <p class="text-xs text-gray-500 mb-1">UTILIDAD NETA DEL PERÍODO</p>
                        <p :class="['text-2xl font-bold', utilidadGeneral >= 0 ? 'text-green-700' : 'text-red-600']">
                            {{ Q(utilidadGeneral) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ utilidadGeneral >= 0 ? '📈 El negocio está generando ganancias' : '📉 Los egresos superan los ingresos' }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>