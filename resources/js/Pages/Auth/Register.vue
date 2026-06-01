<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Criar Conta" />

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel for="name" value="Nome Completo" class="text-slate-700 font-medium" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1.5 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Seu nome completo"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="E-mail" class="text-slate-700 font-medium" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1.5 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="exemplo@email.com"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Senha" class="text-slate-700 font-medium" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1.5 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="Mínimo 8 caracteres"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirmar Senha"
                    class="text-slate-700 font-medium"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1.5 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Repita sua senha"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="flex items-center justify-between pt-2">
                <Link
                    :href="route('login')"
                    class="text-sm font-medium text-teal-600 hover:text-teal-500 hover:underline focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
                >
                    Já tem uma conta?
                </Link>

                <PrimaryButton
                    class="px-5 py-3"
                    :class="{ 'opacity-50 pointer-events-none': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Processando...
                    </span>
                    <span v-else>Criar Conta</span>
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
