<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue'
import modal from "../../Components/DialogModal.vue";
import btnPrimary from "../../Components/PrimaryButton.vue";

defineProps({
    isCliente: Boolean,
});

const usuarios = ref([]);
const openModalAddUser = ref(false);
const openModalviewUser = ref(false);
const openModalEditUser = ref(false);
const modelUser = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    raw_rol: 'cliente',
    terms: false,
});
const modelUserView = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    raw_rol: 'cliente',
    terms: false,
});
const modelEditView = ref({
    id: '',
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    raw_rol: 'cliente',
    terms: false,
});

const getUsuarios = async () => {
    try {
        let { data } = await axios('/api/get-users-clientes');
        usuarios.value = data
    } catch (error) {
        console.log(error)
    }
}

const addUser = async () => {
    try {
        await axios.post(route('create.new.cliente'), modelUser.value)
        openModalAddUser.value = false;
        modelUser.value = {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            raw_rol: 'cliente',
            terms: false,
        }
        getUsuarios()
    } catch (error) {
        console.log(error)
    }
}

const ViewUSer = async (user) => {
    try {
        let { data } = await axios(route('view.cliente', { id: user.id }))
        modelUserView.value = data
        openModalviewUser.value = true
    } catch (error) {
        console.log(error)
    }
}
const ViewToEditUSer = async (user) => {
    try {
        let { data } = await axios(route('view.cliente', { id: user.id }))
        modelEditView.value = data
        openModalEditUser.value = true
    } catch (error) {
        console.log(error)
    }
}
const EditUser = async () => {
    try {
        let { data } = await axios.post(route('edit.cliente', { id: modelEditView.value.id }), modelEditView.value)
        modelEditView.value = data
        openModalEditUser.value = false
        getUsuarios()
    } catch (error) {
        console.log(error)
    }
}

