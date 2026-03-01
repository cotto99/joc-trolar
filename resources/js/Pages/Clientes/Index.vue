<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({ clientes: Array })

const form = useForm({
    nombre: '', apellido: '', telefono: '',
    email: '', dpi: '', nit: '', direccion: '', notas: ''
})

const editando  = ref(null)
const verDetalle = ref(null)
const formEdit  = useForm({
    nombre: '', apellido: '', telefono: '',
    email: '', dpi: '', nit: '', direccion: '', notas: ''
})

function guardar() {
    form.post(route('clientes.store'), { onSuccess: () => form.reset() })
}

function iniciarEdicion(c) {
    editando.value = c.id
    Object.assign(formEdit, {
        nombre: c.nombre, apellido: c.apellido,
        telefono: c.telefono, email: c.email,
        dpi: c.dpi, nit: c.nit,
        direccion: c.direccion, notas: c.notas
    })
}

function guardarEdicion(c) {
    formEdit.put(route('clientes.update', c.id), {
        onSuccess: () => editando.value = null
    })
}

function eliminar(c) {
    if (confirm(`¿Eliminar a ${c.nombre} ${c.apellido}?`))
        useForm({}).delete(route('clientes.destroy', c.id))
}
</script>

<template>
    <Head title="Clientes" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-bold text-slate-800">👥 Clientes</h1>
        </template>

        <!-- Formulario -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="font-bold text-gray-700 mb-4">Nuevo Cliente</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nombre *</label>
                    <input v-model="form.nombre" type="text"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                    <p v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Apellido *</label>
                    <input v-model="form.apellido" type="text"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Teléfono</label>
                    <input v-model="form.telefono" type="text"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Email</label>
                    <input v-model="form.email" type="email"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">DPI</label>
                    <input v-model="form.dpi" type="text"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">NIT</label>
                    <input v-model="form.nit" type="text"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Dirección</label>
                    <input v-model="form.direccion" type="text"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Notas</label>
                    <input v-model="form.notas" type="text"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>
            <button @click="guardar" :disabled="form.processing"
                    class="bg-slate-700 text-white px-6 py-2 rounded-lg hover:bg-slate-800 text-sm font-medium disabled:opacity-50">
                + Agregar Cliente
            </button>
        </div>

        <!-- Tabla -->
        <div class="bg-white rounded-xl shadow overflow-hidden overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">Nombre</th>
                        <th class="text-left px-4 py-3">Teléfono</th>
                        <th class="text-left px-4 py-3">Email</th>
                        <th class="text-left px-4 py-3">Bodegas activas</th>
                        <th class="text-center px-4 py-3">Contratos</th>
                        <th class="text-center px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="c in clientes" :key="c.id" class="border-t hover:bg-gray-50">
                        <template v-if="editando === c.id">
                            <td class="px-4 py-2" colspan="5">
                                <div class="grid grid-cols-4 gap-2">
                                    <input v-model="formEdit.nombre" placeholder="Nombre" class="border rounded px-2 py-1 text-sm" />
                                    <input v-model="formEdit.apellido" placeholder="Apellido" class="border rounded px-2 py-1 text-sm" />
                                    <input v-model="formEdit.telefono" placeholder="Teléfono" class="border rounded px-2 py-1 text-sm" />
                                    <input v-model="formEdit.email" placeholder="Email" class="border rounded px-2 py-1 text-sm" />
                                    <input v-model="formEdit.dpi" placeholder="DPI" class="border rounded px-2 py-1 text-sm" />
                                    <input v-model="formEdit.nit" placeholder="NIT" class="border rounded px-2 py-1 text-sm" />
                                    <input v-model="formEdit.direccion" placeholder="Dirección" class="border rounded px-2 py-1 text-sm col-span-2" />
                                </div>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex gap-2 justify-center">
                                    <button @click="guardarEdicion(c)" class="text-green-600 hover:underline text-xs">Guardar</button>
                                    <button @click="editando = null" class="text-gray-400 hover:underline text-xs">Cancelar</button>
                                </div>
                            </td>
                        </template>
                        <template v-else>
                            <td class="px-4 py-3 font-medium">{{ c.nombre }} {{ c.apellido }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ c.telefono || '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ c.email || '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    <span v-for="cont in c.contratos_activos" :key="cont.id"
                                          class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full">
                                        🏭 {{ cont.bodega?.numero }}
                                    </span>
                                    <span v-if="!c.contratos_activos?.length" class="text-gray-400 text-xs">Sin bodegas</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="bg-slate-100 text-slate-700 text-xs px-2 py-1 rounded-full">
                                    {{ c.contratos_count }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex gap-3 justify-center">
                                    <button @click="iniciarEdicion(c)" class="text-blue-500 hover:underline text-xs">Editar</button>
                                    <button @click="eliminar(c)" class="text-red-400 hover:underline text-xs">Eliminar</button>
                                </div>
                            </td>
                        </template>
                    </tr>
                    <tr v-if="!clientes.length">
                        <td colspan="6" class="text-center py-8 text-gray-400">No hay clientes registrados</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>