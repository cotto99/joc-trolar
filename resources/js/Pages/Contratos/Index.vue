<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Head, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
    contratos: Object,
    clientes:  Array,
    bodegas:   Array,
})

const mostrarForm        = ref(false)
const mostrarModalCliente = ref(false)

const form = useForm({
    cliente_id:    '',
    bodega_id:     '',
    fecha_inicio:  new Date().toISOString().split('T')[0],
    fecha_fin:     '',
    monto:         '',
    periodicidad:  'mensual',
    dia_pago:      1,
    observaciones: '',
})

const formCliente = useForm({
    nombre:    '',
    apellido:  '',
    telefono:  '',
    email:     '',
    dpi:       '',
    nit:       '',
    direccion: '',
    notas:     '',
})

function guardar() {
    form.post(route('contratos.store'), {
        onSuccess: () => {
            mostrarForm.value = false
            form.reset()
        }
    })
}

function guardarCliente() {
    formCliente.post(route('clientes.store'), {
        onSuccess: () => {
            mostrarModalCliente.value = false
            formCliente.reset()
            // Recargar la página para que aparezca el nuevo cliente
            router.reload({ only: ['clientes'] })
        }
    })
}

function cancelarContrato(contrato) {
    if (confirm(`¿Cancelar el contrato ${contrato.numero_contrato}? Esta acción liberará la bodega.`))
        router.patch(route('contratos.cancelar', contrato.id))
}

function fmt(val) {
    return new Intl.NumberFormat('es-GT', { style: 'currency', currency: 'GTQ' }).format(val || 0)
}

const estadoClass = {
    activo:    'bg-green-100 text-green-700',
    vencido:   'bg-red-100 text-red-700',
    cancelado: 'bg-gray-100 text-gray-500',
}
</script>
<template>
    <Head title="Contratos" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-bold text-slate-800">📄 Contratos</h1>
        </template>

        <!-- Botón nuevo -->
        <div class="mb-6">
            <button @click="mostrarForm = !mostrarForm"
                    class="bg-slate-700 text-white px-6 py-2 rounded-lg hover:bg-slate-800 text-sm font-medium">
                {{ mostrarForm ? '✕ Cancelar' : '+ Nuevo Contrato' }}
            </button>
        </div>

        <!-- Formulario -->
        <div v-if="mostrarForm" class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="font-bold text-gray-700 mb-4">Crear Contrato</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
    <label class="block text-sm text-gray-600 mb-1">Cliente *</label>
    <div class="flex gap-2">
        <select v-model="form.cliente_id"
                class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            <option value="">Seleccionar cliente...</option>
            <option v-for="c in clientes" :key="c.id" :value="c.id">
                {{ c.nombre }} {{ c.apellido }}
            </option>
        </select>
        <button type="button" @click="mostrarModalCliente = true"
                class="bg-blue-500 text-white px-3 py-2 rounded-lg hover:bg-blue-600 text-sm whitespace-nowrap">
            + Nuevo
        </button>
    </div>
    <p v-if="form.errors.cliente_id" class="text-red-500 text-xs mt-1">{{ form.errors.cliente_id }}</p>
    <p v-if="!clientes.length" class="text-orange-500 text-xs mt-1">
        ⚠️ No hay clientes registrados. Creá uno con el botón "+ Nuevo"
    </p>
