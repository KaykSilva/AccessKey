<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    keys: Object,
    filters: Object,
    responsibles: Array,
    people: Array,
});

const showCreateModal = ref(false);
const showEditModal = ref(false);
const showCheckoutModal = ref(false);
const showReturnModal = ref(false);

const selectedKey = ref(null);

const form = useForm({
    responsible_id: '',
    name: '',
    code: '',
    description: '',
    location: '',
    status: 'available',
    active: true,
});

const checkoutForm = useForm({
    person_id: '',
    take_notes: '',
});

const returnForm = useForm({
    delivered_by_user_id: '',
    return_notes: '',
});

const filters = useForm({
    status: props.filters?.status || '',
    active: props.filters?.active || '',
});

const openCreate = () => {
    form.reset();
    form.status = 'available';
    form.active = true;
    showCreateModal.value = true;
};

const openEdit = (key) => {
    selectedKey.value = key;

    form.responsible_id = key.responsible_id || '';
    form.name = key.name;
    form.code = key.code;
    form.description = key.description || '';
    form.location = key.location || '';
    form.status = key.status;
    form.active = key.active;

    showEditModal.value = true;
};

const openCheckout = (key) => {
    selectedKey.value = key;
    checkoutForm.reset();
    showCheckoutModal.value = true;
};

const openReturn = (key) => {
    selectedKey.value = key;
    returnForm.reset();
    showReturnModal.value = true;
};

const store = () => {
    form.post(route('keys.store'), {
        preserveScroll: true,
        onSuccess: () => showCreateModal.value = false,
    });
};

const update = () => {
    form.put(route('keys.update', selectedKey.value.id), {
        preserveScroll: true,
        onSuccess: () => showEditModal.value = false,
    });
};

const destroyKey = (key) => {
    if (!confirm('Deseja excluir esta chave?')) return;

    router.delete(route('keys.destroy', key.id), {
        preserveScroll: true,
    });
};

const checkout = () => {
    checkoutForm.post(route('keys.checkout', selectedKey.value.id), {
        preserveScroll: true,
        onSuccess: () => showCheckoutModal.value = false,
    });
};

const returnKey = () => {
    returnForm.post(route('keys.return', selectedKey.value.id), {
        preserveScroll: true,
        onSuccess: () => showReturnModal.value = false,
    });
};

