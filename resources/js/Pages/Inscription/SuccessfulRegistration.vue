<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div
        style="background-image: url('/images/inscription/background.jpg')"
        class="
    flex
    items-center
    justify-center
    w-screen
    h-screen
    bg-cover
  "
    >
        <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
        <div class="px-40 py-20 bg-white rounded-md shadow-xl z-10">
            <div class="flex flex-col items-center">
                <div class="mx-auto h-16 w-16 flex justify-center items-start">
                    <i class="fa-solid fa-circle-check text-green-500 text-4xl" ></i>
                </div>
                <p class="mb-8 text-center text-lab-grey md:text-lg">
                    Votre demande a bien été enregistrée
                </p>

                <Link :href="route('welcome')" method="get" as="button"  class="button purple-button hover:bg-lab-white hover:text-black">
                    Retour à la page d'accueil
                </Link>

            </div>
        </div>
    </div>
</template>



