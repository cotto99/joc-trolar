<script setup>
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const sidebarOpen = ref(false)
const page = usePage()

const navSections = [
    {
        label: 'Principal',
        items: [
            { name: 'Dashboard', href: route('dashboard'),     icon: '🏠' },
            { name: 'Dashboard Admin', href: route('dashboard.admin'), icon: '📊' },
            { name: 'Bodegas',   href: route('bodegas.index'), icon: '🏭' },
            { name: 'Clientes',  href: route('clientes.index'), icon: '👥' },
            { name: 'Contratos', href: route('contratos.index'), icon: '📄' },
            { name: 'Pagos',     href: route('pagos.index'),    icon: '💰' },
        ]
    },
    {
        label: 'Operaciones',
        items: [
            { name: 'Bitácoras',       href: route('bitacoras.index'),        icon: '📓' },
            { name: 'Fletes',          href: route('fletes.index'),           icon: '🚛' },
            { name: 'Candados',        href: route('candados.index'),         icon: '🔐' },
            { name: 'Venta Candados',  href: route('ventas-candados.index'),  icon: '🛒' },
        ]
    },
    {
        label: 'Finanzas',
        items: [
            { name: 'Gastos Fijos',     href: route('gastos-fijos.index'),     icon: '📌' },
            { name: 'Gastos Variables', href: route('gastos-variables.index'), icon: '📊' },
        ]
    },
]

function isActive(href) {
    try {
        return page.url.startsWith(new URL(href).pathname)
    } catch {
        return false
    }
}
</script>
<template>
    <div class="min-h-screen bg-gray-100">

        <!-- TOPBAR MÓVIL -->
        <header class="lg:hidden bg-slate-900 text-white px-4 py-3 flex items-center justify-between sticky top-0 z-40 shadow-lg">
            <span class="text-lg font-bold">🏭 MiniBodegas Asercom</span>
            <div class="flex items-center gap-3">
                <Link :href="route('logout')" method="post" as="button" class="text-xs text-slate-300 hover:text-white">Salir</Link>
                <button @click="sidebarOpen = !sidebarOpen" class="text-white text-2xl">{{ sidebarOpen ? '✕' : '☰' }}</button>
            </div>
        </header>

        <!-- DRAWER MÓVIL -->
        <transition name="slide">
            <div v-if="sidebarOpen" class="lg:hidden fixed inset-0 z-30 flex">
                <div class="absolute inset-0 bg-black/50" @click="sidebarOpen = false"></div>
                <div v-for="section in navSections" :key="section.label" class="mb-2">
    <p class="text-xs text-slate-500 uppercase px-5 py-2 tracking-widest">
        {{ section.label }}
    </p>
    <Link v-for="item in section.items" :key="item.name"
          :href="item.href"
          @click="sidebarOpen = false"
          class="flex items-center gap-4 px-5 py-4 hover:bg-slate-700 transition-colors"
          :class="{ 'bg-slate-700 border-l-4 border-blue-400': isActive(item.href) }">
        <span class="text-2xl">{{ item.icon }}</span>
        <span class="font-medium">{{ item.name }}</span>
    </Link>
</div>
            </div>
        </transition>

        <!-- LAYOUT DESKTOP -->
        <div class="hidden lg:flex min-h-screen">
            <aside class="w-64 bg-slate-900 text-white flex flex-col min-h-screen sticky top-0">
                <div class="p-5 border-b border-slate-700">
                    <h1 class="text-xl font-bold tracking-wide">🏭 MiniBodegas Asercom</h1>
                    <p class="text-xs text-slate-400 mt-1">Control de Bodegas</p>
                </div>
                <nav class="flex-1 py-4 overflow-y-auto">
    <div v-for="section in navSections" :key="section.label" class="mb-2">
        <p class="text-xs text-slate-500 uppercase px-4 py-2 tracking-widest">
            {{ section.label }}
        </p>
        <Link v-for="item in section.items" :key="item.name"
              :href="item.href"
              class="flex items-center gap-3 px-4 py-3 hover:bg-slate-700 transition-colors"
              :class="{ 'bg-slate-700 border-l-4 border-blue-400': isActive(item.href) }">
            <span class="text-xl">{{ item.icon }}</span>
            <span class="text-sm font-medium">{{ item.name }}</span>
        </Link>
    </div>
</nav>
                <div class="p-4 border-t border-slate-700 text-xs text-slate-400">
                    {{ page.props.auth.user.name }}
                </div>
            </aside>

            <div class="flex-1 flex flex-col">
                <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between sticky top-0 z-10">
                    <slot name="header" />
                    <Link :href="route('logout')" method="post" as="button"
                          class="text-sm text-gray-500 hover:text-red-500">
                        Cerrar sesión
                    </Link>
                </header>
                <main class="flex-1 p-6"><slot /></main>
            </div>
        </div>

        <!-- CONTENIDO MÓVIL -->
        <main class="lg:hidden p-4 pb-6"><slot /></main>
    </div>
</template>

<style scoped>
.slide-enter-active, .slide-leave-active { transition: opacity 0.2s ease; }
.slide-enter-from, .slide-leave-to { opacity: 0; }
</style>