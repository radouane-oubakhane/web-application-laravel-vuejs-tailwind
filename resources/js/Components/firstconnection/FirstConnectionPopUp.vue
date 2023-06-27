<template>
    <div
         class="min-w-screen h-screen min-h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover">
        <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
        <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
        <div class="container mx-auto">
            <div class="flex justify-center px-6 my-12">
                <div class="w-full xl:w-3/4 lg:w-11/12 flex justify-center z-10 shadow-md">
                    <div class="w-full lg:w-7/12 bg-white p-5 rounded-lg">
                        <h3 class="pt-4 text-2xl text-center text-lab-purple">Changer votre mot de pass</h3>
                        <div
                            class="px-8 pt-6 mb-2 bg-white rounded">
                            <div class="ml-2 mb-4">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="password">
                                    Mot de passe
                                </label>
                                <input

                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="Mot de passe"
                                    v-model="form.password"
                                    required


                                />
                            </div>
                            <div class="ml-2 mb-10">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="confirmpassword">
                                    Confirmer mot de passe
                                </label>
                                <input
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="confirmpassword"
                                    name="confirmpassword"
                                    type="password"
                                    placeholder="Confirmer mot de passe"
                                    v-model="form.confPass"
                                    required
                                />
                                <p v-if="error"
                                   class="block m-4 text-sm text-red-700">
                                    Mot de passe incorrect
                                </p>
                            </div>
                            <div class="mb-6 text-center">
                                <button
                                    @click="update"
                                    class="w-full px-4 py-2 font-bold text-white bg-lab-purple rounded-full hover:bg-indigo-700 focus:outline-none focus:shadow-outline"
                                    >
                                    Enregistrer les modifications
                                </button>
                            </div>
                            <hr class="mb-6 border-t" />
                            <div class="mb-6 text-center">
                                <BreezeResponsiveNavLink :href="route('logout')" method="post" as="button"
                                                         class="w-full px-4 py-2 font-bold text-white bg-lab-red rounded-full hover:bg-red-700 focus:outline-none focus:shadow-outline">
                                    Se déconnecter
                                </BreezeResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {ref, watch} from "vue";

const form = useForm({
    password: null,
    confPass: null
});

const error = ref(null);




function update() {
    if (form.password === form.confPass) {
        error.value = false
        Inertia.post('/comptes/edit', form);
    }
    else error.value = true
}

watch(form.confPass, () => {
    error.value = form.password !== form.confPass;
})


</script>

