<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="relative flex min-h-screen flex-col items-center justify-center bg-slate-50 px-4 py-12 sm:px-6 lg:px-8 overflow-hidden select-none"
    >
        <!-- Background Glowing Circles -->
        <div class="absolute -top-40 -left-40 h-[600px] w-[600px] rounded-full bg-gradient-to-tr from-teal-300/30 to-emerald-300/30 blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 h-[600px] w-[600px] rounded-full bg-gradient-to-tr from-pink-300/25 to-violet-300/25 blur-3xl"></div>

        <div class="relative z-10 w-full max-w-md space-y-8">
            <!-- Logo Section -->
            <div class="flex flex-col items-center">
                <Link href="/" class="group transition-transform hover:scale-105 duration-300">
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white shadow-lg border border-slate-100 transition-shadow group-hover:shadow-xl">
                        <ApplicationLogo class="h-16 w-16" />
                    </div>
                </Link>
                <h2 class="mt-6 text-center text-3xl font-extrabold tracking-tight text-slate-900">
                    AccessKey
                </h2>
                <p class="mt-2 text-center text-sm text-slate-500">
                    Sistema inteligente de controle de chaves
                </p>
            </div>

            <!-- Main Card (Glassmorphic vibe) -->
            <div
                class="rounded-3xl border border-white/60 bg-white/80 p-8 shadow-2xl backdrop-blur-md"
            >
                <slot />
            </div>
        </div>
    </div>
</template>