const applyFilters = () => {
    router.get(route('keys.index'), {
        status: filters.status,
        active: filters.active,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const statusLabel = (status) => ({
    available: 'Disponível',
    borrowed: 'Retirada',
    maintenance: 'Manutenção',
    inactive: 'Inativa',
}[status] || status);

const statusClass = (status) => ({
    available: 'bg-green-100 text-green-700',
    borrowed: 'bg-orange-100 text-orange-700',
    maintenance: 'bg-yellow-100 text-yellow-700',
    inactive: 'bg-gray-100 text-gray-700',
}[status] || 'bg-gray-100 text-gray-700');
</script>

<template>
    <Head title="Chaves" />

    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Chaves</h1>
                <p class="text-sm text-gray-500">Controle de chaves cadastradas, retiradas e devoluções.</p>
            </div>

            <button
                @click="openCreate"
                class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            >
                Nova chave
            </button>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm border flex gap-3">
            <select v-model="filters.status" class="rounded-lg border-gray-300">
                <option value="">Todos os status</option>
                <option value="available">Disponíveis</option>
                <option value="borrowed">Retiradas</option>
                <option value="maintenance">Manutenção</option>
                <option value="inactive">Inativas</option>
            </select>

            <select v-model="filters.active" class="rounded-lg border-gray-300">
                <option value="">Todos</option>
                <option value="1">Ativas</option>
                <option value="0">Inativas</option>
            </select>

            <button
                @click="applyFilters"
                class="rounded-lg bg-gray-900 px-4 py-2 text-white"
            >
                Filtrar
            </button>
        </div>

        <div class="overflow-hidden rounded-xl border bg-white shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="p-4">Código</th>
                        <th class="p-4">Nome</th>
                        <th class="p-4">Responsável</th>
                        <th class="p-4">Local</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="key in keys.data" :key="key.id" class="border-t">
                        <td class="p-4 font-medium">{{ key.code }}</td>
                        <td class="p-4">{{ key.name }}</td>
                        <td class="p-4">{{ key.responsible?.name || '-' }}</td>
                        <td class="p-4">{{ key.location || '-' }}</td>
                        <td class="p-4">
                            <span :class="statusClass(key.status)" class="rounded-full px-3 py-1 text-xs font-medium">
                                {{ statusLabel(key.status) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end gap-2">
                                <button
                                    v-if="key.status === 'available'"
                                    @click="openCheckout(key)"
                                    class="rounded-lg bg-green-600 px-3 py-1.5 text-white"
                                >
                                    Retirar
                                </button>

                                <button
                                    v-if="key.status === 'borrowed'"
                                    @click="openReturn(key)"
                                    class="rounded-lg bg-orange-600 px-3 py-1.5 text-white"
                                >
                                    Devolver
                                </button>

                                <button @click="openEdit(key)" class="rounded-lg bg-blue-600 px-3 py-1.5 text-white">
                                    Editar
                                </button>

                                <button @click="destroyKey(key)" class="rounded-lg bg-red-600 px-3 py-1.5 text-white">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="keys.data.length === 0">
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            Nenhuma chave encontrada.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex gap-2">
            <button
                v-for="link in keys.links"
                :key="link.label"
                v-html="link.label"
                :disabled="!link.url"
                @click="link.url && router.visit(link.url)"
                class="rounded-lg border px-3 py-1 text-sm"
                :class="{ 'bg-gray-900 text-white': link.active }"
            />
        </div>
    </div>

    <div v-if="showCreateModal || showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-2xl rounded-xl bg-white p-6 shadow-xl">
            <h2 class="mb-4 text-xl font-bold">
                {{ showCreateModal ? 'Nova chave' : 'Editar chave' }}
            </h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Nome</label>
                    <input v-model="form.name" class="mt-1 w-full rounded-lg border-gray-300" />
                    <p v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Código</label>
                    <input v-model="form.code" class="mt-1 w-full rounded-lg border-gray-300" />
                    <p v-if="form.errors.code" class="text-sm text-red-600">{{ form.errors.code }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Responsável</label>
                    <select v-model="form.responsible_id" class="mt-1 w-full rounded-lg border-gray-300">
                        <option value="">Sem responsável</option>
                        <option v-for="responsible in responsibles" :key="responsible.id" :value="responsible.id">
                            {{ responsible.name }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Status</label>
                    <select v-model="form.status" class="mt-1 w-full rounded-lg border-gray-300">
                        <option value="available">Disponível</option>
                        <option value="borrowed">Retirada</option>
                        <option value="maintenance">Manutenção</option>
                        <option value="inactive">Inativa</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="text-sm font-medium">Local</label>
                    <input v-model="form.location" class="mt-1 w-full rounded-lg border-gray-300" />
                </div>

                <div class="col-span-2">
                    <label class="text-sm font-medium">Descrição</label>
                    <textarea v-model="form.description" class="mt-1 w-full rounded-lg border-gray-300"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button
                    @click="showCreateModal = false; showEditModal = false"
                    class="rounded-lg border px-4 py-2"
                >
                    Cancelar
                </button>

                <button
                    @click="showCreateModal ? store() : update()"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-white"
                    :disabled="form.processing"
                >
                    Salvar
                </button>
            </div>
        </div>
    </div>

    <div v-if="showCheckoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
            <h2 class="mb-4 text-xl font-bold">Retirar chave</h2>

            <p class="mb-4 text-sm text-gray-500">
                Chave: <strong>{{ selectedKey?.name }}</strong>
            </p>

            <label class="text-sm font-medium">Pessoa que vai pegar</label>
            <select v-model="checkoutForm.person_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Selecione</option>
                <option v-for="person in people" :key="person.id" :value="person.id">
                    {{ person.name }}
                </option>
            </select>

            <label class="mt-4 block text-sm font-medium">Observações</label>
            <textarea v-model="checkoutForm.take_notes" class="mt-1 w-full rounded-lg border-gray-300"></textarea>

            <div class="mt-6 flex justify-end gap-2">
                <button @click="showCheckoutModal = false" class="rounded-lg border px-4 py-2">Cancelar</button>
                <button @click="checkout" class="rounded-lg bg-green-600 px-4 py-2 text-white">Confirmar retirada</button>
            </div>
        </div>
    </div>

    <div v-if="showReturnModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
            <h2 class="mb-4 text-xl font-bold">Devolver chave</h2>

            <p class="mb-4 text-sm text-gray-500">
                Chave: <strong>{{ selectedKey?.name }}</strong>
            </p>

            <label class="text-sm font-medium">Usuário que recebeu</label>
            <p class="text-sm text-gray-500">Será registrado automaticamente como o usuário logado.</p>

            <label class="mt-4 block text-sm font-medium">Observações da devolução</label>
            <textarea v-model="returnForm.return_notes" class="mt-1 w-full rounded-lg border-gray-300"></textarea>

            <div class="mt-6 flex justify-end gap-2">
                <button @click="showReturnModal = false" class="rounded-lg border px-4 py-2">Cancelar</button>
                <button @click="returnKey" class="rounded-lg bg-orange-600 px-4 py-2 text-white">Confirmar devolução</button>
            </div>
        </div>
    </div>
</template>
