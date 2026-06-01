<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Recuperar Senha" />

        <div class="mb-4 text-sm text-slate-600 leading-relaxed">
            Esqueceu sua senha? Sem problemas. Basta nos informar seu endereço de e-mail 
            e nós enviaremos um link de redefinição de senha para você escolher uma nova.
        </div>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-teal-600"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="email" value="E-mail" class="text-slate-700 font-medium" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1.5 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="exemplo@email.com"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end">
                <PrimaryButton
                    class="w-full py-3"
                    :class="{ 'opacity-50 pointer-events-none': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Enviando...
                    </span>
                    <span v-else>Enviar Link de Redefinição</span>
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
