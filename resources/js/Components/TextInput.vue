<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-xl border-slate-200 shadow-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 text-slate-800 placeholder-slate-400"
        v-model="model"
        ref="input"
    />
</template>
