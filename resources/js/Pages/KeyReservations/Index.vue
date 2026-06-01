<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    reservations: Object,
    filters: Object,
    keys: Array,
    people: Array,
});

const showModal = ref(false);
const editingReservation = ref(null);

const form = useForm({
    key_id: '',
    person_id: '',
    start_at: '',
    end_at: '',
    period: 'custom',
    reason: '',
    status: 'pending',
});

const filters = useForm({
    key_id: props.filters?.key_id || '',
    person_id: props.filters?.person_id || '',
    status: props.filters?.status || '',
    start_at: props.filters?.start_at || '',
    end_at: props.filters?.end_at || '',
});

const openCreate = () => {
    editingReservation.value = null;
    form.reset();
    form.period = 'custom';
    form.status = 'pending';
    showModal.value = true;
};

const openEdit = (reservation) => {
    editingReservation.value = reservation;

    form.key_id = reservation.key_id;
    form.person_id = reservation.person_id;
    form.start_at = reservation.start_at?.slice(0, 16);
    form.end_at = reservation.end_at?.slice(0, 16);
    form.period = reservation.period;
    form.reason = reservation.reason || '';
    form.status = reservation.status;

    showModal.value = true;
};

const save = () => {
    if (editingReservation.value) {
        form.put(route('reservations.update', editingReservation.value.id), {
            preserveScroll: true,
            onSuccess: () => showModal.value = false,
        });

        return;
    }

    form.post(route('reservations.store'), {
        preserveScroll: true,
        onSuccess: () => showModal.value = false,
    });
};

const destroyReservation = (reservation) => {
    if (!confirm('Deseja excluir esta reserva?')) return;

    router.delete(route('reservations.destroy', reservation.id), {
        preserveScroll: true,
    });
};

