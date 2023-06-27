<script setup>
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Head, Link, useForm} from '@inertiajs/inertia-vue3';
import {ref} from "vue";
import AdminSideBar from '@/Pages/Admin/AdminSideBar';
import {Inertia} from "@inertiajs/inertia";
defineProps({
    user: String,
    table: Array,
});
let sidebarOpen = ref(false);
let dropdownOpen = ref(true);
let logOut = ref(false);
let profil = ref(false);
const updateProf = ref(false);
const password = ref(null);
const confirmPassword = ref(null);
const error = ref(false);

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
                        <span class="text-white text-2xl mx-2 font-semibold">{{user}}</span>
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
                                     src="https://images.unsplash.com/photo-1528892952291-009c663ce843?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=296&amp;q=80"
                                     alt="Your avatar">
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10"
                                 :class="{hidden: dropdownOpen}">
                                <a href="#"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Profile</a>
                                <a @click="logOut =! logOut"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Logout</a>
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
                                    <h2 class="text-xl font-bold py-4 ">Are you sure?</h2>
                                    <p class="text-sm text-gray-500 px-8">Do you really want to log out?</p>
                                </div>
                                <div class="p-3  mt-2 text-center space-x-4 md:block">
                                    <button @click="clickLogOut" class="mb-2 md:mb-0 bg-white px-5 py-2 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-md hover:shadow-lg hover:bg-gray-100">
                                        Non
                                    </button>
                                    <BreezeResponsiveNavLink :href="route('logout')" method="post" as="button"  class="mb-2 md:mb-0 bg-red-500 border border-lab-red px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-md hover:shadow-lg hover:bg-red-600">
                                        Yes
                                    </BreezeResponsiveNavLink>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                    <div class="container mx-auto px-6 py-8">
                        <h3 class="text-gray-700 text-3xl font-medium">Projets</h3>
                        <div class="mt-8">

                        </div>
                        <form class="flex items-center">
                            <label for="voice-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <input type="text" id="voice-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Rechercher des chercheurs, des doctorants, des projets..." required>
                                <button type="button" class="flex absolute inset-y-0 right-0 items-center pr-3">
                                </button>
                            </div>
                            <button type="submit" class="inline-flex items-center py-2.5 px-3 ml-2 text-sm font-medium text-white bg-lab-purple rounded-md border border-lab-purple hover:bg-lab-purple focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <i class="fa-solid fa-magnifying-glass pr-2"></i>
                                Recherche</button>
                        </form>

                        <div class="flex flex-col mt-8">
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
                                                Detail</th>
                                            <th
                                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                Equipe</th>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white">
                                        <tr v-for="projet in table">
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm leading-5 font-medium text-gray-900">{{projet.intitule}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900">{{projet.detail}}</div>
                                            </td>

                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                                {{projet.equipe}}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
                <img class="absolute h-full w-full object-cover" src="images/event-1.jpg" >
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
                <button @click="updateProfil" type="button" class="text-white bg-gradient-to-br from-green-600 to-green-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Edit Profile</button>
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
                        <h3 class="pt-4 text-2xl text-center text-lab-purple">Editer Un Evenement</h3>
                        <form
                            class="px-8 pt-6 pb-2 mb-4 bg-white rounded">
                            <div class="mb-2">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="nom">
                                    Email
                                </label>
                                <input
                                    v-model="profilForm.email"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="nom"
                                    name="nom"
                                    type="email"
                                    :placeholder="user.email"

                                />
                            </div>
                            <div class="mb-2">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="nom">
                                    Numero Telephone
                                </label>
                                <input
                                    v-model="profilForm.numeroTelephone"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="nom"
                                    name="nom"
                                    type="text"
                                    :placeholder="user.numero_telephone"
                                />
                            </div>
                            <div class="mb-2">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="photo">
                                    Photo
                                    <span class="text-xs pt-1 italic text-red-500"> fichier PNG, JPG</span>
                                </label>
                                <input
                                    @input="profilForm.photo = $event.target.files[0]"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="photo"
                                    name="photo"
                                    type="file"
                                />
                            </div>
                            <div class="mb-4 md:flex md:justify-between">
                                <div class="mb-4 md:mr-2 md:mb-0">
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="nom">
                                        Password
                                    </label>
                                    <input
                                        v-model="password"
                                        class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        id="nom"
                                        name="nom"
                                        type="password"
                                        placeholder="password"


                                    />
                                </div>
                                <div class="md:ml-2">
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="nom">
                                        Confirm Your Password
                                    </label>
                                    <input
                                        v-model="confirmPassword"
                                        class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        id="nom"
                                        name="nom"
                                        type="password"
                                        placeholder="Confirm Your Password"
                                    />
                                    <p v-if="error"
                                       class="block mb-4 text-sm text-red-700">
                                        password incorrect
                                    </p>
                                </div>
                            </div>
                            <div class="mb-6 text-center">
                                <button
                                    class="w-full px-4 py-2 font-bold text-white bg-lab-purple rounded-full hover:bg-indigo-700 focus:outline-none focus:shadow-outline"
                                    @click="updateP">
                                    Editer un Profil
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
</template>







