<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Bem-vindo ao AccessKey" />
    <div class="relative min-h-screen bg-slate-900 text-slate-100 flex flex-col justify-between overflow-hidden font-sans select-none">
        
        <!-- Animated Background Glows -->
        <div class="absolute -top-40 -left-40 h-[600px] w-[600px] rounded-full bg-teal-500/10 blur-[120px] pointer-events-none animate-pulse duration-5000"></div>
        <div class="absolute -bottom-40 -right-40 h-[600px] w-[600px] rounded-full bg-emerald-500/10 blur-[120px] pointer-events-none animate-pulse duration-5000"></div>

        <!-- Header -->
        <header class="relative z-10 max-w-7xl w-full mx-auto px-6 py-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-white p-1.5 flex items-center justify-center shadow-lg">
                    <ApplicationLogo />
                </div>
                <span class="text-xl font-bold tracking-wider text-white">AccessKey</span>
            </div>

            <!-- Header Nav -->
            <nav v-if="canLogin" class="flex gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-medium text-sm transition-all shadow-lg shadow-teal-600/20 active:scale-95"
                >
                    Acessar Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium text-sm border border-slate-700 transition-colors"
                    >
                        Entrar
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-medium text-sm transition-all shadow-lg shadow-teal-600/20 active:scale-95"
                    >
                        Criar Conta
                    </Link>
                </template>
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="relative z-10 max-w-5xl w-full mx-auto px-6 py-12 flex-1 flex flex-col justify-center items-center text-center gap-8">
            <div class="space-y-4">
                <!-- Premium Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-teal-400 text-xs font-semibold uppercase tracking-wider mb-2">
                    🔑 Gestão de chaves inteligente
                </div>
                <h1 class="text-4xl sm:text-6xl font-extrabold text-white tracking-tight leading-none">
                    Controle suas chaves com <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-400 via-emerald-400 to-lime-300">segurança e elegância</span>
                </h1>
                <p class="max-w-2xl mx-auto text-base sm:text-lg text-slate-400 leading-relaxed">
                    Esqueça planilhas e anotações em papel. O AccessKey oferece um mural visual dinâmico, histórico completo de movimentações e gestão automatizada de responsáveis para sua empresa ou condomínio.
                </p>
            </div>

            <!-- Call to Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="px-8 py-4 rounded-2xl bg-teal-600 hover:bg-teal-500 text-white font-semibold shadow-lg shadow-teal-600/25 transition-all active:scale-95"
                >
                    Ir para o Painel
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="px-8 py-4 rounded-2xl bg-teal-600 hover:bg-teal-500 text-white font-semibold shadow-lg shadow-teal-600/25 transition-all active:scale-95"
                    >
                        Fazer Login
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-8 py-4 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold border border-slate-700 transition-colors"
                    >
                        Criar Conta Grátis
                    </Link>
                </template>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full mt-12 text-left">
                <!-- Feature 1 -->
                <div class="p-6 rounded-2xl border border-slate-800 bg-slate-900/50 backdrop-blur-md hover:border-slate-700 transition-colors space-y-3">
                    <div class="h-10 w-10 rounded-xl bg-teal-500/10 flex items-center justify-center text-teal-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Mural Interativo</h3>
                    <p class="text-sm text-slate-400">
                        Representação visual inteligente simulando um claviculário real. Veja instantaneamente quais chaves estão disponíveis ou retiradas.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 rounded-2xl border border-slate-800 bg-slate-900/50 backdrop-blur-md hover:border-slate-700 transition-colors space-y-3">
                    <div class="h-10 w-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Auditoria de Movimentos</h3>
                    <p class="text-sm text-slate-400">
                        Histórico e logs em tempo real sobre quem pegou qual chave, quando, e quem realizou o recebimento da devolução.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 rounded-2xl border border-slate-800 bg-slate-900/50 backdrop-blur-md hover:border-slate-700 transition-colors space-y-3">
                    <div class="h-10 w-10 rounded-xl bg-lime-500/10 flex items-center justify-center text-lime-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Agendamento Prévio</h3>
                    <p class="text-sm text-slate-400">
                        Reserve salas de reunião, veículos ou áreas restritas agendando a chave com antecedência para evitar conflitos de horários.
                    </p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative z-10 py-8 border-t border-slate-800 text-center text-xs text-slate-500 max-w-7xl w-full mx-auto px-6">
            &copy; 2026 AccessKey. Todos os direitos reservados.
        </footer>
    </div>
</template>
