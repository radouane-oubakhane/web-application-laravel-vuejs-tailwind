<template>
    <div class="mx-auto mt-8 h-16 w-16 flex justify-center items-center">
        <i class="fa-solid fa-circle-check text-green-500 text-4xl" ></i>
    </div>
    <h1 class="mt-2 text-center text-2xl font-bold text-gray-500">Success</h1>
    <p class="my-4 text-center text-sm text-gray-500">
        <slot/>
    </p>
</template>

<script>
export default {
    name: "Success"
}
</script>

<style scoped>

</style>
