<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    responsibles: Object,
    filters: Object,
});

const showModal = ref(false);
const editingResponsible = ref(null);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    department: '',
    active: true,
});

const filters = useForm({
    search: props.filters?.search || '',
    active: props.filters?.active || '',
});

const openCreate = () => {
    editingResponsible.value = null;
    form.reset();
    form.active = true;
    showModal.value = true;
};

const openEdit = (responsible) => {
    editingResponsible.value = responsible;

    form.name = responsible.name;
    form.email = responsible.email || '';
    form.phone = responsible.phone || '';
    form.department = responsible.department || '';
    form.active = responsible.active;

    showModal.value = true;
};

const save = () => {
    if (editingResponsible.value) {
        form.put(route('responsibles.update', editingResponsible.value.id), {
            preserveScroll: true,
            onSuccess: () => showModal.value = false,
        });
        return;
    }

    form.post(route('responsibles.store'), {
        preserveScroll: true,
        onSuccess: () => showModal.value = false,
    });
};

const destroyResponsible = (responsible) => {
    if (!confirm('Deseja excluir este responsável?')) return;

    router.delete(route('responsibles.destroy', responsible.id), {
        preserveScroll: true,
    });
};

const applyFilters = () => {
    router.get(route('responsibles.index'), {
        search: filters.search,
        active: filters.active,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Responsáveis" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Responsáveis</h1>
                <p class="mt-1 text-sm text-slate-500">Cadastro dos responsáveis diretos por grupos ou setores de chaves.</p>
            </div>

            <button
                @click="openCreate"
                class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-teal-600/20 transition-all hover:bg-teal-500 active:scale-95"
            >
                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Novo Responsável
            </button>
        </div>

        <!-- Filters Block -->
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center justify-between">
                <div class="flex flex-wrap items-center gap-3 flex-1">
                    <div class="w-full sm:flex-1">
                        <input
                            v-model="filters.search"
                            placeholder="Buscar por nome, email ou setor..."
                            class="block w-full rounded-xl border-slate-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800 placeholder-slate-400"
                        />
                    </div>

                    <div class="w-full sm:w-48">
                        <select
                            v-model="filters.active"
                            class="block w-full rounded-xl border-slate-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800"
                        >
                            <option value="">Todos os status</option>
                            <option value="1">Ativos</option>
                            <option value="0">Inativos</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button
                        @click="applyFilters"
                        class="flex-1 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-slate-800 sm:flex-none"
                    >
                        Filtrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table (Desktop) -->
        <div class="hidden overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:block">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nome</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Email</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Telefone</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Setor</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">
                        <tr v-for="responsible in responsibles.data" :key="responsible.id" class="transition-colors hover:bg-slate-50/60">
                            <td class="whitespace-nowrap px-6 py-4 text-slate-900 font-semibold">{{ responsible.name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">{{ responsible.email || '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">{{ responsible.phone || '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                <span class="bg-slate-100 rounded-lg px-2 py-1 text-xs font-medium text-slate-700">{{ responsible.department || '-' }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span
                                    class="inline-flex rounded-full border px-2.5 py-0.5 text-xs font-semibold"
                                    :class="responsible.active ? 'bg-green-50 text-green-700 border-green-200' : 'bg-slate-50 text-slate-600 border-slate-200'"
                                >
                                    {{ responsible.active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        @click="openEdit(responsible)"
                                        class="rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition-all hover:bg-slate-50 active:scale-95"
                                    >
                                        Editar
                                    </button>

                                    <button
                                        @click="destroyResponsible(responsible)"
                                        class="rounded-xl bg-red-50 text-red-600 px-3 py-1.5 text-xs font-semibold transition-all hover:bg-red-100 active:scale-95"
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="responsibles.data.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="text-sm font-semibold text-slate-900">Nenhum responsável cadastrado</p>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ filters.search || filters.active ? 'Ajuste os filtros ou ' : '' }}cadastre um responsável para começar.
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
                v-for="responsible in responsibles.data"
                :key="responsible.id"
                class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
            >
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-950">{{ responsible.name }}</span>
                            <span
                                class="inline-flex rounded-full border px-2 py-0.5 text-xs font-semibold"
                                :class="responsible.active ? 'bg-green-50 text-green-700 border-green-200' : 'bg-slate-50 text-slate-600 border-slate-200'"
                            >
                                {{ responsible.active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                        <div class="mt-2 space-y-1 text-xs text-slate-500">
                            <p><span class="font-semibold text-slate-700">Setor:</span> {{ responsible.department || '-' }}</p>
                            <p><span class="font-semibold text-slate-700">Email:</span> {{ responsible.email || '-' }}</p>
                            <p><span class="font-semibold text-slate-700">Telefone:</span> {{ responsible.phone || '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex gap-2 border-t border-slate-100 pt-3">
                    <button
                        @click="openEdit(responsible)"
                        class="flex-1 rounded-xl border border-slate-200 py-2 text-xs font-semibold text-slate-700 transition-all active:scale-95"
                    >
                        Editar
                    </button>

                    <button
                        @click="destroyResponsible(responsible)"
                        class="rounded-xl bg-red-50 text-red-600 px-3 py-2 text-xs font-semibold transition-all active:scale-95"
                    >
                        Excluir
                    </button>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="responsibles.links?.length > 3" class="flex items-center justify-center gap-1 overflow-x-auto py-2">
            <button
                v-for="link in responsibles.links"
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
                        {{ editingResponsible ? 'Editar Responsável' : 'Novo Responsável' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ editingResponsible ? 'Altere as informações do responsável selecionado.' : 'Cadastre um novo responsável no sistema para gerenciar grupos de chaves.' }}
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
                        <label class="text-sm font-semibold text-slate-700">Nome Completo <span class="text-red-500">*</span></label>
                        <input
                            v-model="form.name"
                            class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800 placeholder-slate-400"
                            placeholder="Nome do responsável"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">E-mail de contato</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800 placeholder-slate-400"
                            placeholder="exemplo@empresa.com"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Telefone / Ramal</label>
                        <input
                            v-model="form.phone"
                            class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800 placeholder-slate-400"
                            placeholder="Ex: (11) 99999-9999"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Setor / Departamento</label>
                        <input
                            v-model="form.department"
                            class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-slate-800 placeholder-slate-400"
                            placeholder="Ex: TI, Engenharia, Manutenção..."
                        />
                    </div>

                    <div class="flex items-center gap-3 pt-7">
                        <input
                            id="active"
                            v-model="form.active"
                            type="checkbox"
                            class="rounded border-slate-300 text-teal-600 focus:ring-teal-500 h-5 w-5 transition-all cursor-pointer"
                        />
                        <label for="active" class="text-sm font-semibold text-slate-700 cursor-pointer selection:bg-transparent">Este responsável está ativo?</label>
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
                        <span v-else>Salvar Responsável</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
