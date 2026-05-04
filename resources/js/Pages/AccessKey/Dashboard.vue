<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    stats: Object,
    recentMovements: Array,
    upcomingReservations: Array,
});

const formatDate = (value) => {
    if (!value) return '-';
    return new Date(value).toLocaleString('pt-BR');
};

const statusLabel = (status) => ({
    available: 'Disponível',
    borrowed: 'Retirada',
    maintenance: 'Manutenção',
    inactive: 'Inativa',
    pending: 'Pendente',
    approved: 'Aprovada',
    returned: 'Devolvida',
}[status] || status);
</script>

<template>
    <Head title="AccessKey" />

    <div class="p-6 space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">AccessKey</h1>
            <p class="text-sm text-gray-500">
                Dashboard geral do controle de chaves.
            </p>
        </div>

        <div class="grid grid-cols-4 gap-4">
            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total de chaves</p>
                <h2 class="mt-2 text-3xl font-bold">{{ stats.total_keys }}</h2>
            </div>

            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Disponíveis</p>
                <h2 class="mt-2 text-3xl font-bold text-green-600">{{ stats.available_keys }}</h2>
            </div>

            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Retiradas</p>
                <h2 class="mt-2 text-3xl font-bold text-orange-600">{{ stats.borrowed_keys }}</h2>
            </div>

            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Reservas pendentes</p>
                <h2 class="mt-2 text-3xl font-bold text-yellow-600">{{ stats.pending_reservations }}</h2>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4">
            <Link :href="route('keys.index')" class="rounded-xl border bg-white p-5 shadow-sm hover:bg-gray-50">
                <h3 class="font-semibold">Chaves</h3>
                <p class="mt-1 text-sm text-gray-500">Cadastrar, editar, retirar e devolver chaves.</p>
            </Link>

            <Link :href="route('people.index')" class="rounded-xl border bg-white p-5 shadow-sm hover:bg-gray-50">
                <h3 class="font-semibold">Pessoas autorizadas</h3>
                <p class="mt-1 text-sm text-gray-500">Gerenciar quem pode retirar chaves.</p>
            </Link>

            <Link :href="route('responsibles.index')" class="rounded-xl border bg-white p-5 shadow-sm hover:bg-gray-50">
                <h3 class="font-semibold">Responsáveis</h3>
                <p class="mt-1 text-sm text-gray-500">Gerenciar responsáveis pelas chaves.</p>
            </Link>

            <Link :href="route('reservations.index')" class="rounded-xl border bg-white p-5 shadow-sm hover:bg-gray-50">
                <h3 class="font-semibold">Agendamentos</h3>
                <p class="mt-1 text-sm text-gray-500">Reservas e interesses de uso.</p>
            </Link>

            <Link :href="route('movements.index')" class="rounded-xl border bg-white p-5 shadow-sm hover:bg-gray-50">
                <h3 class="font-semibold">Histórico</h3>
                <p class="mt-1 text-sm text-gray-500">Auditoria de retiradas e devoluções.</p>
            </Link>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div class="rounded-xl border bg-white shadow-sm">
                <div class="border-b p-4">
                    <h2 class="font-semibold">Movimentações recentes</h2>
                </div>

                <div class="divide-y">
                    <div
                        v-for="movement in recentMovements"
                        :key="movement.id"
                        class="p-4"
                    >
                        <div class="flex justify-between">
                            <div>
                                <p class="font-medium">
                                    {{ movement.key?.code }} - {{ movement.key?.name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Pessoa: {{ movement.person?.name || '-' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Retirada: {{ formatDate(movement.taken_at) }}
                                </p>
                                <p v-if="movement.returned_at" class="text-sm text-gray-500">
                                    Devolução: {{ formatDate(movement.returned_at) }}
                                </p>
                            </div>

                            <span class="h-fit rounded-full bg-gray-100 px-3 py-1 text-xs">
                                {{ statusLabel(movement.status) }}
                            </span>
                        </div>
                    </div>

                    <div v-if="recentMovements.length === 0" class="p-6 text-center text-gray-500">
                        Nenhuma movimentação encontrada.
                    </div>
                </div>
            </div>

            <div class="rounded-xl border bg-white shadow-sm">
                <div class="border-b p-4">
                    <h2 class="font-semibold">Próximos agendamentos</h2>
                </div>

                <div class="divide-y">
                    <div
                        v-for="reservation in upcomingReservations"
                        :key="reservation.id"
                        class="p-4"
                    >
                        <div class="flex justify-between">
                            <div>
                                <p class="font-medium">
                                    {{ reservation.key?.code }} - {{ reservation.key?.name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Pessoa: {{ reservation.person?.name || '-' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Início: {{ formatDate(reservation.start_at) }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Fim: {{ formatDate(reservation.end_at) }}
                                </p>
                            </div>

                            <span class="h-fit rounded-full bg-yellow-100 px-3 py-1 text-xs text-yellow-700">
                                {{ statusLabel(reservation.status) }}
                            </span>
                        </div>
                    </div>

                    <div v-if="upcomingReservations.length === 0" class="p-6 text-center text-gray-500">
                        Nenhum agendamento próximo.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
