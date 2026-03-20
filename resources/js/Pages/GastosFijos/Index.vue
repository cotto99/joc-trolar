<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    activos:   Array,
    inactivos: Array,
    total:     Number,
})

const modalDesactivar = ref(false)
const gastoADesactivar = ref(null)

const formDesactivar = useForm({
    justificacion_inactivo: '',
})

function abrirModalDesactivar(gasto) {
    gastoADesactivar.value = gasto
    formDesactivar.justificacion_inactivo = ''
    modalDesactivar.value = true
}

function confirmarDesactivar() {
    formDesactivar.patch(route('gastos-fijos.desactivar', gastoADesactivar.value.id), {
        onSuccess: () => {
            modalDesactivar.value = false
            gastoADesactivar.value = null
        }
    })
}

function reactivar(id) {
    if (confirm('¿Reactivar este gasto fijo?')) {
        router.patch(route('gastos-fijos.reactivar', id))
    }
}

function eliminar(id) {
    if (confirm('¿Eliminar este gasto fijo permanentemente?')) {
        router.delete(route('gastos-fijos.destroy', id))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>📌 Gastos Fijos</template>

        <div class="max-w-5xl mx-auto">

            <!-- Total mensual -->
            <div class="bg-white rounded-xl shadow p-5 mb-6 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Total mensual en gastos fijos activos</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Q{{ Number(total).toFixed(2) }}</p>
                </div>
                <Link :href="route('gastos-fijos.create')"
                      class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium">
                    + Nuevo Gasto Fijo
                </Link>
            </div>

            <!-- Gastos Activos -->
            <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-700">✅ Gastos Activos</h3>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Nombre</th>
                            <th class="px-4 py-3 text-left">Descripción</th>
                            <th class="px-4 py-3 text-left">Monto Mensual</th>
                            <th class="px-4 py-3 text-left">Día de Pago</th>
                            <th class="px-4 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="g in activos" :key="g.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ g.nombre }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ g.descripcion ?? '—' }}</td>
                            <td class="px-4 py-3 font-bold text-gray-700">Q{{ Number(g.monto_mensual).toFixed(2) }}</td>
                            <td class="px-4 py-3 text-gray-500">Día {{ g.dia_pago }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <Link :href="route('gastos-fijos.edit', g.id)"
                                          class="text-yellow-600 hover:underline text-xs">Editar</Link>
                                    <button @click="abrirModalDesactivar(g)"
                                            class="text-orange-600 hover:underline text-xs">Desactivar</button>
                                    <button @click="eliminar(g.id)"
                                            class="text-red-600 hover:underline text-xs">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="activos.length === 0">
                            <td colspan="5" class="text-center py-8 text-gray-400">No hay gastos fijos activos.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Gastos Inactivos -->
            <div v-if="inactivos.length > 0" class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-500">⏸️ Gastos Inactivos</h3>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Nombre</th>
                            <th class="px-4 py-3 text-left">Monto Mensual</th>
                            <th class="px-4 py-3 text-left">Justificación</th>
                            <th class="px-4 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="g in inactivos" :key="g.id" class="bg-gray-50 opacity-75">
                            <td class="px-4 py-3 font-medium text-gray-500">{{ g.nombre }}</td>
                            <td class="px-4 py-3 text-gray-400 line-through">Q{{ Number(g.monto_mensual).toFixed(2) }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs italic">{{ g.justificacion_inactivo }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button @click="reactivar(g.id)"
                                            class="text-green-600 hover:underline text-xs">Reactivar</button>
                                    <button @click="eliminar(g.id)"
                                            class="text-red-600 hover:underline text-xs">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Desactivar -->
        <div v-if="modalDesactivar"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">⏸️ Desactivar Gasto Fijo</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Estás desactivando <strong>{{ gastoADesactivar?.nombre }}</strong>.
                    Debes ingresar una justificación.
                </p>
                <textarea v-model="formDesactivar.justificacion_inactivo"
                          rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 mb-1"
                          placeholder="Ej: Se canceló el contrato con el proveedor de seguridad...">
                </textarea>
                <p v-if="formDesactivar.errors.justificacion_inactivo"
                   class="text-red-500 text-xs mb-3">
                    {{ formDesactivar.errors.justificacion_inactivo }}
                </p>
                <div class="flex gap-3 mt-3">
                    <button @click="confirmarDesactivar"
                            :disabled="formDesactivar.processing"
                            class="bg-orange-500 text-white px-5 py-2 rounded-lg text-sm hover:bg-orange-600 disabled:opacity-50">
                        Confirmar Desactivación
                    </button>
                    <button @click="modalDesactivar = false"
                            class="bg-gray-100 text-gray-700 px-5 py-2 rounded-lg text-sm hover:bg-gray-200">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>