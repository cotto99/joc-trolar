<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import Swal from 'sweetalert2'

const props = defineProps({ bodegas: Array })

const form = useForm({
    numero: '',
    nombre: '',
    tamanio_m2: '',
    descripcion: '',
})

const editando = ref(null)
const formEdit = useForm({
    numero: '',
    nombre: '',
    tamanio_m2: '',
    descripcion: '',
})

function guardar() {
    form.post(route('bodegas.store'), { onSuccess: () => form.reset() })
}

function iniciarEdicion(b) {
    editando.value = b.id
    formEdit.numero = b.numero
    formEdit.nombre = b.nombre
    formEdit.tamanio_m2 = b.tamanio_m2
    formEdit.descripcion = b.descripcion
}

function guardarEdicion(b) {
    formEdit.put(route('bodegas.update', b.id), {
        onSuccess: () => editando.value = null
    })
}

async function eliminar(bodega) {
    if (bodega.estado === 'ocupada') {
        const result = await Swal.fire({
            title: '⚠️ Bodega Ocupada',
            html: `
                <p class="text-gray-600 mb-3">La bodega <strong>#${bodega.numero}</strong> tiene un cliente asignado.</p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-left text-sm text-red-700 space-y-1">
                    <p>📋 Se eliminara del sistema:</p>
                    <p>• Cliente asignado</p>
                    <p>• Contrato activo</p>
                    <p>• Historial de pagos</p>
                </div>
                
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
        })

        if (result.isConfirmed) {
            router.delete(route('bodegas.destroy', bodega.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: '¡Eliminada!',
                        text: `La bodega #${bodega.numero} fue eliminada del sistema.`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                    })
                }
            })
        }
    } else {
        const result = await Swal.fire({
            title: '¿Eliminar bodega?',
            text: `La bodega #${bodega.numero} será eliminada del sistema.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
        })

        if (result.isConfirmed) {
            router.delete(route('bodegas.destroy', bodega.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: '¡Eliminada!',
                        text: `La bodega #${bodega.numero} fue eliminada.`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                    })
                }
            })
        }
    }
}

</script>

<template>
    <Head title="Bodegas" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-bold text-slate-800">🏭 Bodegas</h1>
        </template>

        <!-- Formulario nuevo -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="font-bold text-gray-700 mb-4">Nueva Bodega</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Número *</label>
                    <input v-model="form.numero" type="text" placeholder="Ej: 01, A1, B2"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                    <p v-if="form.errors.numero" class="text-red-500 text-xs mt-1">{{ form.errors.numero }}</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nombre</label>
                    <input v-model="form.nombre" type="text" placeholder="Ej: Bodega Norte"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Tamaño (m²)</label>
                    <input v-model="form.tamanio_m2" type="number" step="0.01" placeholder="0.00"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Descripción</label>
                    <input v-model="form.descripcion" type="text" placeholder="Opcional"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>
            <button @click="guardar" :disabled="form.processing"
                    class="mt-4 bg-slate-700 text-white px-6 py-2 rounded-lg hover:bg-slate-800 text-sm font-medium disabled:opacity-50">
                + Agregar Bodega
            </button>
        </div>

        <!-- Stats rápidas -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <p class="text-2xl font-bold text-slate-700">{{ bodegas.length }}</p>
                <p class="text-xs text-gray-500">Total</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center border-t-4 border-red-500">
                <p class="text-2xl font-bold text-red-600">{{ bodegas.filter(b => b.estado === 'ocupada').length }}</p>
                <p class="text-xs text-gray-500">Ocupadas</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center border-t-4 border-blue-500">
                <p class="text-2xl font-bold text-blue-600">{{ bodegas.filter(b => b.estado === 'disponible').length }}</p>
                <p class="text-xs text-gray-500">Disponibles</p>
            </div>
        </div>

        <!-- Tabla -->
        <div class="bg-white rounded-xl shadow overflow-hidden overflow-x-auto">
            <table class="w-full text-sm min-w-[600px]">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">N°</th>
                        <th class="text-left px-4 py-3">Nombre</th>
                        <th class="text-left px-4 py-3">Tamaño</th>
                        <th class="text-left px-4 py-3">Descripción</th>
                        <th class="text-center px-4 py-3">Estado</th>
                        <th class="text-left px-4 py-3">Cliente Actual</th>
                        <th class="text-center px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="b in bodegas" :key="b.id" class="border-t hover:bg-gray-50">
                        <template v-if="editando === b.id">
                            <td class="px-4 py-2"><input v-model="formEdit.numero" class="border rounded px-2 py-1 w-16 text-sm" /></td>
                            <td class="px-4 py-2"><input v-model="formEdit.nombre" class="border rounded px-2 py-1 w-full text-sm" /></td>
                            <td class="px-4 py-2"><input v-model="formEdit.tamanio_m2" type="number" class="border rounded px-2 py-1 w-20 text-sm" /></td>
                            <td class="px-4 py-2"><input v-model="formEdit.descripcion" class="border rounded px-2 py-1 w-full text-sm" /></td>
                            <td></td>
                            <td></td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex gap-2 justify-center">
                                    <button @click="guardarEdicion(b)" class="text-green-600 hover:underline text-xs">Guardar</button>
                                    <button @click="editando = null" class="text-gray-400 hover:underline text-xs">Cancelar</button>
                                </div>
                            </td>
                        </template>
                        <template v-else>
                            <td class="px-4 py-3 font-bold text-lg">{{ b.numero }}</td>
                            <td class="px-4 py-3">{{ b.nombre || '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ b.tamanio_m2 ? b.tamanio_m2 + ' m²' : '—' }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ b.descripcion || '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span :class="b.estado === 'ocupada'
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-blue-100 text-blue-700'"
                                      class="px-3 py-1 rounded-full text-xs font-semibold capitalize">
                                    {{ b.estado === 'ocupada' ? '🔴 Ocupada' : '🔵 Disponible' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ b.contrato_activo?.cliente
                                    ? b.contrato_activo.cliente.nombre + ' ' + b.contrato_activo.cliente.apellido
                                    : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex gap-3 justify-center">
                                    <button @click="iniciarEdicion(b)" class="text-blue-500 hover:underline text-xs">Editar</button>
                                    <button @click="eliminar(b)"
    class="text-red-600 hover:underline text-xs">
    Eliminar
</button>
                                </div>
                            </td>
                        </template>
                    </tr>
                    <tr v-if="!bodegas.length">
                        <td colspan="7" class="text-center py-8 text-gray-400">No hay bodegas registradas</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>