<script setup>
import {Link, Head, useForm} from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

defineProps({
    fonctions: Array
});

const form = useForm({
    nom: null,
    prenom: null,
    email: null,
    numeroTelephone: null,
    cin: null,
    cne: null,
    dateNaissance: null,
    lieuxNaissance: null,
    fonctionId: null,
    photo: null
});

const submit = () => {
    Inertia.post('/inscriptions/store', form)
}



</script>
<template>

    <div class="relative min-h-screen items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 bg-gray-500 bg-no-repeat bg-cover items-center"
         style="background-image: url('/images/inscription/background.jpg')">
        <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
    <div class="container mx-auto">
        <div class="flex justify-center px-6 my-12">
            <div class="w-full xl:w-3/4 lg:w-11/12 flex z-10 shadow-md">
                <div
                    class="w-full h-auto bg-gray-400 hidden lg:block lg:w-5/12 bg-cover rounded-l-lg"
                    style="background-image: url('/images/inscription/formimg.png')"
                ></div>
                <div class="w-full lg:w-7/12 bg-white p-5 rounded-lg lg:rounded-l-none">
                    <h3 class="pt-4 text-2xl text-center text-lab-purple">Créer un compte</h3>
                    <form @submit.prevent="submit"
                          class="px-8 pt-6 pb-8 mb-4 bg-white rounded">
                        <div class="mb-4 md:flex md:justify-between">
                            <div class="mb-4 md:mr-2 md:mb-0">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="nom">
                                    Nom
                                </label>
                                <input
                                    v-model="form.nom"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="nom"
                                    name="nom"
                                    type="text"
                                    placeholder="Nom"
                                    required
                                />
                            </div>
                            <div class="md:ml-2">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="prenom">
                                    Prenom
                                </label>
                                <input
                                    v-model="form.prenom"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="prenom"
                                    name="prenom"
                                    type="text"
                                    placeholder="Prenom"
                                    required
                                />
                            </div>
                        </div>
                        <div class="mb-4 md:flex md:justify-between">
                            <div class="mb-4 md:mr-2 md:mb-0">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="cin">
                                    CIN
                                </label>
                                <input
                                    v-model="form.cin"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="cin"
                                    name="cin"
                                    type="text"
                                    placeholder="CIN"
                                    required
                                />
                            </div>
                            <div class="md:ml-2">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="cin">
                                    CNE
                                </label>
                                <input
                                    v-model="form.cne"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="cne"
                                    name="cin"
                                    type="text"
                                    placeholder="CNE"
                                />
                                <p class="text-xs pt-1 italic text-red-500">si vous êtes un doctorant.</p>
                            </div>
                        </div>
                        <div class="mb-4 md:flex md:justify-between">
                            <div class="mb-4 md:mr-2 md:mb-0">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="email">
                                    Email
                                </label>
                                <input
                                    v-model="form.email"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="email"
                                    name="email"
                                    type="email"
                                    placeholder="Email"
                                    required
                                />
                            </div>
                            <div class="md:ml-2">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="phone">
                                    Numero de telephone
                                </label>
                                <input
                                    v-model="form.numeroTelephone"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    placeholder="Numero de telephone"
                                    required
                                />
                            </div>
                        </div>
                        <div class="mb-4 md:flex md:justify-between">
                            <div class="mb-4 md:mr-2 md:mb-0">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="date">
                                    Date de naissance
                                </label>
                                <input
                                    v-model="form.dateNaissance"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="date"
                                    name="date"
                                    type="date"
                                    placeholder="Date de naissance"
                                    required
                                />
                            </div>
                            <div class="md:ml-2">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="lieux">
                                    Lieux de naissance
                                </label>
                                <input
                                    v-model="form.lieuxNaissance"
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="lieux"
                                    name="lieux"
                                    type="text"
                                    placeholder="Lieux de naissance"
                                    required
                                />
                            </div>
                        </div>
                        <div class="mb-4">
                            <p class="block mb-2 text-sm font-bold text-gray-700">
                                Photo
                                <span class="text-xs pt-1 italic text-red-500"> fichier PNG, JPG</span>
                            </p>
                            <div class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">

                                <label for="photo">cliquez ici pour télécharger votre photo de profil</label>
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
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-bold md:text-center text-gray-700" for="email">
                                Fonction
                            </label>
                            <select class="w-full px-3 py-2 mb-3 text-sm bg-white leading-tight text-gray-700 text-center border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    name="fonction"
                                    v-model="form.fonctionId"
                                    required>
                                <option v-for="fonction in fonctions" :value="fonction.id" :key="fonction.id">{{fonction.intitule.toUpperCase()}}</option>
                            </select>
                        </div>
                        <div class="mb-6 text-center">
                            <button
                                class="w-full px-4 py-2 font-bold text-white bg-lab-purple rounded-full hover:bg-indigo-700 focus:outline-none focus:shadow-outline"
                                type="submit">
                                Créer un compte
                            </button>
                        </div>
                        <hr class="mb-6 border-t" />
                        <div class="text-center">
                            <Link
                                class="inline-block text-sm text-blue-500 align-baseline hover:text-blue-800"
                                :href="route('login')">
                                Vous avez déjà un compte? Connexion!
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

