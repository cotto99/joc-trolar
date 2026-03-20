<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
    candados:  Array,
    clientes:  Array,
    contratos: Array,
})

// Modo cliente: 'existente' | 'nuevo' | 'consumidor'
const modoCliente = ref('existente')

const form = useForm({
    candado_id:  '',
    cliente_id:  '',
    contrato_id: '',
    cantidad:    1,
    fecha_venta: '',
    notas:       '',
    // Nuevo cliente
    nuevo_cliente:          false,
    nuevo_cliente_nombre:   '',
    nuevo_cliente_apellido: '',
    nuevo_cliente_telefono: '',
    nuevo_cliente_email:    '',
})

const candadoSeleccionado = computed(() =>
    props.candados.find(c => c.id == form.candado_id)
)

const totalEstimado = computed(() => {
    if (!candadoSeleccionado.value || !form.cantidad) return '0.00'
    return (candadoSeleccionado.value.precio_venta * form.cantidad).toFixed(2)
})

function cambiarModo(modo) {
    modoCliente.value = modo
    form.cliente_id             = ''
    form.nuevo_cliente          = false
    form.nuevo_cliente_nombre   = ''
    form.nuevo_cliente_apellido = ''
    form.nuevo_cliente_telefono = ''
    form.nuevo_cliente_email    = ''

    if (modo === 'nuevo') {
        form.nuevo_cliente = true
    }
}

function submit() {
    form.post(route('ventas-candados.store'))
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>🛒 Nueva Venta de Candado</template>

        <div class="max-w-xl mx-auto">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Registrar Venta</h2>

                <form @submit.prevent="submit" class="space-y-4">

                    <!-- Candado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Candado *</label>
                        <select v-model="form.candado_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar candado</option>
                            <option v-for="c in candados" :key="c.id" :value="c.id">
                                {{ c.tipo }} — Stock: {{ c.stock }} — Q{{ Number(c.precio_venta).toFixed(2) }}
                            </option>
                        </select>
                        <p v-if="form.errors.candado_id" class="text-red-500 text-xs mt-1">{{ form.errors.candado_id }}</p>
                    </div>

                    <!-- Info candado seleccionado -->
                    <div v-if="candadoSeleccionado"
                         class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="grid grid-cols-3 gap-2 text-center text-sm">
                            <div>
                                <p class="text-xs text-gray-400">Stock disponible</p>
                                <p class="font-bold text-blue-600">{{ candadoSeleccionado.stock }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Precio unitario</p>
                                <p class="font-bold text-gray-700">Q{{ Number(candadoSeleccionado.precio_venta).toFixed(2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Total estimado</p>
                                <p class="font-bold text-green-600">Q{{ totalEstimado }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Selector de modo cliente -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                        <div class="flex gap-2 mb-3">
                            <button type="button"
                                    @click="cambiarModo('existente')"
                                    :class="[
                                        'flex-1 py-2 px-3 rounded-lg text-xs font-medium border transition',
                                        modoCliente === 'existente'
                                            ? 'bg-blue-600 text-white border-blue-600'
                                            : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400'
                                    ]">
                                👤 Cliente Existente
                            </button>
                            <button type="button"
                                    @click="cambiarModo('nuevo')"
                                    :class="[
                                        'flex-1 py-2 px-3 rounded-lg text-xs font-medium border transition',
                                        modoCliente === 'nuevo'
                                            ? 'bg-green-600 text-white border-green-600'
                                            : 'bg-white text-gray-600 border-gray-300 hover:border-green-400'
                                    ]">
                                ➕ Crear Cliente
                            </button>
                            <button type="button"
                                    @click="cambiarModo('consumidor')"
                                    :class="[
                                        'flex-1 py-2 px-3 rounded-lg text-xs font-medium border transition',
                                        modoCliente === 'consumidor'
                                            ? 'bg-gray-600 text-white border-gray-600'
                                            : 'bg-white text-gray-600 border-gray-300 hover:border-gray-400'
                                    ]">
                                🏪 Consumidor Final
                            </button>
                        </div>

                        <!-- Cliente existente -->
                        <div v-if="modoCliente === 'existente'">
                            <select v-model="form.cliente_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccionar cliente</option>
                                <option v-for="c in clientes" :key="c.id" :value="c.id">
                                    {{ c.nombre }} {{ c.apellido }}
                                    {{ c.telefono ? '— ' + c.telefono : '' }}
                                </option>
                            </select>
                            <p v-if="form.errors.cliente_id" class="text-red-500 text-xs mt-1">{{ form.errors.cliente_id }}</p>
                        </div>

                        <!-- Crear cliente nuevo -->
                        <div v-if="modoCliente === 'nuevo'"
                             class="bg-green-50 border border-green-200 rounded-lg p-4 space-y-3">
                            <p class="text-xs text-green-700 font-medium">📝 Datos del nuevo cliente</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Nombre *</label>
                                    <input v-model="form.nuevo_cliente_nombre" type="text"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                           placeholder="Nombre">
                                    <p v-if="form.errors.nuevo_cliente_nombre" class="text-red-500 text-xs mt-1">
                                        {{ form.errors.nuevo_cliente_nombre }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Apellido *</label>
                                    <input v-model="form.nuevo_cliente_apellido" type="text"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                           placeholder="Apellido">
                                    <p v-if="form.errors.nuevo_cliente_apellido" class="text-red-500 text-xs mt-1">
                                        {{ form.errors.nuevo_cliente_apellido }}
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Teléfono</label>
                                    <input v-model="form.nuevo_cliente_telefono" type="text"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                           placeholder="Ej: 5555-1234">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Email</label>
                                    <input v-model="form.nuevo_cliente_email" type="email"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                                           placeholder="correo@ejemplo.com">
                                    <p v-if="form.errors.nuevo_cliente_email" class="text-red-500 text-xs mt-1">
                                        {{ form.errors.nuevo_cliente_email }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Consumidor final -->
                        <div v-if="modoCliente === 'consumidor'"
                             class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-center">
                            <p class="text-sm text-gray-500">🏪 La venta se registrará como <strong>Consumidor Final</strong></p>
                            <p class="text-xs text-gray-400 mt-1">No se vinculará a ningún cliente en el sistema</p>
                        </div>
                    </div>

                    <!-- Contrato (opcional) -->
                    <div v-if="modoCliente !== 'consumidor'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contrato (opcional)</label>
                        <select v-model="form.contrato_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">Sin contrato</option>
                            <option v-for="c in contratos" :key="c.id" :value="c.id">
                                Bodega #{{ c.bodega?.numero }} — {{ c.cliente?.nombre }} {{ c.cliente?.apellido }}
                            </option>
                        </select>
                    </div>

                    <!-- Cantidad y Fecha -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad *</label>
                            <input v-model="form.cantidad" type="number" min="1"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <p v-if="form.errors.cantidad" class="text-red-500 text-xs mt-1">{{ form.errors.cantidad }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de venta *</label>
                            <input v-model="form.fecha_venta" type="date"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <p v-if="form.errors.fecha_venta" class="text-red-500 text-xs mt-1">{{ form.errors.fecha_venta }}</p>
                        </div>
                    </div>

                    <!-- Notas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas (opcional)</label>
                        <textarea v-model="form.notas" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                  placeholder="Observaciones..."></textarea>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 text-sm font-medium disabled:opacity-50">
                            Registrar Venta
                        </button>
                        <Link :href="route('ventas-candados.index')"
                              class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 text-sm">
                            Cancelar
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>