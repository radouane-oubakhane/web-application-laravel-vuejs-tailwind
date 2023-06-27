<script setup>
defineProps(['value']);
</script>

<template>
    <label class="absolute ml-5 top-0 text-lg text-gray-700 bg-white mt-2 -z-1 duration-300 origin-0">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>

<style scoped>
label {
    transform: translateY(-1.5rem) translatex(-1rem) scaleX(0.80) scaleY(0.80);
}
</style>