</div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Bodega *</label>
                    <select v-model="form.bodega_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar bodega...</option>
                        <option v-for="b in bodegas" :key="b.id" :value="b.id">
                            Bodega {{ b.numero }} {{ b.nombre ? '- ' + b.nombre : '' }}
                        </option>
                    </select>
                    <p v-if="form.errors.bodega_id" class="text-red-500 text-xs mt-1">{{ form.errors.bodega_id }}</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Monto (GTQ) *</label>
                    <input v-model="form.monto" type="number" step="0.01" placeholder="0.00"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                    <p v-if="form.errors.monto" class="text-red-500 text-xs mt-1">{{ form.errors.monto }}</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Fecha Inicio *</label>
                    <input v-model="form.fecha_inicio" type="date"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Fecha Fin *</label>
                    <input v-model="form.fecha_fin" type="date"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                    <p v-if="form.errors.fecha_fin" class="text-red-500 text-xs mt-1">{{ form.errors.fecha_fin }}</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Periodicidad *</label>
                    <select v-model="form.periodicidad"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="mensual">Mensual</option>
                        <option value="trimestral">Trimestral</option>
                        <option value="semestral">Semestral</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Día de pago *</label>
                    <input v-model.number="form.dia_pago" type="number" min="1" max="28"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                    <p class="text-xs text-gray-400 mt-1">Día del mes (1-28)</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Observaciones</label>
                    <textarea v-model="form.observaciones" rows="2"
                              class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <div class="flex justify-end">
                <button @click="guardar" :disabled="form.processing"
                        class="bg-green-600 text-white px-8 py-2 rounded-lg hover:bg-green-700 font-medium disabled:opacity-50">
                    💾 Crear Contrato
                </button>
            </div>
        </div>

        <!-- Tabla contratos -->
        <div class="bg-white rounded-xl shadow overflow-hidden overflow-x-auto">
            <table class="w-full text-sm min-w-[800px]">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">N° Contrato</th>
                        <th class="text-left px-4 py-3">Cliente</th>
                        <th class="text-left px-4 py-3">Bodega</th>
                        <th class="text-left px-4 py-3">Vigencia</th>
                        <th class="text-left px-4 py-3">Periodicidad</th>
                        <th class="text-right px-4 py-3">Monto</th>
                        <th class="text-center px-4 py-3">Estado</th>
                        <th class="text-center px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="c in contratos.data" :key="c.id" class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ c.numero_contrato }}</td>
                        <td class="px-4 py-3 font-medium">{{ c.cliente?.nombre }} {{ c.cliente?.apellido }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-bold">
                                {{ c.bodega?.numero }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ c.fecha_inicio }} → {{ c.fecha_fin }}
                        </td>
                        <td class="px-4 py-3 capitalize text-gray-500">{{ c.periodicidad }}</td>
                        <td class="px-4 py-3 text-right font-mono font-bold text-green-600">{{ fmt(c.monto) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span :class="estadoClass[c.estado]"
                                  class="px-2 py-1 rounded-full text-xs font-semibold capitalize">
                                {{ c.estado }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex gap-2 justify-center">
                                <a :href="route('contratos.descargar', c.id)"
                                   class="text-blue-500 hover:underline text-xs">📄 PDF</a>
                                <button v-if="c.estado === 'activo'"
                                        @click="cancelarContrato(c)"
                                        class="text-red-400 hover:underline text-xs">Cancelar</button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!contratos.data?.length">
                        <td colspan="8" class="text-center py-8 text-gray-400">No hay contratos registrados</td>
                    </tr>
                </tbody>
            </table>
            <!-- Paginación -->
            <div v-if="contratos.last_page > 1" class="px-4 py-3 border-t flex gap-2 justify-end text-sm flex-wrap">
                <a v-for="p in contratos.links" :key="p.label"
                   :href="p.url" v-html="p.label"
                   class="px-3 py-1 rounded border"
                   :class="p.active ? 'bg-slate-700 text-white' : 'text-gray-600 hover:bg-gray-50'" />
            </div>
        </div>
        <!-- Modal nuevo cliente -->
<div v-if="mostrarModalCliente"
     class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">

        <!-- Header -->
        <div class="bg-slate-700 text-white p-5 rounded-t-xl flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg">👤 Nuevo Cliente</h3>
                <p class="text-slate-300 text-sm mt-0.5">El cliente quedará seleccionado automáticamente</p>
            </div>
            <button @click="mostrarModalCliente = false"
                    class="text-slate-300 hover:text-white text-2xl leading-none">✕</button>
        </div>

        <!-- Formulario -->
        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Nombre *</label>
                <input v-model="formCliente.nombre" type="text"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                <p v-if="formCliente.errors.nombre" class="text-red-500 text-xs mt-1">{{ formCliente.errors.nombre }}</p>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Apellido *</label>
                <input v-model="formCliente.apellido" type="text"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                <p v-if="formCliente.errors.apellido" class="text-red-500 text-xs mt-1">{{ formCliente.errors.apellido }}</p>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Teléfono</label>
                <input v-model="formCliente.telefono" type="text"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input v-model="formCliente.email" type="email"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">DPI</label>
                <input v-model="formCliente.dpi" type="text"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">NIT</label>
                <input v-model="formCliente.nit" type="text"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Dirección</label>
                <input v-model="formCliente.direccion" type="text"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Notas</label>
                <textarea v-model="formCliente.notas" rows="2"
                          class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
        </div>

        <!-- Botones -->
        <div class="p-5 border-t flex gap-3">
            <button @click="mostrarModalCliente = false"
                    class="flex-1 border border-gray-300 text-gray-600 py-2 rounded-lg text-sm hover:bg-gray-50">
                Cancelar
            </button>
            <button @click="guardarCliente" :disabled="formCliente.processing"
                    class="flex-1 bg-slate-700 text-white py-2 rounded-lg text-sm hover:bg-slate-800 disabled:opacity-50">
                {{ formCliente.processing ? 'Guardando...' : '✓ Guardar Cliente' }}
            </button>
        </div>
    </div>
</div>
    </AuthenticatedLayout>
</template>