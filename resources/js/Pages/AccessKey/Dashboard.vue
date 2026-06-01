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
    available: 'bg-teal-50 text-teal-700 border-teal-200',
    borrowed: 'bg-orange-50 text-orange-700 border-orange-200',
    maintenance: 'bg-red-50 text-red-700 border-red-200',
    inactive: 'bg-slate-50 text-slate-600 border-slate-200',
    pending: 'bg-yellow-50 text-yellow-700 border-yellow-200',
    approved: 'bg-teal-50 text-teal-700 border-teal-200',
    returned: 'bg-slate-100 text-slate-700 border-slate-300',
}[status] || 'bg-slate-50 text-slate-600 border-slate-200');

const statCards = [
    {
        label: 'Total de chaves',
        value: props.stats.total_keys,
        colorClass: 'text-teal-600 bg-teal-50 border-teal-200',
        icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'
    },
    {
        label: 'Disponíveis',
        value: props.stats.available_keys,
        colorClass: 'text-emerald-600 bg-emerald-50 border-emerald-200',
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
    },
    {
        label: 'Retiradas',
        value: props.stats.borrowed_keys,
        colorClass: 'text-orange-600 bg-orange-50 border-orange-200',
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
    },
    {
        label: 'Reservas pendentes',
        value: props.stats.pending_reservations,
        colorClass: 'text-yellow-600 bg-yellow-50 border-yellow-200',
        icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'
    },
];

const navigationCards = [
    {
        route: 'keys.index',
        title: 'Mural / Claviculário',
        description: 'Retire ou devolva chaves de forma rápida e visual.',
        icon: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
        color: 'bg-teal-50 text-teal-700 border border-teal-100'
    },
    {
        route: 'people.index',
        title: 'Pessoas Autorizadas',
        description: 'Gerencie quem tem autorização para pegar chaves.',
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        color: 'bg-green-50 text-green-700 border border-green-100'
    },
    {
        route: 'reservations.index',
        title: 'Agendamentos',
        description: 'Reserve horários e evite conflitos de uso.',
        icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        color: 'bg-yellow-50 text-yellow-700 border border-yellow-100'
    },
    {
        route: 'movements.index',
        title: 'Auditoria de Logs',
        description: 'Histórico detalhado de todas as retiradas e devoluções.',
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        color: 'bg-slate-100 text-slate-700 border border-slate-200'
    },
];

const getGreeting = () => {
    const hours = new Date().getHours();
    if (hours < 12) return 'Bom dia';
    if (hours < 18) return 'Boa tarde';
    return 'Boa noite';
};
</script>

