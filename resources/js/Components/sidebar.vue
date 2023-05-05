<script setup >
import { ref, computed, onMounted } from "vue";
import { useComponentStore } from '../stores/componentStore.js'
import modal from "./DialogModal.vue";
import moment from "moment";
const store = useComponentStore();

const isOpen = computed({
    get() {
        return store.sideBar
    },
    set(val) {
        store.sideBar = val
    }
})

const openModal = ref(false);
const openModalNewMessage = ref(false);
const searchTerm = ref('');
const results = ref([]);
const filteredResults = ref([]);
const filterResults = () => {
    filteredResults.value = results.value.filter(result =>

        result.name.toString().toLowerCase().includes(searchTerm.value.toLowerCase())
    );
}
const selectResult = (result) => {
    searchTerm.value = result;
    filteredResults.value = [];
}

const getUsers = async () => {
    try {
        let { data } = await axios('/api/get-users');
        results.value = data
    } catch (error) {
        console.log(error)
    }
}

const mensajes = ref([])
const Dialogos = ref([]);
const MensajeAbierto = ref(null);

const getMensajes = async () => {
    try {
        let { data } = await axios('/api/get-mensajes');
        mensajes.value = data
    } catch (error) {
        console.log(error)
    }
}

const mensajeNewEmisor = ref('');
const mensajeRespuestaEmisor = ref('');

const sendNewMensaje = async (u) => {
    try {
        await axios.post('/api/send-new-mensaje', {emisor:u, receptor: searchTerm.value, mensaje: mensajeNewEmisor.value})
        openModalNewMessage.value = false
        searchTerm.value = '';
        filteredResults.value = [];
        getMensajes()
    } catch (error) {
        console.log(error)
    }
}
const sendRespuestaMensaje = async (u) => {
    try {
        await axios.post('/api/responder-mensajes', {receptor:u, mensaje:MensajeAbierto.value, respuesta: mensajeRespuestaEmisor.value})
        openModal.value = false
        mensajeRespuestaEmisor.value = '';
        getMensajes()
    } catch (error) {
        console.log(error)
    }
}

const OpenMensajes = (data) => {
    try {
        MensajeAbierto.value = data
        Dialogos.value = data
        openModal.value = true
    } catch (error) {
        console.log(error)
    }
}

onMounted(() => {
    getUsers()
    getMensajes()
})

