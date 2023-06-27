<script setup>
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Head, Link, useForm} from '@inertiajs/inertia-vue3';
import {ref, defineProps, watch} from "vue";
import {Inertia} from "@inertiajs/inertia";
import AdminSideBar from "@/Pages/Admin/AdminSideBar";
import Success from "@/Components/flashmessage/Success";
import Fail from "@/Components/flashmessage/Fail";
import FirstConnectionPopUp from "@/Components/firstconnection/FirstConnectionPopUp";
import Nocontent from "@/Components/nocontent/Nocontent";



const props = defineProps({
    user: Object,
    brevets: Array,
    chercheurs: Array,
    first_time: Boolean
});

const form = useForm(
    {
        id: null
    }
)

const downl = useForm(
    {
        id: null
    }
)

function download(brevet) {
    downl.id = brevet
    Inertia.get('/brevet/download', downl)
}

function etat(brevet) {
    form.id = brevet
    Inertia.post('/brevet/etat', form)
}

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
const tab = ref(props.chercheurs);
const message = ref(true);
const errors = ref(true);

watch(search, () => {
    tab.value = props.chercheurs.filter(
        chercheur => chercheur['nom'].toUpperCase().includes(search.value.toUpperCase())
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
</script>

<template>
    <Head title="Rapports" />
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
                        <h3 class="text-gray-700 text-3xl font-medium">Brevets</h3>
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
                        <div v-if="tab.length !== 0" class="flex flex-col mt-8">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div>
                                    <div v-if="annuler"
                                        v-for="i in tab.length"
                                         class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
                                        <div class="flex items-start justify-center">
                                            <div
                                                class="bg-white rounded-md shadow-md overflow-hidden flex flex-col justify-center items-center">
                                                <div>
                                                    <img class="object-center object-cover h-auto w-full"
                                                         :src="tab[i - 1].photo"
                                                         alt="photo">
                                                </div>
                                                <div class="text-center py-8 sm:py-6">
                                                    <span class="text-xl font-bold mb-2">{{tab[i - 1].nom}} {{tab[i - 1].prenom}}</span>
                                                    <p class="text-base text-lab-grey font-normal">{{tab[i - 1].email}}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-1 flex-col w-full">
                                            <h2
                                                class="text-center py-8">
                                                Mes brevets
                                            </h2>
                                            <div class="bg-white shadow-md rounded-md">
                                                <ul class="divide-y divide-gray-300">
                                                    <li v-for="brevet in brevets[i - 1]"
                                                        class="p-4 hover:bg-gray-50 cursor-pointer flex flex-row justify-between items-center">
                                                        <p>{{brevet.intitule}}</p>
                                                        <Link v-if="brevet.etat"
                                                              @click="etat(brevet.id)"
                                                              class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Rejeter
                                                        </Link>
                                                        <Link

                                                            v-if="!brevet.etat"
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                                                            @click="etat(brevet.id)">
                                                            Accepter

                                                        </Link>
                                                        <button
                                                            class="hover:bg-lab-purple hover:text-white text-gray-800 py-2 px-4 rounded-md inline-flex items-center"
                                                            @click="download(brevet.id)">
                                                            Télécharger
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div v-if="brevets[i - 1].length == 0"
                                                class="bg-white shadow-md rounded-md overflow-hidden">
                                                <img src="/images/nodata/nodata.jpg" alt="no data">
                                            </div>
                                        </div>

                                    </div>
                                    <div v-if="!annuler"
                                        v-for="i in chercheurs.length"
                                         class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
                                        <div class="flex items-start justify-center">
                                            <div
                                                class="bg-white rounded-md shadow-md overflow-hidden flex flex-col justify-center items-center">
                                                <div>
                                                    <img class="object-center object-cover h-auto w-full"
                                                         :src="chercheurs[i - 1].photo"
                                                         alt="photo">
                                                </div>
                                                <div class="text-center py-8 sm:py-6">
                                                    <span class="text-xl font-bold mb-2">{{chercheurs[i - 1].nom}} {{chercheurs[i - 1].prenom}}</span>
                                                    <p class="text-base text-lab-grey font-normal">{{chercheurs[i - 1].email}}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-1 flex-col w-full">
                                            <h2
                                                class="text-center py-8">
                                                Mes brevets
                                            </h2>
                                            <div class="bg-white shadow-md rounded-md">
                                                <ul class="divide-y divide-gray-300">
                                                    <li v-for="brevet in brevets[i - 1]"
                                                        class="p-4 hover:bg-gray-50 cursor-pointer flex flex-row justify-between items-center">
                                                        <p>{{brevet.intitule}}</p>
                                                        <Link v-if="brevet.etat"
                                                              @click="etat(brevet.id)"
                                                              class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Rejeter
                                                        </Link>
                                                        <Link

                                                            v-if="!brevet.etat"
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                                                            @click="etat(brevet.id)">
                                                            Accepter

                                                        </Link>
                                                        <button
                                                            class="hover:bg-lab-purple hover:text-white text-gray-800 py-2 px-4 rounded-md inline-flex items-center"
                                                            @click="download(brevet.id)">
                                                            Télécharger
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div v-if="brevets[i - 1].length == 0"
                                                 class="bg-white shadow-md rounded-md overflow-hidden">
                                                <img src="/images/nodata/nodata.jpg" alt="no data">
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







