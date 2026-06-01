<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    keys: Object,
    filters: Object,
    responsibles: Array,
    people: Array,
});

const showCreateModal = ref(false);
const showEditModal = ref(false);
const showCheckoutModal = ref(false);
const showReturnModal = ref(false);
const showMobileFilters = ref(false);
const viewMode = ref('mural'); // 'mural' or 'list'

const selectedKey = ref(null);

const form = useForm({
    responsible_id: '',
    name: '',
    code: '',
    description: '',
    location: '',
    status: 'available',
    active: true,
});

const checkoutForm = useForm({
    person_id: '',
    take_notes: '',
});

const returnForm = useForm({
    delivered_by_user_id: '',
    return_notes: '',
});

const filters = useForm({
    status: props.filters?.status || '',
    active: props.filters?.active || '',
});

const hasActiveFilters = computed(() => {
    return filters.status !== '' || filters.active !== '';
});

const openCreate = () => {
    form.reset();
    form.status = 'available';
    form.active = true;
    showCreateModal.value = true;
};

const openEdit = (key) => {
    selectedKey.value = key;

    form.responsible_id = key.responsible_id || '';
    form.name = key.name;
    form.code = key.code;
    form.description = key.description || '';
    form.location = key.location || '';
    form.status = key.status;
    form.active = key.active;

    showEditModal.value = true;
};

const openCheckout = (key) => {
    selectedKey.value = key;
    checkoutForm.reset();
    showCheckoutModal.value = true;
};

const openReturn = (key) => {
    selectedKey.value = key;
    returnForm.reset();
    showReturnModal.value = true;
};

const store = () => {
    form.post(route('keys.store'), {
        preserveScroll: true,
        onSuccess: () => showCreateModal.value = false,
    });
};

const update = () => {
    form.put(route('keys.update', selectedKey.value.id), {
        preserveScroll: true,
        onSuccess: () => showEditModal.value = false,
    });
};

const destroyKey = (key) => {
    if (!confirm(`Deseja excluir permanentemente a chave "${key.code} - ${key.name}"?`)) return;

    router.delete(route('keys.destroy', key.id), {
        preserveScroll: true,
    });
};

const checkout = () => {
    checkoutForm.post(route('keys.checkout', selectedKey.value.id), {
        preserveScroll: true,
        onSuccess: () => showCheckoutModal.value = false,
    });
};

const returnKey = () => {
    returnForm.post(route('keys.return', selectedKey.value.id), {
        preserveScroll: true,
        onSuccess: () => showReturnModal.value = false,
    });
};

