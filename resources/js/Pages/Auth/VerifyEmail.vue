<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Verificação de E-mail" />

        <div class="mb-4 text-sm text-slate-600 leading-relaxed">
            Obrigado por se cadastrar! Antes de começar, por favor, confirme seu endereço de e-mail clicando no link que acabamos de enviar para você. Se você não recebeu o e-mail, teremos o prazer de lhe enviar outro.
        </div>

        <div
            class="mb-4 text-sm font-medium text-teal-600"
            v-if="verificationLinkSent"
        >
            Um novo link de verificação foi enviado para o endereço de e-mail fornecido durante o cadastro.
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                <PrimaryButton
                    class="w-full sm:w-auto px-5 py-3"
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
                    <span v-else>Reenviar E-mail de Verificação</span>
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm font-medium text-slate-600 hover:text-slate-900 hover:underline focus:outline-none"
                    >Sair da Conta</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
