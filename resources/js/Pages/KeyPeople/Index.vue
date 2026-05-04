<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    people: Object,
    filters: Object,
});

const showModal = ref(false);
const editingPerson = ref(null);

const form = useForm({
    name: '',
    document: '',
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
    editingPerson.value = null;
    form.reset();
    form.active = true;
    showModal.value = true;
};

const openEdit = (person) => {
    editingPerson.value = person;

    form.name = person.name;
    form.document = person.document || '';
    form.email = person.email || '';
    form.phone = person.phone || '';
    form.department = person.department || '';
    form.active = person.active;

    showModal.value = true;
};

const save = () => {
    if (editingPerson.value) {
        form.put(route('people.update', editingPerson.value.id), {
            preserveScroll: true,
            onSuccess: () => showModal.value = false,
        });
        return;
    }

    form.post(route('people.store'), {
        preserveScroll: true,
        onSuccess: () => showModal.value = false,
    });
};

const destroyPerson = (person) => {
    if (!confirm('Deseja excluir esta pessoa?')) return;

    router.delete(route('people.destroy', person.id), {
        preserveScroll: true,
    });
};

const applyFilters = () => {
    router.get(route('people.index'), {
        search: filters.search,
        active: filters.active,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Pessoas autorizadas" />

    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Pessoas autorizadas</h1>
                <p class="text-sm text-gray-500">Cadastro de pessoas que podem retirar chaves.</p>
            </div>

            <button @click="openCreate" class="rounded-lg bg-blue-600 px-4 py-2 text-white">
                Nova pessoa
            </button>
        </div>

        <div class="rounded-xl border bg-white p-4 flex gap-3">
            <input
                v-model="filters.search"
                placeholder="Buscar por nome, documento, email..."
                class="w-full rounded-lg border-gray-300"
            />

            <select v-model="filters.active" class="rounded-lg border-gray-300">
                <option value="">Todos</option>
                <option value="1">Ativos</option>
                <option value="0">Inativos</option>
            </select>

            <button @click="applyFilters" class="rounded-lg bg-gray-900 px-4 py-2 text-white">
                Filtrar
            </button>
        </div>

        <div class="overflow-hidden rounded-xl border bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="p-4">Nome</th>
                        <th class="p-4">Documento</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Telefone</th>
                        <th class="p-4">Setor</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="person in people.data" :key="person.id" class="border-t">
                        <td class="p-4 font-medium">{{ person.name }}</td>
                        <td class="p-4">{{ person.document || '-' }}</td>
                        <td class="p-4">{{ person.email || '-' }}</td>
                        <td class="p-4">{{ person.phone || '-' }}</td>
                        <td class="p-4">{{ person.department || '-' }}</td>
                        <td class="p-4">
                            <span
                                class="rounded-full px-3 py-1 text-xs font-medium"
                                :class="person.active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'"
                            >
                                {{ person.active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end gap-2">
                                <button @click="openEdit(person)" class="rounded-lg bg-blue-600 px-3 py-1.5 text-white">
                                    Editar
                                </button>

                                <button @click="destroyPerson(person)" class="rounded-lg bg-red-600 px-3 py-1.5 text-white">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="people.data.length === 0">
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            Nenhuma pessoa encontrada.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex gap-2">
            <button
                v-for="link in people.links"
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
                {{ editingPerson ? 'Editar pessoa' : 'Nova pessoa' }}
            </h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Nome</label>
                    <input v-model="form.name" class="mt-1 w-full rounded-lg border-gray-300" />
                    <p v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Documento</label>
                    <input v-model="form.document" class="mt-1 w-full rounded-lg border-gray-300" />
                </div>

                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input v-model="form.email" class="mt-1 w-full rounded-lg border-gray-300" />
                </div>

                <div>
                    <label class="text-sm font-medium">Telefone</label>
                    <input v-model="form.phone" class="mt-1 w-full rounded-lg border-gray-300" />
                </div>

                <div>
                    <label class="text-sm font-medium">Setor</label>
                    <input v-model="form.department" class="mt-1 w-full rounded-lg border-gray-300" />
                </div>

                <div class="flex items-center gap-2 pt-6">
                    <input id="active" v-model="form.active" type="checkbox" class="rounded border-gray-300" />
                    <label for="active" class="text-sm font-medium">Ativo</label>
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
