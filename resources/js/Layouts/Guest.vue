<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <div class="relative min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 bg-gray-500 bg-no-repeat bg-cover relative items-center"
         style="background-image: url('images/login/background.jpg')">
        <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
            <div class="mt-2 items-center z-10">
                <slot />
            </div>
    </div>
</template>
