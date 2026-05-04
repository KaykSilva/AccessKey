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
    pending: 'bg-yellow-100 text-yellow-700',
    approved: 'bg-green-100 text-green-700',
    rejected: 'bg-red-100 text-red-700',
    cancelled: 'bg-gray-100 text-gray-700',
    finished: 'bg-blue-100 text-blue-700',
}[status] || 'bg-gray-100 text-gray-700');

const formatDate = (value) => {
    if (!value) return '-';

    return new Date(value).toLocaleString('pt-BR');
};
</script>

<template>
    <Head title="Reservas de chaves" />

    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Reservas de chaves</h1>
                <p class="text-sm text-gray-500">
                    Agendamento de interesse para uso das chaves por data e período.
                </p>
            </div>

            <button @click="openCreate" class="rounded-lg bg-blue-600 px-4 py-2 text-white">
                Nova reserva
            </button>
        </div>

        <div class="rounded-xl border bg-white p-4 grid grid-cols-5 gap-3">
            <select v-model="filters.key_id" class="rounded-lg border-gray-300">
                <option value="">Todas as chaves</option>
                <option v-for="key in keys" :key="key.id" :value="key.id">
                    {{ key.code }} - {{ key.name }}
                </option>
            </select>

            <select v-model="filters.person_id" class="rounded-lg border-gray-300">
                <option value="">Todas as pessoas</option>
                <option v-for="person in people" :key="person.id" :value="person.id">
                    {{ person.name }}
                </option>
            </select>

            <select v-model="filters.status" class="rounded-lg border-gray-300">
                <option value="">Todos os status</option>
                <option value="pending">Pendentes</option>
                <option value="approved">Aprovadas</option>
                <option value="rejected">Rejeitadas</option>
                <option value="cancelled">Canceladas</option>
                <option value="finished">Finalizadas</option>
            </select>

            <input v-model="filters.start_at" type="date" class="rounded-lg border-gray-300" />

            <button @click="applyFilters" class="rounded-lg bg-gray-900 px-4 py-2 text-white">
                Filtrar
            </button>
        </div>

        <div class="overflow-hidden rounded-xl border bg-white shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="p-4">Chave</th>
                        <th class="p-4">Pessoa</th>
                        <th class="p-4">Início</th>
                        <th class="p-4">Fim</th>
                        <th class="p-4">Período</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="reservation in reservations.data" :key="reservation.id" class="border-t">
                        <td class="p-4 font-medium">
                            {{ reservation.key?.code }} - {{ reservation.key?.name }}
                        </td>

                        <td class="p-4">
                            {{ reservation.person?.name || '-' }}
                        </td>

                        <td class="p-4">
                            {{ formatDate(reservation.start_at) }}
                        </td>

                        <td class="p-4">
                            {{ formatDate(reservation.end_at) }}
                        </td>

                        <td class="p-4">
                            {{ periodLabel(reservation.period) }}
                        </td>

                        <td class="p-4">
                            <span :class="statusClass(reservation.status)" class="rounded-full px-3 py-1 text-xs font-medium">
                                {{ statusLabel(reservation.status) }}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex justify-end gap-2">
                                <button
                                    @click="openEdit(reservation)"
                                    class="rounded-lg bg-blue-600 px-3 py-1.5 text-white"
                                >
                                    Editar
                                </button>

                                <button
                                    @click="destroyReservation(reservation)"
                                    class="rounded-lg bg-red-600 px-3 py-1.5 text-white"
                                >
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="reservations.data.length === 0">
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            Nenhuma reserva encontrada.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex gap-2">
            <button
                v-for="link in reservations.links"
                :key="link.label"
                v-html="link.label"
                :disabled="!link.url"
                @click="link.url && router.visit(link.url)"
                class="rounded-lg border px-3 py-1 text-sm"
                :class="{ 'bg-gray-900 text-white': link.active }"
            />
        </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-2xl rounded-xl bg-white p-6 shadow-xl">
            <h2 class="mb-4 text-xl font-bold">
                {{ editingReservation ? 'Editar reserva' : 'Nova reserva' }}
            </h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Chave</label>
                    <select v-model="form.key_id" class="mt-1 w-full rounded-lg border-gray-300">
                        <option value="">Selecione</option>
                        <option v-for="key in keys" :key="key.id" :value="key.id">
                            {{ key.code }} - {{ key.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.key_id" class="text-sm text-red-600">{{ form.errors.key_id }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Pessoa</label>
                    <select v-model="form.person_id" class="mt-1 w-full rounded-lg border-gray-300">
                        <option value="">Selecione</option>
                        <option v-for="person in people" :key="person.id" :value="person.id">
                            {{ person.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.person_id" class="text-sm text-red-600">{{ form.errors.person_id }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Início</label>
                    <input v-model="form.start_at" type="datetime-local" class="mt-1 w-full rounded-lg border-gray-300" />
                    <p v-if="form.errors.start_at" class="text-sm text-red-600">{{ form.errors.start_at }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Fim</label>
                    <input v-model="form.end_at" type="datetime-local" class="mt-1 w-full rounded-lg border-gray-300" />
                    <p v-if="form.errors.end_at" class="text-sm text-red-600">{{ form.errors.end_at }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Período</label>
                    <select v-model="form.period" class="mt-1 w-full rounded-lg border-gray-300">
                        <option value="morning">Manhã</option>
                        <option value="afternoon">Tarde</option>
                        <option value="night">Noite</option>
                        <option value="custom">Personalizado</option>
                    </select>
                </div>

                <div v-if="editingReservation">
                    <label class="text-sm font-medium">Status</label>
                    <select v-model="form.status" class="mt-1 w-full rounded-lg border-gray-300">
                        <option value="pending">Pendente</option>
                        <option value="approved">Aprovada</option>
                        <option value="rejected">Rejeitada</option>
                        <option value="cancelled">Cancelada</option>
                        <option value="finished">Finalizada</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="text-sm font-medium">Motivo</label>
                    <textarea v-model="form.reason" class="mt-1 w-full rounded-lg border-gray-300"></textarea>
                    <p v-if="form.errors.reason" class="text-sm text-red-600">{{ form.errors.reason }}</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button @click="showModal = false" class="rounded-lg border px-4 py-2">
                    Cancelar
                </button>

                <button @click="save" class="rounded-lg bg-blue-600 px-4 py-2 text-white" :disabled="form.processing">
                    Salvar
                </button>
            </div>
        </div>
    </div>
</template>
