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

const formatRelativeTime = (value) => {
    if (!value) return '';
    const now = new Date();
    const date = new Date(value);
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);

    if (diffMins < 1) return 'Agora mesmo';
    if (diffMins < 60) return `Há ${diffMins} min`;
    const diffHours = Math.floor(diffMins / 60);
    if (diffHours < 24) return `Há ${diffHours}h`;
    const diffDays = Math.floor(diffHours / 24);
    if (diffDays < 7) return `Há ${diffDays}d`;
    return formatDate(value);
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

const statusColor = (status) => ({
    available: 'bg-green-100 text-green-700',
    borrowed: 'bg-orange-100 text-orange-700',
    maintenance: 'bg-red-100 text-red-700',
    inactive: 'bg-gray-100 text-gray-700',
    pending: 'bg-yellow-100 text-yellow-700',
    approved: 'bg-blue-100 text-blue-700',
    returned: 'bg-indigo-100 text-indigo-700',
}[status] || 'bg-gray-100 text-gray-700');

const statCards = [
    {
        label: 'Total de chaves',
        value: props.stats.total_keys,
        color: 'text-gray-900',
        icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'
    },
    {
        label: 'Disponíveis',
        value: props.stats.available_keys,
        color: 'text-green-600',
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
    },
    {
        label: 'Retiradas',
        value: props.stats.borrowed_keys,
        color: 'text-orange-600',
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
    },
    {
        label: 'Reservas pendentes',
        value: props.stats.pending_reservations,
        color: 'text-yellow-600',
        icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'
    },
];

const navigationCards = [
    {
        route: 'keys.index',
        title: 'Chaves',
        description: 'Cadastrar, editar, retirar e devolver chaves.',
        icon: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
        color: 'bg-blue-50 text-blue-600'
    },
    {
        route: 'people.index',
        title: 'Pessoas autorizadas',
        description: 'Gerenciar quem pode retirar chaves.',
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        color: 'bg-green-50 text-green-600'
    },
    {
        route: 'responsibles.index',
        title: 'Responsáveis',
        description: 'Gerenciar responsáveis pelas chaves.',
        icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        color: 'bg-purple-50 text-purple-600'
    },
    {
        route: 'reservations.index',
        title: 'Agendamentos',
        description: 'Reservas e interesses de uso.',
        icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        color: 'bg-pink-50 text-pink-600'
    },
    {
        route: 'movements.index',
        title: 'Histórico',
        description: 'Auditoria de retiradas e devoluções.',
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        color: 'bg-indigo-50 text-indigo-600'
    },
];
</script>

<template>
    <Head title="AccessKey" />

    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <!-- Header -->
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">AccessKey</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Dashboard geral do controle de chaves
                </p>
            </div>
            <div class="mt-4 flex gap-2 sm:mt-0">
                <Link
                    :href="route('keys.index')"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 active:scale-95"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nova retirada
                </Link>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div
                v-for="stat in statCards"
                :key="stat.label"
                class="group relative rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition-all hover:shadow-md sm:p-6"
            >
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500">{{ stat.label }}</p>
                        <h2 :class="[stat.color, 'mt-2 text-2xl font-bold sm:text-3xl']">
                            {{ stat.value }}
                        </h2>
                    </div>
                    <div :class="[stat.color, 'rounded-lg p-2 opacity-20 group-hover:opacity-100 transition-opacity']">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Cards -->
        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-5">
            <Link
                v-for="card in navigationCards"
                :key="card.route"
                :href="route(card.route)"
                class="group flex flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-gray-300 hover:shadow-md active:scale-[0.98] sm:p-6"
            >
                <div :class="[card.color, 'rounded-lg p-2.5 w-fit transition-transform group-hover:scale-110']">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">{{ card.title }}</h3>
                    <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ card.description }}</p>
                </div>
            </Link>
        </div>

        <!-- Recent Activity & Upcoming -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Recent Movements -->
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 p-4 sm:p-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Movimentações recentes</h2>
                        <p class="text-sm text-gray-500">Últimas retiradas e devoluções</p>
                    </div>
                    <Link
                        :href="route('movements.index')"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        Ver todas
                    </Link>
                </div>

                <div class="divide-y divide-gray-100">
                    <div
                        v-for="movement in recentMovements"
                        :key="movement.id"
                        class="group flex flex-col gap-3 p-4 transition-colors hover:bg-gray-50 sm:flex-row sm:items-center sm:justify-between sm:p-4"
                    >
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100">
                                <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 truncate">
                                    {{ movement.key?.code }} - {{ movement.key?.name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ movement.person?.name || 'N/A' }}
                                </p>
                                <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400">
                                    <span>Retirada: {{ formatRelativeTime(movement.taken_at) }}</span>
                                    <span v-if="movement.returned_at">Devolução: {{ formatRelativeTime(movement.returned_at) }}</span>
                                </div>
                            </div>
                        </div>
                        <span :class="[statusColor(movement.status), 'shrink-0 self-start rounded-full px-2.5 py-1 text-xs font-medium sm:self-center']">
                            {{ statusLabel(movement.status) }}
                        </span>
                    </div>

                    <div v-if="recentMovements.length === 0" class="flex flex-col items-center justify-center p-12 text-center">
                        <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="mt-3 text-sm font-medium text-gray-900">Nenhuma movimentação</p>
                        <p class="mt-1 text-sm text-gray-500">As movimentações recentes aparecerão aqui.</p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Reservations -->
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 p-4 sm:p-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Próximos agendamentos</h2>
                        <p class="text-sm text-gray-500">Reservas agendadas para os próximos dias</p>
                    </div>
                    <Link
                        :href="route('reservations.index')"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        Ver todos
                    </Link>
                </div>

                <div class="divide-y divide-gray-100">
                    <div
                        v-for="reservation in upcomingReservations"
                        :key="reservation.id"
                        class="group flex flex-col gap-3 p-4 transition-colors hover:bg-gray-50 sm:flex-row sm:items-center sm:justify-between sm:p-4"
                    >
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-yellow-50">
                                <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 truncate">
                                    {{ reservation.key?.code }} - {{ reservation.key?.name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ reservation.person?.name || 'N/A' }}
                                </p>
                                <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400">
                                    <span>Início: {{ formatDate(reservation.start_at) }}</span>
                                    <span>Fim: {{ formatDate(reservation.end_at) }}</span>
                                </div>
                            </div>
                        </div>
                        <span :class="[statusColor(reservation.status), 'shrink-0 self-start rounded-full px-2.5 py-1 text-xs font-medium sm:self-center']">
                            {{ statusLabel(reservation.status) }}
                        </span>
                    </div>

                    <div v-if="upcomingReservations.length === 0" class="flex flex-col items-center justify-center p-12 text-center">
                        <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-3 text-sm font-medium text-gray-900">Nenhum agendamento</p>
                        <p class="mt-1 text-sm text-gray-500">Os próximos agendamentos aparecerão aqui.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
