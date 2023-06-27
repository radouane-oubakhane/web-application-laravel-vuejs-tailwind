<template>
    <nav class="mt-10">
        <Link class="flex items-center mt-4 py-2 px-6  text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
              :href="route('dashboard')">
            <i class="fa-solid fa-house-user"></i>

            <span class="mx-3">Dashboard</span>
        </Link>
        <Link
            class="flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            :href="route('evenements.doctorant.index')">
            <i class="fa-solid fa-calendar-days"></i>
            <span class="mx-3">Evenement</span>
        </Link>
        <Link
            class="flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            :href="route('conferences.doctorant.show')">
            <i class="fa-solid fa-clock"></i>
            <span class="mx-3">Conférences</span>
        </Link>
        <Link
            class="flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            :href="route('rapports.show')">
            <i class="fa-solid fa-file-circle-check"></i>
            <span class="mx-3">Rapports</span>
        </Link>

    </nav>
</template>
<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import {ref} from "vue";


defineProps({
    sideBarSections: Array,
    user: String,
    table: Array,
    widgets:Array
});
let sidebarOpen = ref(false);
let dropdownOpen = ref(true);
let logOut = ref(false);


function clickLogOut() {
    logOut.value = !logOut.value;
    dropdownOpen.value = !dropdownOpen.value;
}

</script>
<style scoped>

</style>

