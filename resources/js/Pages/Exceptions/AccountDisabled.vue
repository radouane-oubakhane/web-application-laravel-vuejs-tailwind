<script setup>
import { ref } from 'vue';
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';


const showingNavigationDropdown = ref(false);
</script>

<template>
    <div
        style="background-image: url('images/exceptions/background.jpg')"
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
                <h1 class="font-bold text-lab-red text-6xl">Compte désactivé!</h1>
                <p class="mb-8 text-center text-lab-grey md:text-lg">
                    contactez votre responsable pour plus de détails.
                </p>

                <BreezeResponsiveNavLink :href="route('logout')" method="post" as="button"  class="button purple-button hover:bg-lab-white hover:text-black">
                    Retour à la page d'accueil
                </BreezeResponsiveNavLink>

            </div>
        </div>
    </div>
</template>

