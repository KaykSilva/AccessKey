<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    movements: Object,
    filters: Object,
    keys: Array,
    people: Array,
});

const filters = useForm({
    key_id: props.filters?.key_id || '',
    person_id: props.filters?.person_id || '',
    status: props.filters?.status || '',
});

const applyFilters = () => {
    router.get(route('movements.index'), {
        key_id: filters.key_id,
        person_id: filters.person_id,
        status: filters.status,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const formatDate = (value) => {
    if (!value) return '-';
    return new Date(value).toLocaleString('pt-BR');
};

const statusLabel = (status) => ({
    borrowed: 'Em aberto',
    returned: 'Devolvida',
    late: 'Atrasada',
}[status] || status);

const statusClass = (status) => ({
    borrowed: 'bg-orange-50 text-orange-700 border-orange-200',
    returned: 'bg-teal-50 text-teal-700 border-teal-200',
    late: 'bg-red-50 text-red-700 border-red-200',
}[status] || 'bg-slate-50 text-slate-600 border-slate-200');
</script>

<template>
    <Head title="Histórico de Movimentações" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Histórico de Movimentações</h1>
                <p class="mt-1 text-sm text-slate-500">Auditoria completa de todas as retiradas e devoluções do claviculário.</p>
            </div>
        </div>

        <!-- Filters Block -->
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                <select
                    v-model="filters.key_id"
                    class="block w-full rounded-xl border-slate-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                >
                    <option value="">Todas as chaves</option>
                    <option v-for="key in keys" :key="key.id" :value="key.id">
                        {{ key.code }} - {{ key.name }}
                    </option>
                </select>

                <select
                    v-model="filters.person_id"
                    class="block w-full rounded-xl border-slate-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                >
                    <option value="">Todas as pessoas</option>
                    <option v-for="person in people" :key="person.id" :value="person.id">
                        {{ person.name }}
                    </option>
                </select>

                <select
                    v-model="filters.status"
                    class="block w-full rounded-xl border-slate-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                >
                    <option value="">Todos os status</option>
                    <option value="borrowed">Em aberto</option>
                    <option value="returned">Devolvidas</option>
                    <option value="late">Atrasadas</option>
                </select>

                <button
                    @click="applyFilters"
                    class="block w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-slate-800"
                >
                    Filtrar
                </button>
            </div>
        </div>

        <!-- Data Table (Desktop) -->
        <div class="hidden overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:block">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Chave</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Pessoa</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Retirada Por</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Retirada Em</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Recebida Por</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Devolvida Em</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">
                        <tr v-for="movement in movements.data" :key="movement.id" class="transition-colors hover:bg-slate-50/60">
                            <td class="whitespace-nowrap px-6 py-4 text-slate-900 font-semibold">
                                {{ movement.key?.code }} - {{ movement.key?.name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                {{ movement.person?.name || '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                {{ movement.taken_by_user?.name || movement.takenByUser?.name || '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                {{ formatDate(movement.taken_at) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                {{ movement.received_by_user?.name || movement.receivedByUser?.name || '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                {{ formatDate(movement.returned_at) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span :class="statusClass(movement.status)" class="inline-flex rounded-full border px-2.5 py-0.5 text-xs font-semibold">
                                    {{ statusLabel(movement.status) }}
                                </span>
                            </td>
                        </tr>

                        <tr v-if="movements.data.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-semibold text-slate-900">Nenhuma movimentação encontrada</p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Ajuste os filtros para refinar a busca.
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="space-y-4 lg:hidden">
            <div
                v-for="movement in movements.data"
                :key="movement.id"
                class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
            >
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-bold text-slate-950">{{ movement.key?.code }} - {{ movement.key?.name }}</span>
                            <span :class="statusClass(movement.status)" class="inline-flex rounded-full border px-2.5 py-0.5 text-xs font-semibold">
                                {{ statusLabel(movement.status) }}
                            </span>
                        </div>
                        <div class="mt-2 space-y-1 text-xs text-slate-500">
                            <p><span class="font-semibold text-slate-700">Pessoa:</span> {{ movement.person?.name || '-' }}</p>
                            <p><span class="font-semibold text-slate-700">Retirada por:</span> {{ movement.taken_by_user?.name || movement.takenByUser?.name || '-' }}</p>
                            <p><span class="font-semibold text-slate-700">Retirada em:</span> {{ formatDate(movement.taken_at) }}</p>
                            <p><span class="font-semibold text-slate-700">Recebida por:</span> {{ movement.received_by_user?.name || movement.receivedByUser?.name || '-' }}</p>
                            <p><span class="font-semibold text-slate-700">Devolvida em:</span> {{ formatDate(movement.returned_at) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="movements.links?.length > 3" class="flex items-center justify-center gap-1 overflow-x-auto py-2">
            <button
                v-for="link in movements.links"
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
</template>
