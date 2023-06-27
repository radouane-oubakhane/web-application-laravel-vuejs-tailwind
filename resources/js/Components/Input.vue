<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});
</script>

<template>
    <input class="block p-2 w-full text-lg appearance-none focus:outline-none bg-transparent" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input">
</template>
