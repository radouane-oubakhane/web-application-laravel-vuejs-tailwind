<script setup>
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Head, Link, useForm} from '@inertiajs/inertia-vue3';
import {ref, defineProps, watch} from "vue";
import AdminSideBar from '@/Pages/Admin/AdminSideBar';
import Nocontent from "@/Components/nocontent/Nocontent";
import Success from "@/Components/flashmessage/Success";
import Fail from "@/Components/flashmessage/Fail";
import FirstConnectionPopUp from "@/Components/firstconnection/FirstConnectionPopUp";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    equipes: Object,
    themes: Object,
    user: Object,
    projets: Array,
    first_time: Boolean
});
let sidebarOpen = ref(false);
let dropdownOpen = ref(true);
let logOut = ref(false);
let profil = ref(false);
let addProjet = ref(false);
let addTheme = ref(false);
let delProj = ref(false);
let delId = ref(false);
let updateProj = ref(false);
const updateProf = ref(false);
const password = ref(null);
const confirmPassword = ref(null);
const error = ref(false);
const annuler = ref(false);
const search = ref('');
const tab = ref(props.projets);
const message = ref(true);
const errors = ref(true);

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

const form = useForm({
    intitule: null,
    detail: null,
    equipe: null,
    theme: null,
    photo: null
});
const projet = useForm({
    id: null,
    intitule: null,
    detail: null,
    equipe: null,
    theme: null,
    photo: null
});

const theme = useForm(
    {
        intitule: null,
        detail: null
    }
)

function updateProjet(id) {
    projet.id = id
    updateProj.value = !updateProj.value
}
function delProjet(projet) {
    delId.value = projet
    delProj.value = !delProj.value
}
const submit = () => {
    addProjet.value = !addProjet.value
    Inertia.post('/projets/store', form)
}
function update() {
    Inertia.post('/projets/edit', projet)

}
function ajouter() {
    addTheme.value = !addTheme.value
    Inertia.post('/themes/store', theme)
}
</script>

