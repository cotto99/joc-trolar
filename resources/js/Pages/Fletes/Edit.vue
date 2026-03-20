<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
    flete:    Object,
    bodegas:  Array,
    clientes: Array,
    estados:  Object,
})

const form = useForm({
    descripcion_carga: props.flete.descripcion_carga,
    costo:             props.flete.costo,
    fecha_flete:       props.flete.fecha_flete,
    estado:            props.flete.estado,
    notas:             props.flete.notas ?? '',
    bodega_id:         props.flete.bodega_id ?? '',
    cliente_id:        props.flete.cliente_id ?? '',
})

function submit() {
    form.put(route('fletes.update', props.flete.id))
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>🚛 Editar Flete</template>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Editar Flete</h2>

                <form @submit.prevent="submit" class="space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                        <input v-model="form.descripcion_carga" type="text"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <p v-if="form.errors.descripcion_carga" class="text-red-500 text-xs mt-1">{{ form.errors.descripcion_carga }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Costo (Q) *</label>
                            <input v-model="form.costo" type="number" step="0.01"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <p v-if="form.errors.costo" class="text-red-500 text-xs mt-1">{{ form.errors.costo }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                            <input v-model="form.fecha_flete" type="date"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                        <select v-model="form.estado"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option v-for="(label, key) in estados" :key="key" :value="key">
                                {{ label }}
                            </option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bodega</label>
                            <select v-model="form.bodega_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">Sin bodega</option>
                                <option v-for="b in bodegas" :key="b.id" :value="b.id">
                                    #{{ b.numero }} {{ b.nombre ?? '' }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                            <select v-model="form.cliente_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">Sin cliente</option>
                                <option v-for="c in clientes" :key="c.id" :value="c.id">
                                    {{ c.nombre }} {{ c.apellido }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                        <textarea v-model="form.notas" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium disabled:opacity-50">
                            Actualizar Flete
                        </button>
                        <Link :href="route('fletes.index')"
                              class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 text-sm">
                            Cancelar
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>