const applyFilters = () => {
    router.get(route('keys.index'), {
        status: filters.status,
        active: filters.active,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    filters.reset();
    router.get(route('keys.index'), {}, {
        preserveState: true,
        preserveScroll: true,
    });
};

const statusLabel = (status) => ({
    available: 'Disponível',
    borrowed: 'Retirada',
    maintenance: 'Manutenção',
    inactive: 'Inativa',
}[status] || status);

const statusColor = (status) => ({
    available: 'bg-teal-50 text-teal-700 border-teal-200',
    borrowed: 'bg-orange-50 text-orange-700 border-orange-200',
    maintenance: 'bg-yellow-50 text-yellow-700 border-yellow-200',
    inactive: 'bg-slate-100 text-slate-600 border-slate-200',
}[status] || 'bg-slate-100 text-slate-600 border-slate-200');

const quickActions = computed(() => {
    return [
        {
            label: 'Disponíveis',
            count: props.keys.data?.filter(k => k.status === 'available').length || 0,
            color: 'text-teal-700 bg-teal-50 border border-teal-100',
            icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
        },
        {
            label: 'Retiradas',
            count: props.keys.data?.filter(k => k.status === 'borrowed').length || 0,
            color: 'text-orange-600 bg-orange-50 border border-orange-100',
            icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
        },
        {
            label: 'Manutenção',
            count: props.keys.data?.filter(k => k.status === 'maintenance').length || 0,
            color: 'text-yellow-600 bg-yellow-50 border border-yellow-100',
            icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'
        },
    ];
});
</script>

<template>
    <Head title="Chaves" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Chaves</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Gerencie, retire e devolva chaves do claviculário digital.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <!-- View Mode Toggle -->
                <div class="inline-flex rounded-xl bg-slate-100 p-1 border border-slate-200">
                    <button
                        @click="viewMode = 'mural'"
                        :class="viewMode === 'mural' ? 'bg-white text-teal-600 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition-all"
                        title="Visualizar Mural de Chaves"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                        </svg>
                        Mural
                    </button>
                    <button
                        @click="viewMode = 'list'"
                        :class="viewMode === 'list' ? 'bg-white text-teal-600 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition-all"
                        title="Visualizar Lista"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        Tabela
                    </button>
                </div>

                <button
                    @click="openCreate"
                    class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-teal-600/20 transition-all hover:bg-teal-500 active:scale-95"
                >
                    <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nova Chave
                </button>
            </div>
        </div>

        <!-- Quick Stats Widgets -->
        <div class="grid grid-cols-3 gap-4">
            <div
                v-for="stat in quickActions"
                :key="stat.label"
                :class="stat.color"
                class="rounded-2xl p-4 flex items-center justify-between shadow-sm"
            >
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ stat.label }}</p>
                    <p class="text-2xl font-bold mt-1 text-slate-800">{{ stat.count }}</p>
                </div>
                <div class="p-2 rounded-lg bg-white/60">
                    <svg class="h-6 w-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="stat.icon"></svg>
                </div>
            </div>
        </div>

        <!-- Filters Block -->
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center justify-between">
                <div class="flex flex-wrap items-center gap-3 flex-1">
                    <div class="w-full sm:w-48">
                        <select
                            v-model="filters.status"
                            class="block w-full rounded-xl border-slate-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all"
                        >
                            <option value="">Todos os status</option>
                            <option value="available">Disponíveis</option>
                            <option value="borrowed">Retiradas</option>
                            <option value="maintenance">Manutenção</option>
                            <option value="inactive">Inativas</option>
                        </select>
                    </div>

                    <div class="w-full sm:w-48">
                        <select
                            v-model="filters.active"
                            class="block w-full rounded-xl border-slate-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all"
                        >
                            <option value="">Todas as chaves</option>
                            <option value="1">Ativas</option>
                            <option value="0">Inativas</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button
                        @click="applyFilters"
                        class="flex-1 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-slate-800 sm:flex-none"
                    >
                        Filtrar
                    </button>

                    <button
                        v-if="hasActiveFilters"
                        @click="clearFilters"
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50"
                    >
                        Limpar
                    </button>
                </div>
            </div>
        </div>

        <!-- VIEW MODE: MURAL DE CHAVES (Pegboard) -->
        <div v-if="viewMode === 'mural'" class="relative">
            <!-- Board container (wooden/dark pegboard aesthetic) -->
            <div class="rounded-3xl bg-slate-900 border border-slate-800 p-8 shadow-2xl relative">
                <!-- Board details: subtle hanging grid dots -->
                <div class="absolute inset-0 bg-[radial-gradient(#334155_1.5px,transparent_1.5px)] [background-size:24px_24px] opacity-40 rounded-3xl pointer-events-none"></div>
                
                <div class="relative z-10 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-x-6 gap-y-10">
                    <div
                        v-for="key in keys.data"
                        :key="key.id"
                        class="flex flex-col items-center group relative"
                    >
                        <!-- Peg / Hook -->
                        <div class="relative w-10 h-10 flex items-center justify-center pointer-events-none">
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-950 absolute top-1 shadow-inner"></div>
                            <!-- metallic peg sticking out -->
                            <div class="w-1.5 h-6 bg-gradient-to-b from-slate-200 via-slate-400 to-slate-500 rounded-full shadow-md transform rotate-12 border border-slate-400 absolute top-1 z-10 origin-top"></div>
                        </div>

                        <!-- Hanging Ring of the Key -->
                        <div
                            class="w-6 h-6 rounded-full border border-slate-400/60 bg-transparent absolute top-5 z-0 transition-transform duration-300 group-hover:scale-105"
                            :class="key.status === 'borrowed' ? 'opacity-30' : ''"
                        ></div>

                        <!-- The Key Tag Card -->
                        <div
                            class="relative w-36 bg-white border border-slate-200 rounded-2xl shadow-lg p-3 pt-4 flex flex-col items-center text-center transition-all duration-300 hover:rotate-1 hover:translate-y-0.5 hover:shadow-xl active:scale-98 z-10 mt-1.5"
                            :class="[
                                key.status === 'borrowed' ? 'opacity-40 border-dashed border-slate-300 bg-slate-50/50 shadow-sm' : '',
                                key.status === 'maintenance' ? 'border-t-4 border-t-yellow-500' : 'border-t-4 border-t-slate-200',
                                key.status === 'available' ? 'border-t-4 border-t-teal-500' : '',
                                key.status === 'inactive' ? 'opacity-30 border-t-4 border-t-slate-400 bg-slate-100' : ''
                            ]"
                        >
                            <!-- Mini Info Cog Menu (Quick Edit/Delete) on Hover -->
                            <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 z-20">
                                <button
                                    @click.stop="openEdit(key)"
                                    class="p-1 rounded-md bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors"
                                    title="Editar"
                                >
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button
                                    @click.stop="destroyKey(key)"
                                    class="p-1 rounded-md bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                    title="Excluir"
                                >
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Inside Label Window of Tag -->
                            <div class="w-full bg-slate-50 border border-slate-100 rounded-lg py-1 px-1.5 mb-2">
                                <span class="font-mono text-xs font-bold text-slate-800 tracking-tight">{{ key.code }}</span>
                            </div>

                            <!-- Key Name / Room -->
                            <h4 class="text-xs font-semibold text-slate-900 truncate w-full px-1" :title="key.name">
                                {{ key.name }}
                            </h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 truncate w-full" :title="key.location">
                                {{ key.location || 'Sem local' }}
                            </p>

                            <!-- Dynamic Status Representation -->
                            <div class="mt-3 w-full">
                                <!-- AVAILABLE -->
                                <div v-if="key.status === 'available'" class="space-y-2">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-teal-50 px-2 py-0.5 text-[10px] font-semibold text-teal-700 border border-teal-100">
                                        <span class="h-1 w-1 rounded-full bg-teal-500"></span> Disponível
                                    </span>
                                    <button
                                        @click="openCheckout(key)"
                                        class="w-full rounded-lg bg-teal-600 hover:bg-teal-500 text-white font-bold py-1 px-2 text-[10px] transition-colors shadow-sm active:scale-95"
                                    >
                                        Retirar
                                    </button>
                                </div>

                                <!-- BORROWED -->
                                <div v-else-if="key.status === 'borrowed'" class="space-y-2">
                                    <div class="text-[10px] text-slate-500">
                                        <p class="font-medium text-orange-600 truncate">
                                            Com: {{ key.current_movement?.person?.name || key.currentMovement?.person?.name || 'Retirada' }}
                                        </p>
                                    </div>
                                    <button
                                        @click="openReturn(key)"
                                        class="w-full rounded-lg bg-orange-600 hover:bg-orange-500 text-white font-bold py-1 px-2 text-[10px] transition-colors shadow-sm active:scale-95"
                                    >
                                        Devolver
                                    </button>
                                </div>

                                <!-- MAINTENANCE -->
                                <div v-else-if="key.status === 'maintenance'" class="space-y-2">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-0.5 text-[10px] font-semibold text-yellow-700 border border-yellow-100">
                                        ⚠️ Manutenção
                                    </span>
                                    <div class="text-[9px] text-slate-400 italic">Indisponível</div>
                                </div>

                                <!-- INACTIVE -->
                                <div v-else class="space-y-2">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600 border border-slate-200">
                                        Inativa
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State Mural -->
                <div v-if="keys.data.length === 0" class="flex flex-col items-center justify-center p-12 text-center text-slate-400 relative z-10">
                    <svg class="h-16 w-16 text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    <h3 class="text-lg font-bold text-white">Nenhuma chave no claviculário</h3>
                    <p class="text-sm text-slate-500 mt-1">Nenhuma chave corresponde aos filtros atuais.</p>
                </div>
            </div>
        </div>

        <!-- VIEW MODE: DETAILED LIST (Tabela) -->
        <div v-else class="hidden overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:block">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Código
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Nome
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Responsável
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Local
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Ações
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">
                        <tr v-for="key in keys.data" :key="key.id" class="transition-colors hover:bg-slate-50/60">
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="font-mono text-sm font-semibold text-slate-900 bg-slate-100 rounded-lg px-2.5 py-1">{{ key.code }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-900 font-medium">
                                {{ key.name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                {{ key.responsible?.name || '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                {{ key.location || '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span :class="statusColor(key.status)" class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold">
                                    {{ statusLabel(key.status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        v-if="key.status === 'available'"
                                        @click="openCheckout(key)"
                                        class="inline-flex items-center gap-1 rounded-xl bg-teal-600 px-3 py-1.5 text-xs font-semibold text-white transition-all hover:bg-teal-500 active:scale-95"
                                    >
                                        Retirar
                                    </button>

                                    <button
                                        v-if="key.status === 'borrowed'"
                                        @click="openReturn(key)"
                                        class="inline-flex items-center gap-1 rounded-xl bg-orange-600 px-3 py-1.5 text-xs font-semibold text-white transition-all hover:bg-orange-700 active:scale-95"
                                    >
                                        Devolver
                                    </button>

                                    <button
                                        @click="openEdit(key)"
                                        class="rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition-all hover:bg-slate-50 active:scale-95"
                                    >
                                        Editar
                                    </button>

                                    <button
                                        @click="destroyKey(key)"
                                        class="rounded-xl bg-red-50 text-red-600 px-3 py-1.5 text-xs font-semibold transition-all hover:bg-red-100 active:scale-95"
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="keys.data.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                <p class="text-sm font-semibold text-slate-900">Nenhuma chave encontrada</p>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ hasActiveFilters ? 'Ajuste os filtros ou ' : '' }}cadastre uma nova chave.
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards backup view -->
        <div v-if="viewMode === 'list'" class="space-y-4 lg:hidden">
            <div
                v-for="key in keys.data"
                :key="key.id"
                class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
            >
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-base font-bold text-slate-900 bg-slate-100 rounded-lg px-2 py-0.5">{{ key.code }}</span>
                            <span :class="statusColor(key.status)" class="inline-flex rounded-full border px-2 py-0.5 text-xs font-semibold">
                                {{ statusLabel(key.status) }}
                            </span>
                        </div>
                        <p class="mt-2 text-sm font-semibold text-slate-950">{{ key.name }}</p>
                        <div class="mt-2 space-y-1">
                            <p class="text-xs text-slate-500">
                                <span class="font-semibold">Responsável:</span> {{ key.responsible?.name || 'Não definido' }}
                            </p>
                            <p class="text-xs text-slate-500">
                                <span class="font-semibold">Local:</span> {{ key.location || 'Não definido' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex gap-2 border-t border-slate-100 pt-3">
                    <button
                        v-if="key.status === 'available'"
                        @click="openCheckout(key)"
                        class="flex-1 rounded-xl bg-teal-600 py-2 text-xs font-semibold text-white transition-all active:scale-95"
                    >
                        Retirar
                    </button>

                    <button
                        v-if="key.status === 'borrowed'"
                        @click="openReturn(key)"
                        class="flex-1 rounded-xl bg-orange-600 py-2 text-xs font-semibold text-white transition-all active:scale-95"
                    >
                        Devolver
                    </button>

                    <button
                        @click="openEdit(key)"
                        class="flex-1 rounded-xl border border-slate-200 py-2 text-xs font-semibold text-slate-700 transition-all active:scale-95"
                    >
                        Editar
                    </button>

                    <button
                        @click="destroyKey(key)"
                        class="rounded-xl bg-red-50 text-red-600 px-3 py-2 text-xs font-semibold transition-all active:scale-95"
                    >
                        Excluir
                    </button>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="keys.links?.length > 3" class="flex items-center justify-center gap-1 overflow-x-auto py-2">
            <button
                v-for="link in keys.links"
                :key="link.label"
                :disabled="!link.url"
                @click="link.url && router.visit(link.url)"
                class="rounded-xl px-3.5 py-2 text-sm font-semibold transition-all"
                :class="{
                    'bg-teal-600 text-white shadow-md shadow-teal-600/10': link.active,
                    'text-slate-600 hover:bg-slate-100': !link.active && link.url,
                    'cursor-not-allowed text-slate-300': !link.url
                }"
                v-html="link.label"
            />
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <Teleport to="body">
        <div
            v-if="showCreateModal || showEditModal"
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div class="flex min-h-screen items-center justify-center p-4">
                <!-- Backdrop -->
                <div
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="showCreateModal = false; showEditModal = false"
                />

                <!-- Modal Content -->
                <div class="relative w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl border border-slate-100 z-10">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">
                                {{ showCreateModal ? 'Nova chave' : 'Editar chave' }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ showCreateModal ? 'Cadastre uma nova chave no sistema' : `Editando: ${selectedKey?.code} - ${selectedKey?.name}` }}
                            </p>
                        </div>
                        <button
                            @click="showCreateModal = false; showEditModal = false"
                            class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="showCreateModal ? store() : update()" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">
                                    Nome <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800 placeholder-slate-400"
                                    placeholder="Nome da chave (ex: Sala 102)"
                                />
                                <p v-if="form.errors.name" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700">
                                    Código <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.code"
                                    type="text"
                                    class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all font-mono text-slate-800 placeholder-slate-400"
                                    placeholder="Ex: KEY-102"
                                />
                                <p v-if="form.errors.code" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.code }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Responsável</label>
                                <select
                                    v-model="form.responsible_id"
                                    class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                                >
                                    <option value="">Sem responsável</option>
                                    <option
                                        v-for="responsible in responsibles"
                                        :key="responsible.id"
                                        :value="responsible.id"
                                    >
                                        {{ responsible.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Status</label>
                                <select
                                    v-model="form.status"
                                    class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                                >
                                    <option value="available">Disponível</option>
                                    <option value="borrowed">Retirada</option>
                                    <option value="maintenance">Manutenção</option>
                                    <option value="inactive">Inativa</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700">Localização</label>
                                <input
                                    v-model="form.location"
                                    type="text"
                                    class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800 placeholder-slate-400"
                                    placeholder="Ex: Armário A - Prateleira 2"
                                />
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700">Descrição</label>
                                <textarea
                                    v-model="form.description"
                                    rows="3"
                                    class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800 placeholder-slate-400"
                                    placeholder="Informações e observações sobre a chave..."
                                ></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 border-t border-slate-100 pt-4 mt-6">
                            <button
                                type="button"
                                @click="showCreateModal = false; showEditModal = false"
                                class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-teal-600/10 transition-all hover:bg-teal-500 active:scale-95"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing" class="flex items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                    </svg>
                                    Salvando...
                                </span>
                                <span v-else>Salvar Chave</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Teleport>

    <!-- Checkout Modal -->
    <Teleport to="body">
        <div
            v-if="showCheckoutModal"
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCheckoutModal = false" />

                <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl border border-slate-100 z-10">
                    <div class="mb-6">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-50 text-teal-600 border border-teal-100 shadow-sm">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <h2 class="mt-4 text-center text-xl font-bold text-slate-900">Retirar Chave</h2>
                        <p class="mt-1.5 text-center text-sm text-slate-500 bg-slate-50 rounded-xl py-2 px-3 font-mono font-bold border border-slate-100">
                            {{ selectedKey?.code }} - {{ selectedKey?.name }}
                        </p>
                    </div>

                    <form @submit.prevent="checkout" class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">
                                Quem vai retirar? <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="checkoutForm.person_id"
                                class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                            >
                                <option value="">Selecione uma pessoa autorizada</option>
                                <option
                                    v-for="person in people"
                                    :key="person.id"
                                    :value="person.id"
                                >
                                    {{ person.name }} ({{ person.department || 'Sem setor' }})
                                </option>
                            </select>
                            <p v-if="checkoutForm.errors.person_id" class="mt-1 text-xs font-semibold text-red-600">
                                {{ checkoutForm.errors.person_id }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Observações de Retirada</label>
                            <textarea
                                v-model="checkoutForm.take_notes"
                                rows="3"
                                class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800 placeholder-slate-400"
                                placeholder="Motivo da retirada, estado físico da chave ou observações específicas..."
                            ></textarea>
                        </div>

                        <div class="flex justify-end gap-2 border-t border-slate-100 pt-4 mt-6">
                            <button
                                type="button"
                                @click="showCheckoutModal = false"
                                class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-teal-600/10 hover:bg-teal-700 transition-all active:scale-95"
                                :disabled="checkoutForm.processing"
                            >
                                Confirmar Retirada
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Teleport>

    <!-- Return Modal -->
    <Teleport to="body">
        <div
            v-if="showReturnModal"
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showReturnModal = false" />

                <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl border border-slate-100 z-10">
                    <div class="mb-6">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-50 text-orange-600 border border-orange-100 shadow-sm">
                            <svg class="h-7 w-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <h2 class="mt-4 text-center text-xl font-bold text-slate-900">Devolver Chave</h2>
                        <p class="mt-1.5 text-center text-sm text-slate-500 bg-slate-50 rounded-xl py-2 px-3 font-mono font-bold border border-slate-100">
                            {{ selectedKey?.code }} - {{ selectedKey?.name }}
                        </p>
                    </div>

                    <form @submit.prevent="returnKey" class="space-y-4">
                        <div class="rounded-xl bg-teal-50 border border-teal-100 p-3.5 flex items-start gap-2.5">
                            <svg class="h-5 w-5 text-teal-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-teal-850 leading-relaxed">
                                Você está logado como <strong class="font-bold text-teal-950">{{ $page.props.auth.user.name }}</strong> e será registrado como o recebedor desta chave.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Observações de Devolução</label>
                            <textarea
                                v-model="returnForm.return_notes"
                                rows="3"
                                class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all text-slate-800 placeholder-slate-400"
                                placeholder="Descreva se há avarias, arranhões, chave torta ou se tudo foi devolvido em perfeito estado..."
                            ></textarea>
                        </div>

                        <div class="flex justify-end gap-2 border-t border-slate-100 pt-4 mt-6">
                            <button
                                type="button"
                                @click="showReturnModal = false"
                                class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="rounded-xl bg-orange-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-orange-600/10 hover:bg-orange-700 transition-all active:scale-95"
                                :disabled="returnForm.processing"
                            >
                                Confirmar Devolução
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Teleport>
</template>