<template>
    <Head title="Projets" />
    <div>


        <div class="flex h-screen bg-lab-white">
            <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>

            <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-lab-purple overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
                <div class="flex items-center justify-center mt-8">
                    <div class="flex items-center">
                        <span class="text-white text-2xl mx-2 font-semibold">{{user.name}}</span>
                    </div>
                </div>
                <admin-side-bar/>
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
                        <h3 class="text-gray-700 text-3xl font-medium">Projets</h3>
                        <div class="px-6 py-8">
                            <button @click="addProjet = !addProjet" type="button" class="text-white bg-gradient-to-br from-green-600 to-green-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Ajouter un projet</button>
                            <button @click="addTheme = !addTheme" type="button" class="text-white bg-gradient-to-br from-lab-red to-red-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Ajouter un thème de projet</button>
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

                        <div class="flex flex-col mt-8" v-if="projets.length !== 0">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div
                                    class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                                    <table class="min-w-full">
                                        <thead>
                                        <tr>
                                            <th
                                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                Intitule</th>
                                            <th
                                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                Theme</th>
                                            <th
                                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                Equipe</th>
                                            <th
                                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                Detail</th>
                                            <th
                                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                action
                                            </th>
                                            <th
                                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white">
                                        <tr v-if="annuler"
                                            v-for="projet in tab">
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-md"
                                                             :src="projet.photo"
                                                             alt="">
                                                    </div>

                                                    <div class="ml-4">
                                                        <div class="text-sm leading-5 font-medium text-gray-900">{{projet.intitule}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                                {{projet.theme}}</td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                                {{projet.equipe}}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900">{{projet.detail.slice(0, 30)}}...</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <button
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800"
                                                    @click="updateProjet(projet.id)">
                                                    Editer
                                                </button>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <button
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"
                                                    @click="delProjet(projet.id)">
                                                    Supprimer
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!annuler"
                                            v-for="projet in projets">
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-md"
                                                             :src="projet.photo"
                                                             alt="">
                                                    </div>

                                                    <div class="ml-4">
                                                        <div class="text-sm leading-5 font-medium text-gray-900">{{projet.intitule}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                                {{projet.theme}}</td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                                {{projet.equipe}}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900">{{projet.detail.slice(0, 30)}}...</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <button
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800"
                                                    @click="updateProjet(projet.id)">
                                                    Editer
                                                </button>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <button
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"
                                                    @click="delProjet(projet.id)">
                                                    Supprimer
                                                </button>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
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
                <img class="absolute h-full w-full object-cover" src="images/profil/profil.png" >
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
                            class="px-8 pt-6 pb-2 mb-4 bg-white rounded">
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
                            <div class="mb-2">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="password">
                                    Mot de passe
                                </label>
                                <input
                                    v-model="password"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="password"


                                />
                            </div>
                            <div class="mb-2">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="confirmpassword">
                                    Confirmer votre mot de passe
                                </label>
                                <input
                                    v-model="confirmPassword"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="confirmpassword"
                                    name="confirmpassword"
                                    type="password"
                                    placeholder="Confirm Your Password"
                                />
                                <p v-if="error"
                                   class="block mb-4 text-sm text-red-700">
                                    Mot de passe incorrect
                                </p>
                            </div>
                            <div class="mb-6 text-center mt-6">
                                <button
                                    class="w-full px-4 py-2 font-bold text-white bg-lab-purple rounded-full hover:bg-indigo-700 focus:outline-none focus:shadow-outline"
                                    @click="updateP">
                                    Enregistrer les modifications
                                </button>
                            </div>
                            <hr class="mb-6 border-t" />
                            <div class="text-center">
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

    <!-- Ajouter un Theme -->
    <div v-if="addTheme"
         class="min-w-screen h-screen min-h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover">
        <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
        <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
        <div class="container mx-auto">
            <div class="flex justify-center px-6 my-12">
                <div class="w-full xl:w-3/4 lg:w-11/12 flex justify-center z-10 shadow-md">
                    <div class="w-full lg:w-7/12 bg-white p-5 rounded-lg">
                        <h3 class="pt-4 text-2xl text-center text-lab-purple">Ajouter un nouveau thème</h3>
                        <form
                            class="px-8 pt-6 pb-2 mb-4 bg-white rounded">
                            <div class="mb-2">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="intitule">
                                    Intitule
                                </label>
                                <input
                                    v-model="theme.intitule"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="intitule"
                                    name="intitule"
                                    type="text"
                                    required


                                />
                            </div>
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="detail">
                                    Detail
                                </label>
                                <textarea
                                    v-model="theme.detail"
                                    class="w-full h-20 px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="detail"
                                    name="detail"
                                    type="text"
                                    required
                                ></textarea>
                            </div>
                            <div class="mb-6 text-center mt-6">
                                <button
                                    type="button"
                                    class="w-full px-4 py-2 font-bold text-white bg-lab-purple rounded-full hover:bg-indigo-700 focus:outline-none focus:shadow-outline"
                                    @click="ajouter">
                                    Enregistrer
                                </button>
                            </div>
                            <hr class="mb-6 border-t" />
                            <div class="text-center">
                                <button
                                    class="w-full px-4 py-2 font-bold text-white bg-lab-red rounded-full hover:bg-red-700 focus:outline-none focus:shadow-outline"
                                    @click="addTheme =!addTheme"
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

    <!-- Ajouter une Projet -->
    <div v-if="addProjet"
         class="min-w-screen h-screen min-h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover">
        <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
        <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
        <div class="container mx-auto">
            <div class="flex justify-center px-6 my-12">
                <div class="w-full xl:w-3/4 lg:w-11/12 flex justify-center z-10 shadow-md">
                    <div class="w-full lg:w-7/12 bg-white p-5 rounded-lg">
                        <h3 class="pt-4 text-2xl text-center text-lab-purple">Ajouter un detail projet</h3>
                        <form @submit.prevent="submit"
                              class="px-8 pt-6 pb-2 mb-4 bg-white rounded">
                            <div class="mb-4 md:grid md:grid-cols-2 md:gap-6">
                                <div class="md:mr-2 md:mb-0">
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="intitule">
                                        Intitule
                                    </label>
                                    <input
                                        v-model="form.intitule"
                                        class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        id="intitule"
                                        name="intitule"
                                        type="text"
                                        placeholder="Intitule"
                                        required
                                    />
                                </div>
                                <div class="md:ml-2 flex-1">
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="equipe">
                                        Equipe
                                    </label>
                                    <select class="w-full px-3 py-2 text-sm bg-white leading-tight text-gray-700 text-center border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                            name="chercheur"
                                            v-model="form.equipe"
                                            >
                                        <option v-for="equipe in equipes" :value="equipe.id" :key="equipe.id">{{equipe.nom}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4 md:grid md:grid-cols-2 md:gap-6">
                                <div class="mb-4 md:mr-2 md:mb-0">
                                    <p class="block mb-2 text-sm font-bold text-gray-700">
                                        Photo
                                        <span class="text-xs pt-1 italic text-red-500"> fichier PNG, JPG</span>
                                    </p>
                                    <div class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">

                                        <label for="photo">cliquez ici</label>
                                    </div>
                                    <input
                                        @input="form.photo = $event.target.files[0]"
                                        class="cursor-pointer absolute block opacity-0 pin-r pin-t"
                                        id="photo"
                                        name="photo"
                                        type="file"
                                        required
                                    />
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 flex-1">
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="photo">
                                        Thème
                                    </label>
                                    <select class="w-full px-3 py-2 mb-3 text-sm bg-white leading-tight text-gray-700 text-center border rounded shadow appearance-none focus:outline-none focus:shadow-outline mb-0"
                                            name="chercheur"
                                            v-model="form.theme"
                                            required>
                                        <option v-for="theme in themes" :value="theme.id" :key="theme.id">{{theme.intitule}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="detail">
                                    Detail
                                </label>
                                <textarea
                                    v-model="form.detail"
                                    class="w-full h-20 px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="detail"
                                    name="detail"
                                    type="text"
                                    placeholder="Detail"
                                    required
                                ></textarea>
                            </div>
                            <div class="mb-6 text-center">
                                <button
                                    class="w-full px-4 py-2 font-bold text-white bg-lab-purple rounded-full hover:bg-indigo-700 focus:outline-none focus:shadow-outline"
                                    type="submit">
                                    Enregistrer
                                </button>
                            </div>
                            <hr class="mb-6 border-t" />
                            <div class="mb-6 text-center">
                                <button
                                    class="w-full px-4 py-2 font-bold text-white bg-lab-red rounded-full hover:bg-red-700 focus:outline-none focus:shadow-outline"
                                    @click="addProjet =!addProjet"
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

    <!-- Editer une Projet -->
    <div v-if="updateProj"
         class="min-w-screen h-screen min-h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover">
        <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
        <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
        <div class="container mx-auto">
            <div class="flex justify-center px-6 my-12">
                <div class="w-full xl:w-3/4 lg:w-11/12 flex justify-center z-10 shadow-md">
                    <div class="w-full lg:w-7/12 bg-white p-5 rounded-lg">
                        <h3 class="pt-4 text-2xl text-center text-lab-purple">Editer un projet</h3>
                        <form
                              class="px-8 pt-6 bg-white rounded">
                            <div class="mb-4 md:grid md:grid-cols-2 md:gap-6">
                                <div class="mb-4 md:mr-2 md:mb-0">
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="intitule">
                                        Intitule
                                    </label>
                                    <input
                                        v-model="projet.intitule"
                                        class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        id="intitule"
                                        name="intitule"
                                        type="text"
                                        placeholder="Intitule"

                                    />
                                </div>
                                <div class="md:ml-2 flex-1">
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="equipe">
                                        Equipe
                                    </label>
                                    <select class="w-full px-3 py-2 mb-3 text-sm bg-white leading-tight text-gray-700 text-center border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                            name="equipe"
                                            v-model="projet.equipe">
                                        <option v-for="equipe in equipes" :value="equipe.id" :key="equipe.id">{{equipe.nom}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4 md:grid md:grid-cols-2 md:gap-6">
                                <div class="mb-2">
                                    <p class="block mb-2 text-sm font-bold text-gray-700">
                                        Photo
                                        <span class="text-xs pt-1 italic text-red-500"> fichier PNG, JPG</span>
                                    </p>
                                    <div class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">

                                        <label for="photo">cliquez ici</label>
                                    </div>
                                    <input
                                        @input="projet.photo = $event.target.files[0]"
                                        class="cursor-pointer absolute block opacity-0 pin-r pin-t"
                                        id="photo"
                                        name="photo"
                                        type="file"
                                    />
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 flex-1">
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="theme">
                                        Theme
                                    </label>
                                    <select class="w-full px-3 py-2 mb-3 text-sm bg-white leading-tight text-gray-700 text-center border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                            name="chercheur"
                                            v-model="projet.theme">
                                        <option v-for="theme in themes" :value="theme.id" :key="theme.id">{{theme.intitule}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="detail">
                                    Detail
                                </label>
                                <textarea
                                    v-model="projet.detail"
                                    class="w-full h-20 px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="detail"
                                    name="detail"
                                    type="text"
                                    placeholder="Detail"
                                ></textarea>
                            </div>
                            <div class="mb-6 text-center">
                                <button
                                    class="w-full px-4 py-2 font-bold text-white bg-lab-purple rounded-full hover:bg-indigo-700 focus:outline-none focus:shadow-outline"
                                    @click="update">
                                    Enregistrer les modifications
                                </button>
                            </div>
                            <hr class="mb-6 border-t" />
                            <div class="mb-6 text-center">
                                <button
                                    class="w-full px-4 py-2 font-bold text-white bg-lab-red rounded-full hover:bg-red-700 focus:outline-none focus:shadow-outline"
                                    @click="updateProj =!updateProj"
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

    <!-- delete Projet -->
    <div v-if="delProj"
         class="min-w-screen h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover"
         id="modal-id">
        <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
        <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
        <div class="w-full  max-w-lg p-5 relative mx-auto my-auto rounded-xl shadow-lg  bg-white ">
            <div class="">
                <div class="text-center p-5 flex-auto justify-center">
                    <h2 class="text-xl font-bold py-4 ">Êtes-vous sûr?</h2>
                    <p class="text-sm text-gray-500 px-8">Voulez-vous vraiment supprimer cet projet?</p>
                </div>
                <div class="p-3  mt-2 text-center space-x-4 md:block">
                    <button @click="delProj = !delProj" class="mb-2 md:mb-0 bg-white px-5 py-2 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-md hover:shadow-lg hover:bg-gray-100">
                        Non
                    </button>
                    <Link
                        class="mb-2 md:mb-0 bg-red-500 border border-lab-red px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-md hover:shadow-lg hover:bg-red-600"
                        @click="delProj = !delProj"
                        :href="`/projets/${delId}`"
                        method="delete"
                        as="button"
                        type="button">
                        Oui
                    </Link>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Message -->
    <div v-if="($page.props.flash.message && message) || ($page.props.flash.error && errors)"
         class="min-w-screen h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover">
        <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
        <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
        <div class="z-10 w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-md duration-300 hover:scale-105 hover:shadow-xl">
            <div v-if="$page.props.flash.message && message" >
                <success>
                    {{ $page.props.flash.message }}
                </success>
                <div class="space-x-4 bg-gray-100 py-4 text-center">
                    <button @click="message = !message"
                            class="inline-block rounded-md bg-green-500 px-6 py-2 font-semibold text-green-100 shadow-md duration-75 hover:bg-green-400">Fermer</button>
                </div>
            </div>
            <div v-if="$page.props.flash.error && errors">
                <fail>
                    {{ $page.props.flash.error }}
                </fail>
                <div class="space-x-4 bg-gray-100 py-4 text-center">
                    <button @click="errors = !errors"
                            class="inline-block rounded-md bg-red-500 px-10 py-2 font-semibold text-green-100 shadow-md duration-75 hover:bg-red-400">Fermer</button>
                </div>
            </div>
        </div>
    </div>


    <!-- first connection -->
    <div v-if="first_time">
        <FirstConnectionPopUp/>
    </div>

</template>







