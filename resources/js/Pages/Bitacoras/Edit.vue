<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    bitacora:    Object,
    bodegas:     Array,
    clientes:    Array,
    tipos:       Object,
    prioridades: Object,
})

const form = useForm({
    tipo:         props.bitacora.tipo,
    titulo:       props.bitacora.titulo,
    descripcion:  props.bitacora.descripcion,
    prioridad:    props.bitacora.prioridad,
    fecha_evento: props.bitacora.fecha_evento?.slice(0, 16),
    bodega_id:    props.bitacora.bodega_id ?? '',
    cliente_id:   props.bitacora.cliente_id ?? '',
})

function submit() {
    form.put(route('bitacoras.update', props.bitacora.id))
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>📓 Editar Bitácora</template>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Editar Registro</h2>

                <form @submit.prevent="submit" class="space-y-4">

                    <!-- Tipo y Prioridad -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                            <select v-model="form.tipo"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                <option v-for="(label, key) in tipos" :key="key" :value="key">
                                    {{ label }}
                                </option>
                            </select>
                            <p v-if="form.errors.tipo" class="text-red-500 text-xs mt-1">{{ form.errors.tipo }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad *</label>
                            <select v-model="form.prioridad"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                <option v-for="(label, key) in prioridades" :key="key" :value="key">
                                    {{ label }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Título -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                        <input v-model="form.titulo" type="text"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <p v-if="form.errors.titulo" class="text-red-500 text-xs mt-1">{{ form.errors.titulo }}</p>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                        <textarea v-model="form.descripcion" rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                        <p v-if="form.errors.descripcion" class="text-red-500 text-xs mt-1">{{ form.errors.descripcion }}</p>
                    </div>

                    <!-- Fecha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha del evento *</label>
                        <input v-model="form.fecha_evento" type="datetime-local"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <p v-if="form.errors.fecha_evento" class="text-red-500 text-xs mt-1">{{ form.errors.fecha_evento }}</p>
                    </div>

                    <!-- Bodega y Cliente -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bodega (opcional)</label>
                            <select v-model="form.bodega_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">Sin bodega</option>
                                <option v-for="b in bodegas" :key="b.id" :value="b.id">
                                    #{{ b.numero }} {{ b.nombre ?? '' }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente (opcional)</label>
                            <select v-model="form.cliente_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">Sin cliente</option>
                                <option v-for="c in clientes" :key="c.id" :value="c.id">
                                    {{ c.nombre }} {{ c.apellido }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium disabled:opacity-50">
                            Actualizar Registro
                        </button>
                        <a :href="route('bitacoras.index')"
                           class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 text-sm">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>