</script>
<template>
    <!-- Sidebar -->
    <div class=" overflow-y-scroll fixed inset-y-0 left-0 w-4/12 px-4 py-8 bg-white border-r  z-50" v-show="isOpen">
        <div class="px-5 py-5 flex justify-between items-center bg-white gap-4 ">
            <div class="w-full">
                <input type="text" name="" id="" placeholder="Buscar chat"
                    class="rounded-2xl bg-gray-100 py-3 px-5 w-full" />
            </div>
            <div class="">
                <button @click.prevent="openModalNewMessage = true"
                    class="bg-blue-500 hover:bg-blue-600 rounded-full p-3 shadow-lg">
                    <svg fill="#ffffff" width="20px" height="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20 2H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h3v3.767L13.277 18H20c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zm0 14h-7.277L9 18.233V16H4V4h16v12z" />
                        <path d="M11 14h2v-3h3V9h-3V6h-2v3H8v2h3z" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="flex flex-col w-12/12 px-4  overflow-y-auto">
            <!-- mensajes list -->
            <div @click.prevent="OpenMensajes(mensaje.dialogo)" v-for="(mensaje, m) in mensajes" :key="m" class="flex drop-shadow-md flex-row py-4 px-2 items-center border-2 m-1 rounded-lg  border-l-4 border-blue-400 hover:cursor-pointer">
                <div class="w-1/4">
                    <img src="https://source.unsplash.com/L2cxSuKWbpo/600x600" class="object-cover h-12 w-12 rounded-full"
                        alt="" />
                </div>
                <div class="w-full">
                    <div class="text-lg font-semibold">De:{{mensaje.emisor.email}}</div>
                    <span class="text-gray-500">para: {{mensaje.receptor.email}}</span>
                </div>
            </div>
            <!-- end mensajes list -->
        </div>
        <div class="w-full flex justify-center">
            <button @click.prevent="isOpen = false"
                class="w-10/12 mx-auto  left-0 py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600">
                Cerrar
            </button>
        </div>
    </div>
    <!-- Overlay -->
    <div class="fixed inset-0 bg-gray-900 opacity-50 z-40" v-show="isOpen" @click="isOpen = false" />

    <modal :show="openModal" maxWidth="2xl">
        <template #title>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 max-h-fit " @click="openModal = false">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20">
                    <title>Close</title>
                    <path
                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
            </span>
        </template>

        <template #content>
            <div class="flex mt-8 justify-center rounded-lg bg-white  dark:bg-neutral-700 w-full">
                <!-- message -->
                <div class="w-full px-5 flex flex-col justify-between "  >
                    <div class="flex flex-col mt-5 max-h-96 " v-for="(dialogo, d) in Dialogos" :key="d" >
                        <!-- emisor -->
                        <div class="text-[#918f8f] w-full text-end" v-if="dialogo.dialogo_emisor">
                            <p class=" mx-4 my-1"> {{moment(dialogo.created_at).format('LLL')}} </p>
                        </div>
                        <div class="flex justify-end mb-4" v-if="dialogo.dialogo_emisor">
                            <div
                                class="-mr-1 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white max-w-lg break-words">
                                {{dialogo.dialogo_emisor}}
                            </div>
                            <svg width="40px" height="40px" class="w-40" viewBox="0 0 32 32"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="avatar" transform="translate(-1407 -182)">
                                    <circle id="Ellipse_16" data-name="Ellipse 16" cx="15" cy="15" r="15"
                                        transform="translate(1408 183)" fill="#e8f7f9" stroke="#333" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" />
                                    <g id="Group_49" data-name="Group 49">
                                        <circle id="Ellipse_17" data-name="Ellipse 17" cx="4.565" cy="4.565" r="4.565"
                                            transform="translate(1418.435 192.13)" fill="#fff1b6" stroke="#333"
                                            stroke-miterlimit="10" stroke-width="2" />
                                        <path id="Path_53" data-name="Path 53"
                                            d="M1423,213a14.928,14.928,0,0,0,9.4-3.323,9.773,9.773,0,0,0-18.808,0A14.928,14.928,0,0,0,1423,213Z"
                                            fill="#fff1b6" stroke="#333" stroke-miterlimit="10" stroke-width="2" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <!-- receptor -->
                        <div class="text-[#918f8f] w-full text-start" v-if="dialogo.respuesta">
                            <p class=" mx-4 my-1">{{moment(dialogo.respuesta.created_at).format('LLL')}}</p>
                        </div>
                        <div class="flex justify-start mb-4" v-if="dialogo.respuesta">
                            <svg width="40px" height="40px" class=" w-40 " viewBox="0 0 32 32"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="avatar" transform="translate(-1407 -182)">
                                    <circle id="Ellipse_16" data-name="Ellipse 16" cx="15" cy="15" r="15"
                                        transform="translate(1408 183)" fill="#e8f7f9" stroke="#333" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" />
                                    <g id="Group_49" data-name="Group 49">
                                        <circle id="Ellipse_17" data-name="Ellipse 17" cx="4.565" cy="4.565" r="4.565"
                                            transform="translate(1418.435 192.13)" fill="#fff1b6" stroke="#333"
                                            stroke-miterlimit="10" stroke-width="2" />
                                        <path id="Path_53" data-name="Path 53"
                                            d="M1423,213a14.928,14.928,0,0,0,9.4-3.323,9.773,9.773,0,0,0-18.808,0A14.928,14.928,0,0,0,1423,213Z"
                                            fill="#fff1b6" stroke="#333" stroke-miterlimit="10" stroke-width="2" />
                                    </g>
                                </g>
                            </svg>
                            <div
                                class="ml-2 mr-4 py-3 px-4 bg-gray-400 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-white max-w-lg break-words">
                                {{ dialogo.respuesta.dialogo_receptor}}
                            </div>
                        </div>
                    </div>
                    <!-- acciones -->
                    <div class="py-5 flex  gap-4">
                        <!-- mensaje -->
                        <input class="w-full bg-gray-300 py-5 px-3 rounded-xl" type="text"
                            placeholder="Escribe un mensaje aqui ..." v-model="mensajeRespuestaEmisor"/>
                        <!-- btn enviar mensaje -->
                        <div class="">
                            <button @click.prevent="sendRespuestaMensaje($page.props.auth.user)"
                                class="bg-blue-500 hover:bg-blue-600 rounded-full p-3 shadow-lg">
                                <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="Communication / Chat">
                                        <path id="Vector"
                                            d="M5.59961 19.9203L7.12357 18.7012L7.13478 18.6926C7.45249 18.4384 7.61281 18.3101 7.79168 18.2188C7.95216 18.1368 8.12328 18.0771 8.2998 18.0408C8.49877 18 8.70603 18 9.12207 18H17.8031C18.921 18 19.4806 18 19.908 17.7822C20.2843 17.5905 20.5905 17.2842 20.7822 16.9079C21 16.4805 21 15.9215 21 14.8036V7.19691C21 6.07899 21 5.5192 20.7822 5.0918C20.5905 4.71547 20.2837 4.40973 19.9074 4.21799C19.4796 4 18.9203 4 17.8002 4H6.2002C5.08009 4 4.51962 4 4.0918 4.21799C3.71547 4.40973 3.40973 4.71547 3.21799 5.0918C3 5.51962 3 6.08009 3 7.2002V18.6712C3 19.7369 3 20.2696 3.21846 20.5433C3.40845 20.7813 3.69644 20.9198 4.00098 20.9195C4.35115 20.9191 4.76744 20.5861 5.59961 19.9203Z"
                                            stroke="#ffffff" stroke-width="3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </g>
                                </svg>
                            </button>
                        </div>
                        <!-- btn enviar adjuntar documento -->
                        <div class="">
                            <button @click.prevent="isOpen = true"
                                class="bg-blue-500 hover:bg-blue-600 rounded-full p-3 shadow-lg">
                                <svg fill="#ffffff" width="30px" height="30px" viewBox="0 0 64 64" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    xml:space="preserve" xmlns:serif="http://www.serif.com/"
                                    style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                                    <rect id="Icons" x="-320" y="-64" width="1280" height="800" style="fill:none;" />
                                    <g id="Icons1" serif:id="Icons">
                                        <g id="Strike">
                                        </g>
                                        <g id="H1">
                                        </g>
                                        <g id="H2">
                                        </g>
                                        <g id="H3">
                                        </g>
                                        <g id="list-ul">
                                        </g>
                                        <g id="hamburger-1">
                                        </g>
                                        <g id="hamburger-2">
                                        </g>
                                        <g id="list-ol">
                                        </g>
                                        <g id="list-task">
                                        </g>
                                        <g id="trash">
                                        </g>
                                        <g id="vertical-menu">
                                        </g>
                                        <g id="horizontal-menu">
                                        </g>
                                        <g id="sidebar-2">
                                        </g>
                                        <g id="Pen">
                                        </g>
                                        <g id="Pen1" serif:id="Pen">
                                        </g>
                                        <path id="link"
                                            d="M31.413,26.463c3.991,1.537 7.262,5.189 7.43,9.714c0.239,6.468 -6.379,11.427 -11.701,16.688c-2.086,2.009 -4.942,3.153 -7.842,3.135c-8.317,-0.155 -14.62,-11.625 -7.763,-18.74c3.923,-3.972 7.61,-8.964 11.931,-10.816c0.337,-0.145 0.681,-0.268 1.028,-0.371c0.016,1.283 0.124,2.918 0.495,4.281c-0.7,0.283 -1.356,0.69 -1.933,1.232c-4.472,4.311 -10.91,8.453 -10.504,13.891c0.257,3.451 3.395,6.412 6.969,6.39c1.757,-0.033 3.469,-0.744 4.732,-1.96c5.087,-5.029 12.486,-10.214 9.87,-16.115c-0.515,-1.162 -1.386,-2.1 -2.445,-2.767c-0.279,-1.209 -0.513,-2.876 -0.267,-4.562Zm1.174,11.045c-3.991,-1.537 -7.262,-5.189 -7.43,-9.715c-0.239,-6.467 6.379,-11.426 11.701,-16.688c2.086,-2.008 4.942,-3.152 7.842,-3.134c8.317,0.154 14.62,11.624 7.763,18.739c-3.923,3.972 -7.61,8.964 -11.931,10.816c-0.337,0.145 -0.681,0.268 -1.028,0.371c-0.016,-1.283 -0.124,-2.918 -0.495,-4.28c0.7,-0.283 1.356,-0.691 1.933,-1.233c4.472,-4.311 10.91,-8.453 10.504,-13.891c-0.257,-3.45 -3.395,-6.412 -6.969,-6.389c-1.757,0.032 -3.469,0.744 -4.732,1.96c-5.087,5.028 -12.486,10.213 -9.87,16.114c0.515,1.163 1.386,2.1 2.445,2.768c0.279,1.208 0.513,2.876 0.267,4.562Z" />
                                        <g id="clock">
                                        </g>
                                        <g id="external-link">
                                        </g>
                                        <g id="hr">
                                        </g>
                                        <g id="info">

                                        </g>

                                        <g id="warning">

                                        </g>

                                        <g id="plus-circle">

                                        </g>

                                        <g id="minus-circle">

                                        </g>

                                        <g id="vue">

                                        </g>

                                        <g id="cog">

                                        </g>

                                        <g id="logo">

                                        </g>

                                        <g id="radio-check">

                                        </g>

                                        <g id="eye-slash">

                                        </g>

                                        <g id="eye">

                                        </g>

                                        <g id="toggle-off">

                                        </g>

                                        <g id="shredder">

                                        </g>

                                        <g id="spinner--loading--dots-" serif:id="spinner [loading, dots]">

                                        </g>

                                        <g id="react">

                                        </g>

                                        <g id="check-selected">

                                        </g>

                                        <g id="turn-off">

                                        </g>

                                        <g id="code-block">

                                        </g>

                                        <g id="user">

                                        </g>

                                        <g id="coffee-bean">

                                        </g>

                                        <g id="coffee-beans">

                                            <g id="coffee-bean1" serif:id="coffee-bean">

                                            </g>

                                        </g>

                                        <g id="coffee-bean-filled">

                                        </g>

                                        <g id="coffee-beans-filled">

                                            <g id="coffee-bean2" serif:id="coffee-bean">

                                            </g>

                                        </g>

                                        <g id="clipboard">

                                        </g>

                                        <g id="clipboard-paste">

                                        </g>

                                        <g id="clipboard-copy">

                                        </g>

                                        <g id="Layer1">

                                        </g>

                                    </g>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- end message -->
            </div>
        </template>
        <template #footer>

        </template>
    </modal>
    <modal :show="openModalNewMessage" maxWidth="lg">
        <template #title>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 max-h-fit " @click="openModalNewMessage = false">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20">
                    <title>Close</title>
                    <path
                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
            </span>
        </template>

        <template #content>
            <div class="flex flex-col mt-8 justify-center rounded-lg bg-white  dark:bg-neutral-700 w-full">
                <input type="text" class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="Search"
                    v-model="searchTerm.email" @input="filterResults">
                    
                <ul v-if="filteredResults.length > 0" class="mt-2 mb-2 border border-gray-300 rounded-lg shadow-lg">
                    <li v-for="(result, r) in filteredResults" :key="r">
                        <div @click="selectResult(result)"
                            class="flex drop-shadow-md flex-row py-4 px-2 items-center border-2 m-1 rounded-lg  border-l-4 border-blue-400 hover:cursor-pointer">
                            <div class="w-1/4">
                                <svg width="40px" height="40px"  viewBox="0 0 32 32"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="avatar" transform="translate(-1407 -182)">
                                    <circle id="Ellipse_16" data-name="Ellipse 16" cx="15" cy="15" r="15"
                                        transform="translate(1408 183)" fill="#e8f7f9" stroke="#333" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" />
                                    <g id="Group_49" data-name="Group 49">
                                        <circle id="Ellipse_17" data-name="Ellipse 17" cx="4.565" cy="4.565" r="4.565"
                                            transform="translate(1418.435 192.13)" fill="#fff1b6" stroke="#333"
                                            stroke-miterlimit="10" stroke-width="2" />
                                        <path id="Path_53" data-name="Path 53"
                                            d="M1423,213a14.928,14.928,0,0,0,9.4-3.323,9.773,9.773,0,0,0-18.808,0A14.928,14.928,0,0,0,1423,213Z"
                                            fill="#fff1b6" stroke="#333" stroke-miterlimit="10" stroke-width="2" />
                                    </g>
                                </g>
                            </svg>
                            </div>
                            <div class="w-full">
                                <div class="text-lg font-semibold">{{result.name}}</div>
                                <span class="text-gray-500">{{result.email}}</span>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="flex gap-4 my-2">
                    <input class="w-full bg-gray-300 py-5 px-3 rounded-xl mt-2" type="text"
                        placeholder="Escribe un mensaje aqui ..."  v-model="mensajeNewEmisor" />
                    <div class=" self-center ">
                        <button @click.prevent="sendNewMensaje($page.props.auth.user)"
                            class="bg-blue-500 hover:bg-blue-600 rounded-full p-3 shadow-lg">
                            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="Communication / Chat">
                                    <path id="Vector"
                                        d="M5.59961 19.9203L7.12357 18.7012L7.13478 18.6926C7.45249 18.4384 7.61281 18.3101 7.79168 18.2188C7.95216 18.1368 8.12328 18.0771 8.2998 18.0408C8.49877 18 8.70603 18 9.12207 18H17.8031C18.921 18 19.4806 18 19.908 17.7822C20.2843 17.5905 20.5905 17.2842 20.7822 16.9079C21 16.4805 21 15.9215 21 14.8036V7.19691C21 6.07899 21 5.5192 20.7822 5.0918C20.5905 4.71547 20.2837 4.40973 19.9074 4.21799C19.4796 4 18.9203 4 17.8002 4H6.2002C5.08009 4 4.51962 4 4.0918 4.21799C3.71547 4.40973 3.40973 4.71547 3.21799 5.0918C3 5.51962 3 6.08009 3 7.2002V18.6712C3 19.7369 3 20.2696 3.21846 20.5433C3.40845 20.7813 3.69644 20.9198 4.00098 20.9195C4.35115 20.9191 4.76744 20.5861 5.59961 19.9203Z"
                                        stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                </g>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>

        </template>
    </modal>
</template>