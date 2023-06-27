<template>
    <nav class="mt-10">
        <Link class="flex items-center mt-4 py-2 px-6  text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
              :href="route('dashboard')">
            <i class="fa-solid fa-house-user"></i>

            <span class="mx-3">Dashboard</span>
        </Link>

        <Link
              class="flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
              :href="route('inscriptions.index')">
            <i class="fa-solid fa-user-plus"></i>
            <span class="mx-3">Demande d'inscription</span>
        </Link>
        <Link
            class="flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            :href="route('membres.index')">
            <i class="fa-solid fa-user-graduate"></i>
            <span class="mx-3">Membres de lab</span>
        </Link>
        <Link
            class="flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            :href="route('evenements.admin.index')">
            <i class="fa-solid fa-calendar-days"></i>
            <span class="mx-3">Evenements</span>
        </Link>
        <Link
            class="flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            :href="route('equipes.index')">
            <i class="fa-solid fa-people-group"></i>
            <span class="mx-3">Equipes</span>
        </Link>
        <Link
            class="flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            :href="route('projets.index')">
            <i class="fa-solid fa-list-check"></i>
            <span class="mx-3">Projets</span>
        </Link>
        <Link
            class="flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            :href="route('brevets.index')">
            <i class="fa-solid fa-file-circle-check"></i>
            <span class="mx-3">Brevets</span>
        </Link>
        <Link
            class="flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            :href="route('partenaires.index')">
            <i class="fa-solid fa-handshake"></i>
            <span class="mx-3">Partenaires</span>
        </Link>
        <Link
            class="flex items-center mt-4 py-2 px-6 text-white hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            :href="route('settings.index')">
            <i class="fa-solid fa-gear"></i>
            <span class="mx-3">Paramètres</span>
        </Link>

    </nav>
</template>
<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import {ref} from "vue";

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

