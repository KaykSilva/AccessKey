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
    return filters.status || filters.active;
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
    available: 'bg-green-100 text-green-700 border-green-200',
    borrowed: 'bg-orange-100 text-orange-700 border-orange-200',
    maintenance: 'bg-yellow-100 text-yellow-700 border-yellow-200',
    inactive: 'bg-gray-100 text-gray-700 border-gray-200',
}[status] || 'bg-gray-100 text-gray-700 border-gray-200');

const quickActions = computed(() => {
    return [
        {
            label: 'Disponíveis',
            count: props.keys.data?.filter(k => k.status === 'available').length || 0,
            color: 'text-green-600 bg-green-50',
            icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
        },
        {
            label: 'Retiradas',
            count: props.keys.data?.filter(k => k.status === 'borrowed').length || 0,
            color: 'text-orange-600 bg-orange-50',
            icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
        },
        {
            label: 'Manutenção',
            count: props.keys.data?.filter(k => k.status === 'maintenance').length || 0,
            color: 'text-yellow-600 bg-yellow-50',
            icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
        },
    ];
});
</script>

<template>
    <Head title="Chaves" />

    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Chaves</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Controle de chaves cadastradas, retiradas e devoluções
                </p>
            </div>

            <button
                @click="openCreate"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 active:scale-95"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova chave
            </button>
        </div>

        <!-- Quick Stats (Mobile) -->
        <div class="grid grid-cols-3 gap-3 lg:hidden">
            <div
                v-for="stat in quickActions"
                :key="stat.label"
                :class="stat.color"
                class="rounded-lg p-3 text-center"
            >
                <p class="text-2xl font-bold">{{ stat.count }}</p>
                <p class="text-xs font-medium">{{ stat.label }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <!-- Mobile Filter Toggle -->
            <button
                @click="showMobileFilters = !showMobileFilters"
                class="flex w-full items-center justify-between p-4 sm:hidden"
            >
                <span class="flex items-center gap-2 text-sm font-medium text-gray-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filtros
                    <span v-if="hasActiveFilters" class="ml-2 rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-600">
                        Ativos
                    </span>
                </span>
                <svg
                    class="h-5 w-5 text-gray-400 transition-transform"
                    :class="{ 'rotate-180': showMobileFilters }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Filter Content -->
            <div
                :class="showMobileFilters ? 'block' : 'hidden'"
                class="border-t border-gray-100 p-4 sm:block sm:border-t-0"
            >
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="flex-1 space-y-3 sm:flex sm:space-x-3 sm:space-y-0">
                        <div class="relative">
                            <select
                                v-model="filters.status"
                                class="block w-full rounded-lg border-gray-300 py-2 pl-3 pr-10 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Todos os status</option>
                                <option value="available">Disponíveis</option>
                                <option value="borrowed">Retiradas</option>
                                <option value="maintenance">Manutenção</option>
                                <option value="inactive">Inativas</option>
                            </select>
                        </div>

                        <div class="relative">
                            <select
                                v-model="filters.active"
                                class="block w-full rounded-lg border-gray-300 py-2 pl-3 pr-10 text-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                            class="flex-1 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-800 sm:flex-none"
                        >
                            Filtrar
                        </button>

                        <button
                            v-if="hasActiveFilters"
                            @click="clearFilters"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                        >
                            Limpar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table (Desktop) -->
        <div class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:block">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Código
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Nome
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Responsável
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Local
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                Ações
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="key in keys.data" :key="key.id" class="transition-colors hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="font-mono text-sm font-semibold text-gray-900">{{ key.code }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-900">
                                {{ key.name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-500">
                                {{ key.responsible?.name || '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-500">
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
                                        class="inline-flex items-center gap-1 rounded-lg bg-green-600 px-3 py-1.5 text-xs font-medium text-white transition-all hover:bg-green-700 active:scale-95"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Retirar
                                    </button>

                                    <button
                                        v-if="key.status === 'borrowed'"
                                        @click="openReturn(key)"
                                        class="inline-flex items-center gap-1 rounded-lg bg-orange-600 px-3 py-1.5 text-xs font-medium text-white transition-all hover:bg-orange-700 active:scale-95"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Devolver
                                    </button>

                                    <button
                                        @click="openEdit(key)"
                                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white transition-all hover:bg-blue-700 active:scale-95"
                                    >
                                        Editar
                                    </button>

                                    <button
                                        @click="destroyKey(key)"
                                        class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-medium text-white transition-all hover:bg-red-700 active:scale-95"
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="keys.data.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                <p class="mt-3 text-sm font-medium text-gray-900">Nenhuma chave encontrada</p>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ hasActiveFilters ? 'Tente ajustar os filtros ou ' : '' }}cadastre uma nova chave.
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cards (Mobile/Tablet) -->
        <div class="space-y-4 lg:hidden">
            <div
                v-for="key in keys.data"
                :key="key.id"
                class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm"
            >
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-lg font-bold text-gray-900">{{ key.code }}</span>
                            <span :class="statusColor(key.status)" class="inline-flex rounded-full border px-2 py-0.5 text-xs font-semibold">
                                {{ statusLabel(key.status) }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-gray-900">{{ key.name }}</p>
                        <div class="mt-2 space-y-1">
                            <p class="text-xs text-gray-500">
                                <span class="font-medium">Responsável:</span> {{ key.responsible?.name || 'Não definido' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                <span class="font-medium">Local:</span> {{ key.location || 'Não definido' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-3 flex gap-2 border-t border-gray-100 pt-3">
                    <button
                        v-if="key.status === 'available'"
                        @click="openCheckout(key)"
                        class="flex-1 rounded-lg bg-green-600 px-3 py-2 text-xs font-medium text-white transition-all active:scale-95"
                    >
                        Retirar
                    </button>

                    <button
                        v-if="key.status === 'borrowed'"
                        @click="openReturn(key)"
                        class="flex-1 rounded-lg bg-orange-600 px-3 py-2 text-xs font-medium text-white transition-all active:scale-95"
                    >
                        Devolver
                    </button>

                    <button
                        @click="openEdit(key)"
                        class="flex-1 rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white transition-all active:scale-95"
                    >
                        Editar
                    </button>

                    <button
                        @click="destroyKey(key)"
                        class="rounded-lg bg-red-600 px-3 py-2 text-xs font-medium text-white transition-all active:scale-95"
                    >
                        Excluir
                    </button>
                </div>
            </div>

            <div v-if="keys.data.length === 0" class="rounded-xl border border-gray-200 bg-white p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <p class="mt-3 text-sm font-medium text-gray-900">Nenhuma chave encontrada</p>
                <p class="mt-1 text-sm text-gray-500">Cadastre uma nova chave para começar.</p>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="keys.links?.length > 3" class="flex items-center justify-center gap-1 overflow-x-auto py-2">
            <button
                v-for="link in keys.links"
                :key="link.label"
                :disabled="!link.url"
                @click="link.url && router.visit(link.url)"
                class="rounded-lg px-3 py-1.5 text-sm font-medium transition-all"
                :class="{
                    'bg-indigo-600 text-white shadow-sm': link.active,
                    'text-gray-500 hover:bg-gray-100': !link.active && link.url,
                    'cursor-not-allowed text-gray-300': !link.url
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
                    class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
                    @click="showCreateModal = false; showEditModal = false"
                />

                <!-- Modal Content -->
                <div class="relative w-full max-w-2xl rounded-xl bg-white p-6 shadow-2xl">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">
                                {{ showCreateModal ? 'Nova chave' : 'Editar chave' }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ showCreateModal ? 'Cadastre uma nova chave no sistema' : `Editando: ${selectedKey?.code} - ${selectedKey?.name}` }}
                            </p>
                        </div>
                        <button
                            @click="showCreateModal = false; showEditModal = false"
                            class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="showCreateModal ? store() : update()" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Nome <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Nome da chave"
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Código <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.code"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-300 font-mono shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Ex: KEY-001"
                                />
                                <p v-if="form.errors.code" class="mt-1 text-sm text-red-600">{{ form.errors.code }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Responsável</label>
                                <select
                                    v-model="form.responsible_id"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select
                                    v-model="form.status"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="available">Disponível</option>
                                    <option value="borrowed">Retirada</option>
                                    <option value="maintenance">Manutenção</option>
                                    <option value="inactive">Inativa</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Local</label>
                                <input
                                    v-model="form.location"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Localização da chave"
                                />
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Descrição</label>
                                <textarea
                                    v-model="form.description"
                                    rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Informações adicionais sobre a chave"
                                ></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                            <button
                                type="button"
                                @click="showCreateModal = false; showEditModal = false"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-all hover:bg-indigo-700 active:scale-95"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing" class="flex items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                    </svg>
                                    Salvando...
                                </span>
                                <span v-else>Salvar</span>
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
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showCheckoutModal = false" />

                <div class="relative w-full max-w-md rounded-xl bg-white p-6 shadow-2xl">
                    <div class="mb-6">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <h2 class="mt-4 text-center text-xl font-bold text-gray-900">Retirar chave</h2>
                        <p class="mt-1 text-center text-sm text-gray-500">
                            <strong>{{ selectedKey?.code }} - {{ selectedKey?.name }}</strong>
                        </p>
                    </div>

                    <form @submit.prevent="checkout" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Pessoa que vai retirar <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="checkoutForm.person_id"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >
                                <option value="">Selecione uma pessoa</option>
                                <option
                                    v-for="person in people"
                                    :key="person.id"
                                    :value="person.id"
                                >
                                    {{ person.name }}
                                </option>
                            </select>
                            <p v-if="checkoutForm.errors.person_id" class="mt-1 text-sm text-red-600">
                                {{ checkoutForm.errors.person_id }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Observações</label>
                            <textarea
                                v-model="checkoutForm.take_notes"
                                rows="3"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                placeholder="Motivo da retirada, observações..."
                            ></textarea>
                        </div>

                        <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                            <button
                                type="button"
                                @click="showCheckoutModal = false"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 active:scale-95"
                                :disabled="checkoutForm.processing"
                            >
                                Confirmar retirada
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
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showReturnModal = false" />

                <div class="relative w-full max-w-md rounded-xl bg-white p-6 shadow-2xl">
                    <div class="mb-6">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-orange-100">
                            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <h2 class="mt-4 text-center text-xl font-bold text-gray-900">Devolver chave</h2>
                        <p class="mt-1 text-center text-sm text-gray-500">
                            <strong>{{ selectedKey?.code }} - {{ selectedKey?.name }}</strong>
                        </p>
                    </div>

                    <form @submit.prevent="returnKey" class="space-y-4">
                        <div class="rounded-lg bg-gray-50 p-3">
                            <p class="text-sm text-gray-600">
                                O usuário logado será registrado automaticamente como responsável pela devolução.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Observações da devolução</label>
                            <textarea
                                v-model="returnForm.return_notes"
                                rows="3"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="Estado da chave, observações..."
                            ></textarea>
                        </div>

                        <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                            <button
                                type="button"
                                @click="showReturnModal = false"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700 active:scale-95"
                                :disabled="returnForm.processing"
                            >
                                Confirmar devolução
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Teleport>
</template>
