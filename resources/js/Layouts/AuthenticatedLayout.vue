<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';

const showingSidebarMobile = ref(false);

const navLinks = [
    { name: 'Dashboard', routeName: 'dashboard', activePrefix: 'dashboard', icon: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z' },
    { name: 'Mural de Chaves', routeName: 'keys.index', activePrefix: 'keys', icon: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z' },
    { name: 'Pessoas Autorizadas', routeName: 'people.index', activePrefix: 'people', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' },
    { name: 'Responsáveis', routeName: 'responsibles.index', activePrefix: 'responsibles', icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' },
    { name: 'Agendamentos', routeName: 'reservations.index', activePrefix: 'reservations', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
    { name: 'Histórico', routeName: 'movements.index', activePrefix: 'movements', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
];

const isLinkActive = (prefix) => {
    return route().current().startsWith(prefix);
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex">
        <!-- Sidebar for Desktop -->
        <aside class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 bg-slate-900 border-r border-slate-800">
            <!-- Brand header -->
            <div class="flex h-16 items-center px-6 gap-3 border-b border-slate-800">
                <div class="h-9 w-9 rounded-lg bg-white p-1 flex items-center justify-center">
                    <ApplicationLogo class="h-7 w-7" />
                </div>
                <span class="text-lg font-bold text-white tracking-wide">AccessKey</span>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 space-y-1.5 px-4 py-6 overflow-y-auto">
                <Link
                    v-for="link in navLinks"
                    :key="link.routeName"
                    :href="route(link.routeName)"
                    class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200"
                    :class="[
                        isLinkActive(link.activePrefix)
                            ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/20'
                            : 'text-slate-400 hover:bg-slate-800 hover:text-white'
                    ]"
                >
                    <svg
                        class="h-5 w-5 transition-transform group-hover:scale-105"
                        :class="[isLinkActive(link.activePrefix) ? 'text-white' : 'text-slate-400 group-hover:text-white']"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="link.icon" />
                    </svg>
                    {{ link.name }}
                </Link>
            </nav>

            <!-- Sidebar Footer Profile -->
            <div class="p-4 border-t border-slate-800 flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ $page.props.auth.user.name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ $page.props.auth.user.email }}</p>
                </div>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="p-2 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-xl transition-colors"
                    title="Sair"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </Link>
            </div>
        </aside>

        <!-- Sidebar for Mobile (Drawer overlay) -->
        <div v-if="showingSidebarMobile" class="fixed inset-0 z-40 md:hidden flex">
            <!-- Backdrop Overlay -->
            <div
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                @click="showingSidebarMobile = false"
            ></div>

            <!-- Drawer Container -->
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-slate-900 border-r border-slate-800 animate-slide-in">
                <!-- Close Button -->
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button
                        @click="showingSidebarMobile = false"
                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white bg-slate-800 text-white"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Brand header -->
                <div class="flex h-16 items-center px-6 gap-3 border-b border-slate-800">
                    <div class="h-9 w-9 rounded-lg bg-white p-1 flex items-center justify-center">
                        <ApplicationLogo class="h-7 w-7" />
                    </div>
                    <span class="text-lg font-bold text-white tracking-wide">AccessKey</span>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 space-y-1.5 px-4 py-6 overflow-y-auto">
                    <Link
                        v-for="link in navLinks"
                        :key="link.routeName"
                        :href="route(link.routeName)"
                        @click="showingSidebarMobile = false"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200"
                        :class="[
                            isLinkActive(link.activePrefix)
                                ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/20'
                                : 'text-slate-400 hover:bg-slate-800 hover:text-white'
                        ]"
                    >
                        <svg
                            class="h-5 w-5"
                            :class="[isLinkActive(link.activePrefix) ? 'text-white' : 'text-slate-400']"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="link.icon" />
                        </svg>
                        {{ link.name }}
                    </Link>
                </nav>

                <!-- Sidebar Footer Profile -->
                <div class="p-4 border-t border-slate-800 flex items-center gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ $page.props.auth.user.name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $page.props.auth.user.email }}</p>
                    </div>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="p-2 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-xl transition-colors"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col md:pl-64 min-w-0">
            <!-- Topbar Header -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10">
                <div class="flex items-center gap-4">
                    <!-- Hamburger for Mobile -->
                    <button
                        @click="showingSidebarMobile = true"
                        class="md:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 transition-colors focus:outline-none"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <!-- Title slot placeholder -->
                    <div class="flex items-center">
                        <slot name="header" />
                    </div>
                </div>

                <!-- Right profile controls -->
                <div class="flex items-center gap-4">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 rounded-xl border border-slate-200 transition-colors focus:outline-none"
                            >
                                <div class="h-7 w-7 rounded-full bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs uppercase shadow-inner">
                                    {{ $page.props.auth.user.name.substring(0, 2) }}
                                </div>
                                <span class="hidden sm:inline">{{ $page.props.auth.user.name }}</span>
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Meu Perfil
                            </DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 border-t border-slate-100 hover:bg-red-50">
                                <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Sair
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8 max-w-7xl w-full mx-auto">
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
@keyframes slide-in {
    from { transform: translateX(-100%); }
    to { transform: translateX(0); }
}
.animate-slide-in {
    animation: slide-in 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
