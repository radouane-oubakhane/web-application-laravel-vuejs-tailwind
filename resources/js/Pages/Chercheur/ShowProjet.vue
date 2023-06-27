<script setup>
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Head, Link, useForm} from '@inertiajs/inertia-vue3';
import {ref, defineProps, watch} from "vue";
import ChercheurSideBar from '@/Pages/Chercheur/ChercheurSideBar';
import Nocontent from "@/Components/nocontent/Nocontent";
import FirstConnectionPopUp from "@/Components/firstconnection/FirstConnectionPopUp";
import {Inertia} from "@inertiajs/inertia";
const props = defineProps({
    user: Object,
    equipe: Object,
    chefEquipe: Object,
    projets: Array,
    first_time: Boolean

});
let sidebarOpen = ref(false);
let dropdownOpen = ref(true);
let logOut = ref(false);
let profil = ref(false);
const updateProf = ref(false);
const password = ref(null);
const confirmPassword = ref(null);
const error = ref(false);
const annuler = ref(false);
const search = ref('');
const tab = ref(props.projets);



watch(search, () => {
    tab.value = props.projets.filter(
        projet => projet['intitule'].toUpperCase().includes(search.value.toUpperCase())
    )
})

function doAnnuler() {
    annuler.value = !annuler.value
    search.value = ''
}

const profilForm = useForm({
    email: null,
    numeroTelephone: null,
    photo: null,
    password: null
});

function updateP() {
    if (password.value === confirmPassword.value)
    {
        profilForm.password = password.value
        error.value = false
        Inertia.post('/comptes/edit', profilForm)
    }
    else error.value = true
}
function updateProfil() {
    updateProf.value = !updateProf.value;
    profil.value = !profil.value;
}


function clickLogOut() {
    logOut.value = !logOut.value;
    dropdownOpen.value = !dropdownOpen.value;
}
function closeAll() {
    logOut.value = !logOut.value;
    dropdownOpen.value = !dropdownOpen.value;
    dropdownOpen.value = !dropdownOpen.value;
    sidebarOpen.value = !sidebarOpen.value;
}
</script>