<template>
    <Head title="Painel de Controle" />

    <div class="space-y-6">
        <!-- Welcoming Greeting Banner -->
        <div class="relative bg-gradient-to-r from-slate-900 via-slate-800 to-teal-950 text-white rounded-3xl p-6 md:p-8 shadow-xl overflow-hidden border border-slate-800">
            <!-- decorative blur circles inside banner -->
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-teal-500/20 blur-3xl"></div>
            
            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 border border-teal-400/20 text-teal-300 text-xs font-semibold uppercase tracking-wider">
                    ✨ AccessKey v2.0
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                    {{ getGreeting() }}, {{ $page.props.auth.user.name }}!
                </h1>
                <p class="max-w-xl text-slate-400 text-sm leading-relaxed">
                    Atualmente o claviculário possui <strong class="text-teal-300 font-bold">{{ stats.available_keys }}</strong> chaves disponíveis de um total de <strong class="text-white font-bold">{{ stats.total_keys }}</strong> chaves cadastradas.
                </p>
                
                <div class="flex flex-wrap gap-3 pt-2">
                    <Link
                        :href="route('keys.index')"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-semibold text-xs px-4 py-2.5 shadow-md shadow-teal-900/40 transition-all active:scale-95"
                    >
                        🔑 Abrir Claviculário
                    </Link>
                    <Link
                        :href="route('reservations.index')"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs px-4 py-2.5 transition-colors"
                    >
                        📅 Agendar Chave
                    </Link>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div
                v-for="stat in statCards"
                :key="stat.label"
                class="group relative rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:shadow-md"
            >
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">{{ stat.label }}</p>
                        <h2 class="mt-2 text-3xl font-extrabold text-slate-900 tracking-tight">
                            {{ stat.value }}
                        </h2>
                    </div>
                    <div :class="[stat.colorClass, 'rounded-xl p-3 border shadow-sm transition-transform group-hover:scale-105']">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Shortcut Cards -->
        <div class="space-y-3">
            <h2 class="text-base font-bold text-slate-900 px-1">Painel Administrativo</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <Link
                    v-for="card in navigationCards"
                    :key="card.route"
                    :href="route(card.route)"
                    class="group flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:border-slate-400 hover:shadow-md active:scale-[0.99]"
                >
                    <div :class="[card.color, 'rounded-xl p-3 shadow-inner shrink-0 transition-transform group-hover:rotate-6']">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-sm text-slate-950 group-hover:text-teal-600 transition-colors">{{ card.title }}</h3>
                        <p class="text-xs text-slate-500 leading-normal">{{ card.description }}</p>
                    </div>
                </Link>
            </div>
        </div>

        <!-- Recent Activity & Upcoming Reservations -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            
            <!-- Recent Movements (Audit Timeline) -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden flex flex-col">
                <div class="flex items-center justify-between border-b border-slate-100 p-5 bg-slate-50/50">
                    <div>
                        <h2 class="text-base font-bold text-slate-950">Movimentações Recentes</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Últimas devoluções e retiradas de chaves</p>
                    </div>
                    <Link
                        :href="route('movements.index')"
                        class="text-xs font-semibold text-teal-600 hover:text-teal-700 border border-slate-200 bg-white rounded-lg px-2.5 py-1.5 transition-colors shadow-sm"
                    >
                        Ver histórico
                    </Link>
                </div>

                <div class="divide-y divide-slate-200 flex-1">
                    <div
                        v-for="movement in recentMovements"
                        :key="movement.id"
                        class="p-4 flex items-center justify-between gap-4 transition-colors hover:bg-slate-50/30"
                    >
                        <div class="flex items-center gap-3.5 min-w-0">
                            <!-- key status circle -->
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-50 border border-slate-200 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-sm text-slate-950 truncate">
                                    {{ movement.key?.code }} - {{ movement.key?.name }}
                                </p>
                                <p class="text-xs text-slate-500 font-medium truncate mt-0.5">
                                    {{ movement.person?.name || 'N/A' }}
                                </p>
                                <div class="mt-1 flex gap-3 text-[10px] text-slate-400">
                                    <span>Retirada: {{ formatRelativeTime(movement.taken_at) }}</span>
                                    <span v-if="movement.returned_at" class="border-l border-slate-200 pl-3">Devolvida: {{ formatRelativeTime(movement.returned_at) }}</span>
                                </div>
                            </div>
                        </div>
                        <span :class="[statusColor(movement.status), 'shrink-0 rounded-full border px-2.5 py-0.5 text-xs font-semibold']">
                            {{ statusLabel(movement.status) }}
                        </span>
                    </div>

                    <div v-if="recentMovements.length === 0" class="flex flex-col items-center justify-center p-12 text-center text-slate-400 h-full">
                        <svg class="h-12 w-12 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-sm font-semibold text-slate-900">Sem registros recentes</p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Reservations -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden flex flex-col">
                <div class="flex items-center justify-between border-b border-slate-100 p-5 bg-slate-50/50">
                    <div>
                        <h2 class="text-base font-bold text-slate-950">Agendamentos Ativos</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Reservas aprovadas e pendentes para chaves</p>
                    </div>
                    <Link
                        :href="route('reservations.index')"
                        class="text-xs font-semibold text-teal-600 hover:text-teal-700 border border-slate-200 bg-white rounded-lg px-2.5 py-1.5 transition-colors shadow-sm"
                    >
                        Ver agendamentos
                    </Link>
                </div>

                <div class="divide-y divide-slate-200 flex-1">
                    <div
                        v-for="reservation in upcomingReservations"
                        :key="reservation.id"
                        class="p-4 flex items-center justify-between gap-4 transition-colors hover:bg-slate-50/30"
                    >
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-sm text-slate-950 truncate">
                                    {{ reservation.key?.code }} - {{ reservation.key?.name }}
                                </p>
                                <p class="text-xs text-slate-500 font-medium truncate mt-0.5">
                                    {{ reservation.person?.name || 'N/A' }}
                                </p>
                                <div class="mt-1 flex flex-wrap gap-x-3 gap-y-0.5 text-[10px] text-slate-400">
                                    <span>Início: {{ formatDate(reservation.start_at) }}</span>
                                    <span class="border-l border-slate-200 pl-3">Fim: {{ formatDate(reservation.end_at) }}</span>
                                </div>
                            </div>
                        </div>
                        <span :class="[statusColor(reservation.status), 'shrink-0 rounded-full border px-2.5 py-0.5 text-xs font-semibold']">
                            {{ statusLabel(reservation.status) }}
                        </span>
                    </div>

                    <div v-if="upcomingReservations.length === 0" class="flex flex-col items-center justify-center p-12 text-center text-slate-400 h-full">
                        <svg class="h-12 w-12 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm font-semibold text-slate-900">Sem agendamentos futuros</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