onMounted(() => {
    getUsuarios()
})
</script>
<template>
    <AppLayout title="Usuarios" :isCliente="isCliente">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex gap-4 justify-between">
                <p>Usuarios</p>
                <div class="flex gap-4">
                    <div class="float-right self-end">
                        <button @click.prevent="openModalAddUser = true"
                            class="bg-blue-500 hover:bg-blue-600 rounded-full p-3 shadow-lg float-right">
                            <svg width="20px" height="20px" viewBox="-2.5 0 32 32" version="1.1">
                                <g id="icomoon-ignore">
                                </g>
                                <path
                                    d="M18.723 21.788c-1.15-0.48-3.884-1.423-5.565-1.919-0.143-0.045-0.166-0.052-0.166-0.649 0-0.493 0.203-0.989 0.401-1.409 0.214-0.456 0.468-1.224 0.559-1.912 0.255-0.296 0.602-0.88 0.826-1.993 0.196-0.981 0.104-1.338-0.026-1.673-0.013-0.035-0.028-0.070-0.038-0.105-0.049-0.23 0.018-1.425 0.186-2.352 0.116-0.636-0.030-1.989-0.906-3.108-0.553-0.707-1.611-1.576-3.544-1.696l-1.060 0.001c-1.9 0.12-2.96 0.988-3.513 1.695-0.876 1.119-1.021 2.472-0.906 3.108 0.169 0.928 0.236 2.123 0.187 2.348-0.010 0.039-0.025 0.074-0.039 0.11-0.129 0.335-0.221 0.692-0.025 1.673 0.222 1.113 0.57 1.697 0.826 1.993 0.090 0.688 0.344 1.456 0.559 1.912 0.157 0.334 0.23 0.788 0.23 1.431 0 0.597-0.023 0.604-0.157 0.646-1.738 0.513-4.505 1.513-5.537 1.965-0.818 0.351-1.017 0.98-1.017 1.548s0 2.251 0 2.623c0 0.371 0.22 1.006 1.017 1.006 0.613 0 5.518 0 7.746 0 0.668 0 1.098 0 1.098 0h0.192c0 0 0.437 0 1.115 0 2.237 0 7.135 0 7.747 0 0.796 0 1.017-0.634 1.017-1.006s0-2.055 0-2.623-0.392-1.262-1.209-1.613zM18.876 25.98h-17.827v-2.579c0-0.318 0.092-0.46 0.388-0.587 0.994-0.435 3.741-1.426 5.434-1.926 0.889-0.282 0.889-1.070 0.889-1.646 0-0.801-0.106-1.397-0.331-1.878-0.172-0.366-0.392-1.022-0.468-1.601l-0.041-0.312-0.206-0.238c-0.113-0.13-0.396-0.538-0.59-1.513-0.153-0.759-0.085-0.935-0.031-1.076 0.031-0.076 0.058-0.152 0.081-0.237l0.005-0.022 0.005-0.022c0.105-0.495-0.037-1.962-0.181-2.755-0.067-0.365 0.017-1.401 0.7-2.273 0.418-0.534 1.229-1.19 2.722-1.293l0.992-0.001c1.219 0.083 2.145 0.518 2.752 1.294 0.682 0.872 0.766 1.909 0.7 2.275-0.148 0.814-0.287 2.257-0.18 2.758l0.008 0.039 0.011 0.038c0.016 0.054 0.036 0.108 0.056 0.161l0.009 0.026 0.001 0.002c0.059 0.153 0.127 0.326-0.024 1.087-0.196 0.974-0.479 1.384-0.592 1.515l-0.204 0.237-0.042 0.31c-0.076 0.578-0.296 1.237-0.468 1.603-0.247 0.525-0.5 1.157-0.5 1.856 0 0.577 0 1.367 0.918 1.655 1.641 0.485 4.345 1.416 5.448 1.877 0.418 0.179 0.574 0.493 0.574 0.649l-0.006 2.579z"
                                    fill="#ffffff">

                                </path>
                                <path
                                    d="M23.078 14.441v-4.185h-1.049v4.185h-4.186v1.049h4.186v4.185h1.049v-4.185h4.185v-1.049z"
                                    fill="#ffffff">

                                </path>
                            </svg>
                        </button>
                    </div>
                    <div class="">
                        <button @click.prevent="openModalAddUser = true"
                            class="bg-blue-500 hover:bg-blue-600 rounded-full p-3 shadow-lg float-right">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <title />
                                <g id="Complete">
                                    <g id="upload">
                                        <g>
                                            <path d="M3,12.3v7a2,2,0,0,0,2,2H19a2,2,0,0,0,2-2v-7" fill="none"
                                                stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" />
                                            <g>
                                                <polyline data-name="Right" fill="none" id="Right-2"
                                                    points="7.9 6.7 12 2.7 16.1 6.7" stroke="#ffffff" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" />
                                                <line fill="none" stroke="#ffffff" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" x1="12" x2="12" y1="16.3"
                                                    y2="4.8" />
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </button>
                    </div>
                </div>
            </h2>
        </template>
        <div class="overflow-x-auto flex items-center justify-center">
            <div class="w-full lg:w-5/6">
                <div class="bg-white shadow-md rounded my-6">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-start">Nombre</th>
                                <th class="py-3 px-6 text-start">Correo</th>
                                <th class="py-3 px-6 text-start">Rol</th>
                                <th class="py-3 px-6 text-start">Solicitudes de Creditos</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            <tr class="border-b border-gray-200 hover:bg-gray-100" v-for="(usuario, u) in usuarios"
                                :key="u">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium text-bold">{{ usuario.name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium text-bold">{{ usuario.email }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium text-bold">{{ usuario.raw_rol }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span
                                            class="font-medium text-bold bg-yellow-200 text-black py-1 px-3 rounded-full text-xs">{{
                                                usuario.has_many_credits_requests.length }}</span>

                                    </div>
                                </td>

                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <div class="w-4 mr-2 transform  hover:scale-110" @click.prevent="ViewUSer(usuario)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </div>
                                        <div class="w-4 mr-2 transform  hover:scale-110"
                                            @click.prevent="ViewToEditUSer(usuario)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </div>
                                        <div class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                            <svg width="17px" height="17px" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg" fill="none">
                                                <path fill="#000000" fill-rule="evenodd"
                                                    d="M10 3a7 7 0 100 14 7 7 0 000-14zm-9 7a9 9 0 1118 0 9 9 0 01-18 0zm8-4a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm.01 8a1 1 0 102 0V9a1 1 0 10-2 0v5z" />
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <modal :show="openModalAddUser" maxWidth="lg">
            <template #title>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 max-h-fit " @click="openModalAddUser = false">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </template>

            <template #content>
                <form>
                    <div class="flex flex-col gap-4 mt-8 justify-center rounded-lg bg-white  dark:bg-neutral-700 w-full">
                        <input v-model="modelUser.name" type="text"
                            class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="Nombre">
                        <input v-model="modelUser.email" type="text"
                            class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="Correo">
                        <input v-model="modelUser.password" type="password"
                            class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="Contraseña">
                        <input v-model="modelUser.password_confirmation" type="password"
                            class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="confirmar contraseña">
                    </div>
                </form>
            </template>
            <template #footer>
                <btnPrimary @click.prevent="addUser">
                    Agregar
                </btnPrimary>
            </template>
        </modal>
        <modal :show="openModalviewUser" maxWidth="lg">
            <template #title>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 max-h-fit " @click="openModalviewUser = false">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </template>

            <template #content>
                <form>
                    <div class="flex flex-col gap-4 mt-8 justify-center rounded-lg bg-white  dark:bg-neutral-700 w-full">
                        <input disabled v-model="modelUserView.name" type="text"
                            class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="Nombre">
                        <input disabled v-model="modelUserView.email" type="text"
                            class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="Correo">
                        <input disabled v-model="modelUserView.password" type="password"
                            class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="Contraseña">
                        <input disabled v-model="modelUserView.password_confirmation" type="password"
                            class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="confirmar contraseña">
                    </div>
                </form>
            </template>
            <template #footer>
                <!-- <btnPrimary @click.prevent="addUser">
                    Agregar
                </btnPrimary> -->
            </template>
        </modal>
        <modal :show="openModalEditUser" maxWidth="lg">
            <template #title>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 max-h-fit " @click="openModalEditUser = false">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </template>

            <template #content>
                <form>
                    <div class="flex flex-col gap-4 mt-8 justify-center rounded-lg bg-white  dark:bg-neutral-700 w-full">
                        <input v-model="modelEditView.name" type="text"
                            class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="Nombre">
                        <input v-model="modelEditView.email" type="text"
                            class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="Correo">
                        <input v-model="modelEditView.password" type="password"
                            class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="Contraseña">
                        <input v-model="modelEditView.password_confirmation" type="password"
                            class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="confirmar contraseña">
                    </div>
                </form>
            </template>
            <template #footer>
                <btnPrimary @click.prevent="EditUser">
                    Editar
                </btnPrimary>
            </template>
        </modal>
    </AppLayout>
</template>