<template>
    <Head title="Equipes" />
    <div>


        <div class="flex h-screen bg-lab-white">
            <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>

            <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-lab-purple overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
                <div class="flex items-center justify-center mt-8">
                    <div class="flex items-center">
                        <span class="text-white text-2xl mx-2 font-semibold">{{user.name}}</span>
                    </div>
                </div>
                <chercheur-side-bar/>
            </div>
            <div class="flex-1 flex flex-col overflow-hidden">
                <header class="flex justify-between items-center py-4 px-6 bg-white border-b-4 border-lab-purple">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                    </div>

                    <div class="flex items-center">

                        <div class="relative">
                            <button @click="dropdownOpen = ! dropdownOpen"
                                    class="relative block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none">
                                <img class="h-full w-full object-cover"
                                     :src="user.photo"
                                     alt="Your avatar">
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10"
                                 :class="{hidden: dropdownOpen}">
                                <a @click="profil =! profil"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Profil</a>
                                <a @click="logOut =! logOut"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Se déconnecter</a>
                            </div >
                        </div>
                    </div>
                    <div v-if="logOut"
                         class="min-w-screen h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover"
                         id="modal-id">
                        <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
                        <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
                        <div class="w-full  max-w-lg p-5 relative mx-auto my-auto rounded-xl shadow-lg  bg-white ">
                            <div class="">
                                <div class="text-center p-5 flex-auto justify-center">
                                    <h2 class="text-xl font-bold py-4 ">Êtes-vous sûr?</h2>
                                    <p class="text-sm text-gray-500 px-8">Voulez-vous vraiment vous déconnecter?</p>
                                </div>
                                <div class="p-3  mt-2 text-center space-x-4 md:block">
                                    <button @click="clickLogOut" class="mb-2 md:mb-0 bg-white px-5 py-2 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-md hover:shadow-lg hover:bg-gray-100">
                                        Non
                                    </button>
                                    <BreezeResponsiveNavLink :href="route('logout')" method="post" as="button"  class="mb-2 md:mb-0 bg-red-500 border border-lab-red px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-md hover:shadow-lg hover:bg-red-600">
                                        Oui
                                    </BreezeResponsiveNavLink>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                    <div class="container mx-auto px-6 py-8">
                        <h3 class="text-gray-700 text-3xl font-medium">Projet</h3>
                        <div class="mt-8">

                        </div>

                        <div class="flex items-center">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <input @focus="annuler = !annuler"
                                       type="text" name="search" v-model="search" id="search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Rechercher des chercheurs, des doctorants, des projets..." required>
                                <button type="button" class="flex absolute inset-y-0 right-0 items-center pr-3">
                                </button>
                            </div>
                            <button v-if="!annuler"

                                    class="inline-flex items-center py-2.5 px-3 ml-2 text-sm font-medium text-white bg-lab-purple rounded-md border border-lab-purple hover:bg-lab-purple focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <i class="fa-solid fa-magnifying-glass pr-2"></i>
                                Recherche
                            </button>
                            <button v-if="annuler"
                                    @click="doAnnuler"
                                    class="inline-flex items-center py-2.5 px-3 ml-2 text-sm font-medium text-white bg-lab-red rounded-md border border-lab-red hover:bg-lab-red focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <i class="fa-solid fa-xmark pr-2"></i>
                                Annuler
                            </button>
                        </div>

                        <div v-if="projets.length">
                            <div class="py-16 bg-white rounded-md shadow-md mt-16" v-for="projet in projets" v-if="!annuler">
                                <div class="container m-auto px-6 text-gray-600 md:px-12 xl:px-6">
                                    <div class="space-y-6 md:space-y-0 md:flex md:gap-6 lg:items-center lg:gap-12">
                                        <div class="md:5/12 lg:w-5/12 rounded-md overflow-hidden">
                                            <img
                                                :src="projet.photo"
                                                alt="image" loading="lazy" width="" height="">
                                        </div>
                                        <div class="md:7/12 lg:w-6/12">
                                            <h2 class="text-2xl text-gray-900 font-bold md:text-4xl">{{projet.intitule}}</h2>
                                            <p class="mt-6 text-gray-600">{{projet.detail}}</p>
                                            <div class="flex flex-row px-2 pt-8">
                                                <div v-if="chefEquipe.photo" class="w-auto h-auto rounded-full">
                                                    <img class="w-12 h-12 object-cover rounded-full shadow cursor-pointer"
                                                         alt=""
                                                         :src="chefEquipe.photo">
                                                </div>
                                                <div class="flex flex-col mb-2 ml-4 mt-1">
                                                    <div class="text-gray-600 text-sm font-semibold">{{chefEquipe.nom ? chefEquipe.nom : ""}} {{chefEquipe.prenom ? chefEquipe.prenom : ""}}</div>
                                                    <div v-if="chefEquipe.nom" class="flex w-full mt-1">
                                                        <div class="text-blue-700 font-base text-xs mr-1 cursor-pointer">
                                                            CHEF DE PROJET
                                                        </div>
                                                        <div class="text-gray-400 font-thin text-xs">
                                                            • {{chefEquipe.email ? chefEquipe.email : ""}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="py-16 bg-white rounded-md shadow-md mt-16" v-for="projet in tab" v-if="annuler">
                                <div class="container m-auto px-6 text-gray-600 md:px-12 xl:px-6">
                                    <div class="space-y-6 md:space-y-0 md:flex md:gap-6 lg:items-center lg:gap-12">
                                        <div class="md:5/12 lg:w-5/12 rounded-md overflow-hidden">
                                            <img
                                                :src="projet.photo"
                                                alt="image" loading="lazy" width="" height="">
                                        </div>
                                        <div class="md:7/12 lg:w-6/12">
                                            <h2 class="text-2xl text-gray-900 font-bold md:text-4xl">{{projet.intitule}}</h2>
                                            <p class="mt-6 text-gray-600">{{projet.detail}}</p>
                                            <div class="flex flex-row px-2 pt-8">
                                                <div class="w-auto h-auto rounded-full">
                                                    <img class="w-12 h-12 object-cover rounded-full shadow cursor-pointer"
                                                         alt=""
                                                         :src="chefEquipe.photo">
                                                </div>
                                                <div class="flex flex-col mb-2 ml-4 mt-1">
                                                    <div class="text-gray-600 text-sm font-semibold">{{chefEquipe.nom}} {{chefEquipe.prenom}}</div>
                                                    <div class="flex w-full mt-1">
                                                        <div class="text-blue-700 font-base text-xs mr-1 cursor-pointer">
                                                            CHEF DE PROJET
                                                        </div>
                                                        <div class="text-gray-400 font-thin text-xs">
                                                            • {{chefEquipe.email}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <nocontent/>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
    <!-- Profil -->
    <div v-if="profil"
         class="min-w-screen h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover">
        <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
        <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
        <div class="bg-white my-12 pb-6 w-full justify-center items-center overflow-hidden md:max-w-sm rounded-lg shadow-sm mx-auto  shadow-md z-10">
            <div class="relative h-40">
                <img class="absolute h-full w-full object-cover" src="/images/profil/profil.png" >
            </div>
            <div class="relative shadow mx-auto h-24 w-24 -my-12 border-white rounded-full overflow-hidden border-4">
                <img class="object-cover w-full h-full" :src="user.photo">
            </div>
            <div class="mt-16">
                <h1 class="text-lg text-center font-semibold">
                    {{user.name}}
                </h1>
                <p class="text-sm text-gray-600 text-center">
                    {{user.email}}
                </p>
            </div>
            <div class="mt-6 pt-3 flex justify-center flex-wrap mx-6 border-t">
                <button @click="updateProfil" type="button" class="text-white bg-gradient-to-br from-green-600 to-green-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Editer votre profil</button>
                <button @click="profil =! profil" type="button" class="text-white bg-gradient-to-br from-lab-red to-red-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Fermer</button>
            </div>
        </div>

    </div>
    <!-- Editer Profil -->
    <div v-if="updateProf"
         class="min-w-screen h-screen min-h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover">
        <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
        <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
        <div class="container mx-auto">
            <div class="flex justify-center px-6 my-12">
                <div class="w-full xl:w-3/4 lg:w-11/12 flex justify-center z-10 shadow-md">
                    <div class="w-full lg:w-7/12 bg-white p-5 rounded-lg">
                        <h3 class="pt-4 text-2xl text-center text-lab-purple">Editer votre profil</h3>
                        <form
                            class="px-8 pt-6 mb-2
                             bg-white rounded">
                            <div class="mb-2">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="email">
                                    Email
                                </label>
                                <input
                                    v-model="profilForm.email"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="email"
                                    name="email"
                                    type="email"
                                    :placeholder="user.email"

                                />
                            </div>
                            <div class="mb-2">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="numerotelephone">
                                    Numero Telephone
                                </label>
                                <input
                                    v-model="profilForm.numeroTelephone"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="numerotelephone"
                                    name="numerotelephone"
                                    type="text"
                                    placeholder="Numero Telephone"
                                />
                            </div>
                            <div class="mb-2">
                                <p class="block mb-2 text-sm font-bold text-gray-700">
                                    Photo
                                    <span class="text-xs pt-1 italic text-red-500"> fichier PNG, JPG</span>
                                </p>
                                <div class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">

                                    <label for="photo">cliquez ici pour télécharger votre photo de profil</label>
                                </div>
                                <input
                                    @input="profilForm.photo = $event.target.files[0]"
                                    class="cursor-pointer absolute block opacity-0 pin-r pin-t"
                                    id="photo"
                                    name="photo"
                                    type="file"
                                />
                            </div>
                            <div class="mb-4 md:flex md:justify-between">
                                <div class="mb-4 md:mr-2 md:mb-0">
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="password">
                                        Mot de passe
                                    </label>
                                    <input
                                        v-model="password"
                                        class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        id="password"
                                        name="password"
                                        type="password"
                                        placeholder="Mot de passe"


                                    />
                                </div>
                                <div class="md:ml-2">
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="confirmpassword">
                                        Confirmer mot de passe
                                    </label>
                                    <input
                                        v-model="confirmPassword"
                                        class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        id="confirmpassword"
                                        name="confirmpassword"
                                        type="password"
                                        placeholder="Confirmer mot de passe"
                                    />
                                    <p v-if="error"
                                       class="block mb-4 text-sm text-red-700">
                                        Mot de passe incorrect
                                    </p>
                                </div>
                            </div>
                            <div class="mb-6 text-center">
                                <button
                                    class="w-full px-4 py-2 font-bold text-white bg-lab-purple rounded-full hover:bg-indigo-700 focus:outline-none focus:shadow-outline"
                                    @click="updateP">
                                    Enregistrer les modifications
                                </button>
                            </div>
                            <hr class="mb-6 border-t" />
                            <div class="mb-6 text-center">
                                <button
                                    class="w-full px-4 py-2 font-bold text-white bg-lab-red rounded-full hover:bg-red-700 focus:outline-none focus:shadow-outline"
                                    @click="updateProf =!updateProf"
                                >
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- first connection -->
    <div v-if="first_time">
        <FirstConnectionPopUp/>
    </div>

</template>







