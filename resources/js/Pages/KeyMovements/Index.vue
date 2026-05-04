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
    borrowed: 'bg-orange-100 text-orange-700',
    returned: 'bg-green-100 text-green-700',
    late: 'bg-red-100 text-red-700',
}[status] || 'bg-gray-100 text-gray-700');
</script>

<template>
    <Head title="Histórico de chaves" />

    <div class="p-6 space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Histórico de chaves</h1>
            <p class="text-sm text-gray-500">
                Auditoria completa de retiradas e devoluções.
            </p>
        </div>

        <div class="rounded-xl border bg-white p-4 grid grid-cols-4 gap-3">
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
                <option value="borrowed">Em aberto</option>
                <option value="returned">Devolvidas</option>
                <option value="late">Atrasadas</option>
            </select>

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
                        <th class="p-4">Retirada por</th>
                        <th class="p-4">Retirada em</th>
                        <th class="p-4">Recebida por</th>
                        <th class="p-4">Devolvida em</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="movement in movements.data" :key="movement.id" class="border-t">
                        <td class="p-4 font-medium">
                            {{ movement.key?.code }} - {{ movement.key?.name }}
                        </td>

                        <td class="p-4">
                            {{ movement.person?.name || '-' }}
                        </td>

                        <td class="p-4">
                            {{ movement.taken_by_user?.name || movement.takenByUser?.name || '-' }}
                        </td>

                        <td class="p-4">
                            {{ formatDate(movement.taken_at) }}
                        </td>

                        <td class="p-4">
                            {{ movement.received_by_user?.name || movement.receivedByUser?.name || '-' }}
                        </td>

                        <td class="p-4">
                            {{ formatDate(movement.returned_at) }}
                        </td>

                        <td class="p-4">
                            <span :class="statusClass(movement.status)" class="rounded-full px-3 py-1 text-xs font-medium">
                                {{ statusLabel(movement.status) }}
                            </span>
                        </td>
                    </tr>

                    <tr v-if="movements.data.length === 0">
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            Nenhuma movimentação encontrada.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex gap-2">
            <button
                v-for="link in movements.links"
                :key="link.label"
                v-html="link.label"
                :disabled="!link.url"
                @click="link.url && router.visit(link.url)"
                class="rounded-lg border px-3 py-1 text-sm"
                :class="{ 'bg-gray-900 text-white': link.active }"
            />
        </div>
    </div>
</template>
