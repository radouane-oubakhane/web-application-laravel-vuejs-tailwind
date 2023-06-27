<script setup>
import {Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    labInfo: Array,
    evenements: Array,
    partenaires: Array,
    chercheurs: Array
})

let dropdownOpen = ref(true);

</script>

<template>
    <header id="home">
        <nav class="container flex items-center py-4 mt-4 sm:mt-8">
            <div class="py-1 w-14">
                <img src="images/home/logo1.jpg" alt="logo"/>
            </div>
            <ul class="hidden sm:flex flex-1 justify-end items-center gap-12 text-lab-purple uppercase text-xs">
                <li class="cursor-pointer"><Link href="#home">home</Link></li>
                <li v-if="evenements.length" class="cursor-pointer"><Link href="#evenement">Évenement</Link></li>
                <li v-if="chercheurs.length" class="cursor-pointer"><Link href="#team">Notre équipe</Link></li>
                <li v-if="partenaires.length" class="cursor-pointer"><Link href="#partenaire">Partenaire</Link></li>
                <li class="cursor-pointer"><Link href="#about">À propos de nous</Link></li>
                <div v-if="canLogin" class="flex justify-end items-center gap-12">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="bg-lab-red text-white rounded-full px-7 py-3 uppercase">
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')" class="bg-lab-red text-white rounded-full px-7 py-3 uppercase">
                            Se connecter
                        </Link>

                        <Link v-if="canRegister" :href="route('inscriptions.create')" class="bg-lab-red text-white rounded-full px-7 py-3 uppercase">
                            Rejoignez-nous
                        </Link>
                    </template>
                </div>
            </ul>
            <div class="flex sm:hidden flex-1 justify-end relative">
                <button @click="dropdownOpen = ! dropdownOpen">
                        <i class=" text-2xl fa-solid fa-bars"></i>
                </button>
                <div class="absolute right-0 mt-6 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10 uppercase"
                     :class="{hidden: dropdownOpen}">
                    <Link class="block px-4 py-2 text-sm text-gray-700 hover:bg-lab-purple hover:text-white"
                        href="#home">home</Link>
                    <Link class="block px-4 py-2 text-sm text-gray-700 hover:bg-lab-purple hover:text-white"
                          v-if="evenements.length"
                          href="#evenement">Évenement</Link>
                    <Link class="block px-4 py-2 text-sm text-gray-700 hover:bg-lab-purple hover:text-white"
                          v-if="chercheurs.length"
                          href="#team">Notre équipe</Link>
                    <Link class="block px-4 py-2 text-sm text-gray-700 hover:bg-lab-purple hover:text-white"
                          v-if="partenaires.length"
                          href="#partenaire">Partenaire</Link>
                    <Link class="block px-4 py-2 text-sm text-gray-700 hover:bg-lab-purple hover:text-white"
                        href="#about">À propos de nous</Link>
                    <div v-if="canLogin">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-lab-purple hover:text-white">
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link :href="route('login')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-lab-purple hover:text-white">
                                Se connecter
                            </Link>

                            <Link v-if="canRegister" :href="route('inscriptions.create')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-lab-purple hover:text-white">
                                Rejoignez-nous
                            </Link>
                        </template>
                    </div>
                </div >
            </div>
        </nav>
    </header>

    <!-- Landing Section -->
    <section>
        <div class="container flex flex-col-reverse lg:flex-row items-center gap-12 mt-8 lg:mt-16">
            <!-- Content Box -->
            <div class="flex flex-1 flex-col items-center lg:items-start pb-6">
                <h2 class="text-lab-blue text-3xl md:text-4 lg:text-5xl text-center lg:text-left mb-16">
                    {{ labInfo[0].nom }}
                </h2>
                <p class="text-lab-grey text-lg text-center lg:text-left mb-6">
                    {{ labInfo[0].detail }}
                </p>
                <div  class="flex justify-center flex-wrap gap-6 mb-6">
                    <Link :href="route('login')" class="button purple-button hover:bg-lab-white hover:text-black">
                        Se connecter
                    </Link>

                    <Link v-if="canRegister" :href="route('inscriptions.create')" class="button white-button hover:bg-lab-purple hover:text-white">
                        Rejoignez-nous
                    </Link>
                </div>
            </div>
            <!-- Landing Img -->
            <div class="flex justify-center flex-1 mb-10 md:mb-16 lg:mb-0 z-10 hidden lg:block pt-16">
                <img src="images/home/landing-background.png"
                     class="w-5/6 h-5/6 sm:w-3/4 sh:h-3/4 md:w-full nd:h-full" alt="landing background image">
            </div>
        </div>
    </section>
    <!-- Event Section -->
    <section class="bg-lab-white py-20" id="evenement" v-if="evenements.length">
        <!-- Heading -->
        <div class="sm:w3/4 lg:w-5/12 mx-auto px-2">
            <h1 class="text-3xl text-center text-lab-blue mt-4">Les événement organisé par notre laboratoire</h1>
            <p class="text-center text-lab-grey mt-4">
                Nos technologies systématisent la découverte de petites molécules first-in-class et de nouvelles cibles pour des maladies complexes. En sondant de nouveaux espaces et mécanismes chimiques.
            </p>
        </div>
        <!-- Events -->
        <div class="container grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-3 my-16">
            <div v-for="evenement in  evenements" :key="evenement.id" class="overflow-hidden rounded-md shadow-md bg-white text-center hover:-translate-y-1 duration-300">
                <img class="aspect-video w-full rounded-t-md object-cover object-center"
                    :src="evenement.photo"
                    alt="event 1 img">
                <div class="py-3 px-4">
                    <h3 class="text-lab-purple">{{ evenement.intitule }}</h3>
                    <p class="text-lab-grey py-3 overflow-hidden">{{ evenement.detail }}</p>
                    <h2 class="border-t-2 pt-1">{{ evenement.dateEvenement }}</h2>
                </div>
            </div>
        </div>
    </section>
    <!-- Team Section -->
    <section class="bg-lab-white" id="team" v-if="chercheurs.length">
        <div class="container flex md:flex-col-reverse lg:flex-row items-center gap-12 pt-14 lg:pt-28">
            <!-- Content Box -->
            <div class="flex flex-1 flex-col items-center lg:items-start pb-6">
                <h2 class="text-lab-blue text-3xl md:text-4 lg:text-5xl text-center lg:text-left mb-16">
                    Salut, nous sommes {{ labInfo[0].nom }}
                </h2>
                <p class="text-lab-grey text-lg text-center lg:text-left">
                    Notre équipe des meilleurs chercheurs du monde
                </p>
                <div class="my-6 pl-3">
                    <Link v-if="canRegister" :href="route('inscriptions.create')" class="button purple-button hover:bg-lab-white hover:text-black">
                        Rejoignez-nous
                    </Link>
                </div>
            </div>
            <!-- Team Imgs -->
            <div class="container md:grid hidden gap-5 md:grid-cols-3 my-16 w-1/2 pb-6">
                <div v-for="chercheur in chercheurs" :key="chercheur.id" class="overflow-hidden rounded-md shadow-md">
                    <img :src="chercheur.photo"
                         alt="landing background image">
                </div>
            </div>
        </div>
    </section>
    <!-- Partenaire Section -->
    <section class=" my-20" id="partenaire" v-if="partenaires.length">
        <!-- Heading -->
        <div class="sm:w3/4 lg:w-5/12 mx-auto px-2">
            <h1 class="text-3xl text-center text-lab-blue mt-4">Nos Partenaires</h1>
            <p class="text-center text-lab-grey mt-4">
                Nos partenaires qui nous aident à changer l'avenir de la science
            </p>
        </div>
        <!-- Partenaires -->
        <div class="container grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-3 my-16">
            <div v-for="partenaire in partenaires" :key="partenaire.id" class="overflow-hidden rounded-md shadow-md bg-lab-white text-center">
                <div class="py-3 px-4">
                    <h3 class="text-lab-purple">{{ partenaire.nom }}</h3>
                    <p class="text-lab-grey py-3">{{ partenaire.detail }}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section -->
    <section id="about">
        <!-- Heading -->
        <div class="sm:w3/4 lg:w-5/12 mx-auto px-2 my-16">
            <h1 class="text-3xl text-center text-lab-blue mt-4">À propos de nous</h1>
            <p class="text-center text-lab-grey mt-4">
                Nos technologies systématisent la découverte de petites molécules first-in-class et de nouvelles cibles pour des maladies complexes. En sondant de nouveaux espaces et mécanismes chimiques.
            </p>
        </div>
        <!-- Content -->
        <div class="relative mt-20 lg:mt-24">
            <div class="container flex flex-col lg:flex-row items-center justify-center gap-x-24">
                <div class="flex flex-1 justify-center z-10 mb-10 lg:mb-0 rounded-md overflow-hidden">
                    <img src="images/home/about.jpg" alt=""
                         class="w-5/6 h-5/6 sm:w-3/4 sm:h-3/4 md:w-full md:h-full"/>
                </div>
                <div class="flex flex-1 flex-col items-center lg:items-start">
                    <h1 class="text-3xl text-lab-blue ">{{ labInfo[0].nom }}</h1>
                    <p class="text-lab-grey my-4 text-center lg:text-left sm:w-3/4 lg:w-full">
                        Chez Radouane & Hamza LAB, nous apprécions non seulement les partenariats internes entre nos scientifiques, nous pensons également que ces relations durables sont essentielles au succès et à la livraison rapide de nouveaux médicaments aux patients. Nous avons constitué une équipe de découverte de médicaments composée de biologistes, de chimistes, de bioinformaticiens et de scientifiques des données qui sont des employés à temps plein et copropriétaires d'Enveda Biosciences. Couvrant trois continents et six fuseaux horaires, nous sommes tous unis par notre objectif de fournir de nouveaux traitements et de l'espoir à chaque patient.
                    </p>
                </div>
            </div>
            <div
                class="
                hidden
                lg:block
                overflow-hidden
                bg-lab-purple
                rounded-r-full
                absolute
                h-80
                w-2/4
                -bottom-24
                -left-36
                "
            >
            </div>

        </div>

    </section>
    <!-- footer Section -->
    <section class="bg-lab-blue py-8 mt-14 lg:mt-28">
        <div class="container flex flex-col md:flex-row items-center">
            <div class="flex flex-1 flex-wrap items-center justify-center md:justify-start gap-12">
                <a href="#home">
                    <img src="images/home/logo2.png" alt="lab logo"/>
                </a>
                <ul class="flex text-white uppercase gap-12 text-xs">
                    <li class="cursor-pointer"><Link href="#home">home</Link></li>
                    <li v-if="evenements.length" class="cursor-pointer"><Link href="#evenement">Évenement</Link></li>
                    <li v-if="chercheurs.length" class="cursor-pointer"><Link href="#team">Notre équipe</Link></li>
                    <li v-if="partenaires.length" class="cursor-pointer"><Link href="#partenaire">Partenaire</Link></li>
                    <li class="cursor-pointer"><Link href="#about">À propos de nous</Link></li>
                </ul>
            </div>
            <div class="flex gap-10 mt-12 md:mt-0">
                <a href="#" class="block"><i class="text-white text-2xl fa-brands fa-facebook-square"></i></a>
                <a href="#" class="block"><i class="text-white text-2xl fa-brands fa-twitter-square"></i></a>
            </div>
        </div>
        <div class="container flex flex-col md:flex-row items-center mt-16 mb-8">
            <div class="flex flex-1 flex-col gap-12 mt-12 md:mt-0 text-white uppercase text-xs">
                <p>{{ labInfo[0].adresse }}</p>
                <p>TÉLÉPHONER : {{ labInfo[0].numeroTelephone }}</p>
                <p>Email : {{ labInfo[0].email }}</p>
            </div>
            <div class="text-white uppercase text-xs text-center font-bold my-16">
                <p>© 2022 OUBAKHANE Radouane & MOUMAD Hamza All Right Reserved</p>
            </div>
        </div>
    </section>
</template>

<style scoped>
html {
    scroll-behavior: smooth;
}
</style>