const applyFilters = () => {
    router.get(route('reservations.index'), {
        key_id: filters.key_id,
        person_id: filters.person_id,
        status: filters.status,
        start_at: filters.start_at,
        end_at: filters.end_at,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const statusLabel = (status) => ({
    pending: 'Pendente',
    approved: 'Aprovada',
    rejected: 'Rejeitada',
    cancelled: 'Cancelada',
    finished: 'Finalizada',
}[status] || status);

const periodLabel = (period) => ({
    morning: 'Manhã',
    afternoon: 'Tarde',
    night: 'Noite',
    custom: 'Personalizado',
}[period] || period);

const statusClass = (status) => ({
    pending: 'bg-yellow-50 text-yellow-700 border-yellow-200',
    approved: 'bg-teal-50 text-teal-700 border-teal-200',
    rejected: 'bg-red-50 text-red-700 border-red-200',
    cancelled: 'bg-slate-50 text-slate-600 border-slate-200',
    finished: 'bg-slate-100 text-slate-700 border-slate-300',
}[status] || 'bg-slate-500 text-slate-600 border-slate-200');

const formatDate = (value) => {
    if (!value) return '-';

    return new Date(value).toLocaleString('pt-BR');
};
</script>

<template>
    <Head title="Reservas de Chaves" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Agendamentos</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Agendamento de interesse para uso das chaves por data e período.
                </p>
            </div>

            <button
                @click="openCreate"
                class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-teal-600/20 transition-all hover:bg-teal-500 active:scale-95"
            >
                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Reserva
            </button>
        </div>

        <!-- Filters Block -->
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
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
                    <option value="pending">Pendentes</option>
                    <option value="approved">Aprovadas</option>
                    <option value="rejected">Rejeitadas</option>
                    <option value="cancelled">Canceladas</option>
                    <option value="finished">Finalizadas</option>
                </select>

                <input
                    v-model="filters.start_at"
                    type="date"
                    class="block w-full rounded-xl border-slate-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                />

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
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Início</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Fim</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Período</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">
                        <tr v-for="reservation in reservations.data" :key="reservation.id" class="transition-colors hover:bg-slate-50/60">
                            <td class="whitespace-nowrap px-6 py-4 text-slate-900 font-semibold">
                                {{ reservation.key?.code }} - {{ reservation.key?.name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                {{ reservation.person?.name || '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                {{ formatDate(reservation.start_at) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                {{ formatDate(reservation.end_at) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                <span class="bg-slate-100 rounded-lg px-2 py-1 text-xs font-medium text-slate-700">
                                    {{ periodLabel(reservation.period) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span :class="statusClass(reservation.status)" class="inline-flex rounded-full border px-2.5 py-0.5 text-xs font-semibold">
                                    {{ statusLabel(reservation.status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        @click="openEdit(reservation)"
                                        class="rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition-all hover:bg-slate-50 active:scale-95"
                                    >
                                        Editar
                                    </button>

                                    <button
                                        @click="destroyReservation(reservation)"
                                        class="rounded-xl bg-red-50 text-red-600 px-3 py-1.5 text-xs font-semibold transition-all hover:bg-red-100 active:scale-95"
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="reservations.data.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm font-semibold text-slate-900">Nenhum agendamento encontrado</p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Ajuste os filtros ou crie uma nova reserva.
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
                v-for="reservation in reservations.data"
                :key="reservation.id"
                class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
            >
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-bold text-slate-950">{{ reservation.key?.code }} - {{ reservation.key?.name }}</span>
                            <span :class="statusClass(reservation.status)" class="inline-flex rounded-full border px-2 py-0.5 text-xs font-semibold">
                                {{ statusLabel(reservation.status) }}
                            </span>
                        </div>
                        <div class="mt-2 space-y-1 text-xs text-slate-500">
                            <p><span class="font-semibold text-slate-700">Pessoa:</span> {{ reservation.person?.name || '-' }}</p>
                            <p><span class="font-semibold text-slate-700">Início:</span> {{ formatDate(reservation.start_at) }}</p>
                            <p><span class="font-semibold text-slate-700">Fim:</span> {{ formatDate(reservation.end_at) }}</p>
                            <p><span class="font-semibold text-slate-700">Período:</span> {{ periodLabel(reservation.period) }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex gap-2 border-t border-slate-100 pt-3">
                    <button
                        @click="openEdit(reservation)"
                        class="flex-1 rounded-xl border border-slate-200 py-2 text-xs font-semibold text-slate-700 transition-all active:scale-95"
                    >
                        Editar
                    </button>

                    <button
                        @click="destroyReservation(reservation)"
                        class="rounded-xl bg-red-50 text-red-600 px-3 py-2 text-xs font-semibold transition-all active:scale-95"
                    >
                        Excluir
                    </button>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="reservations.links?.length > 3" class="flex items-center justify-center gap-1 overflow-x-auto py-2">
            <button
                v-for="link in reservations.links"
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
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModal = false" />

        <!-- Modal Card -->
        <div class="relative w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl border border-slate-100 z-10">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        {{ editingReservation ? 'Editar Reserva' : 'Nova Reserva' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ editingReservation ? 'Altere as informações da reserva selecionada.' : 'Crie um agendamento prévio de chave no sistema.' }}
                    </p>
                </div>
                <button
                    @click="showModal = false"
                    class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form @submit.prevent="save" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Chave <span class="text-red-500">*</span></label>
                        <select
                            v-model="form.key_id"
                            class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                        >
                            <option value="">Selecione a chave</option>
                            <option v-for="key in keys" :key="key.id" :value="key.id">
                                {{ key.code }} - {{ key.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.key_id" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.key_id }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Pessoa <span class="text-red-500">*</span></label>
                        <select
                            v-model="form.person_id"
                            class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                        >
                            <option value="">Selecione a pessoa</option>
                            <option v-for="person in people" :key="person.id" :value="person.id">
                                {{ person.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.person_id" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.person_id }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Início <span class="text-red-500">*</span></label>
                        <input
                            v-model="form.start_at"
                            type="datetime-local"
                            class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                        />
                        <p v-if="form.errors.start_at" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.start_at }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Fim <span class="text-red-500">*</span></label>
                        <input
                            v-model="form.end_at"
                            type="datetime-local"
                            class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                        />
                        <p v-if="form.errors.end_at" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.end_at }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Período</label>
                        <select
                            v-model="form.period"
                            class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                        >
                            <option value="morning">Manhã</option>
                            <option value="afternoon">Tarde</option>
                            <option value="night">Noite</option>
                            <option value="custom">Personalizado</option>
                        </select>
                    </div>

                    <div v-if="editingReservation">
                        <label class="text-sm font-semibold text-slate-700">Status</label>
                        <select
                            v-model="form.status"
                            class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                        >
                            <option value="pending">Pendente</option>
                            <option value="approved">Aprovada</option>
                            <option value="rejected">Rejeitada</option>
                            <option value="cancelled">Cancelada</option>
                            <option value="finished">Finalizada</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="text-sm font-semibold text-slate-700">Motivo</label>
                        <textarea
                            v-model="form.reason"
                            rows="2"
                            class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800 placeholder-slate-400"
                            placeholder="Motivo da reserva..."
                        ></textarea>
                        <p v-if="form.errors.reason" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.reason }}</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button
                        type="button"
                        @click="showModal = false"
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-teal-600/10 hover:bg-teal-500 transition-all active:scale-95"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing" class="flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            Salvando...
                        </span>
                        <span v-else>Salvar Reserva</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
