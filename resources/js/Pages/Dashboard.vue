<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed, onBeforeMount } from 'vue';
import modal from "../Components/DialogModal.vue";
import btnPrimay from '../Components/PrimaryButton.vue'

const props = defineProps({
    isCliente: Boolean,
    user_id: Number,
});
const estadoCupoDisponible = ref([
    { text: 'Estado de evaluacion', value: 0, color: 0 }, // 0: gris  1: verde 
    { text: 'Credito estado de propuesta', value: 1, color: 0 }, // 0: gris  1: verde 
    { text: 'Credito en carta de aceptación', value: 2, color: 1 }, // 0: gris  1: verde 
    { text: 'Credito en contrato', value: 4, color: 0 }, // 0: gris  1: verde 
    { text: 'Credito liberao', value: 5, color: 1 }, // 0: gris  1: verde 
    { text: 'Credito Rebotó', value: 6, color: 0 }, // 0: gris  1: verde 
    { text: 'Credito Pagado', value: 7, color: 0 }, // 0: gris  1: verde 
])
const openHistorialUser = ref(false);
const openRecaudosCreditRequestUser = ref(false);
const openReciboPagoCreditRequestUser = ref(false);
const dataCreditRequest = ref([]);
const amortizacionesCreditRequest = ref([]);
const RecentCreditRequest = ref(null);
const reciboDePAgoUser = ref(null);
const recaudo = ref(ref);
const getHistorialUser = async () => {
    try {
        let { data } = await axios(`/api/get-historial-user/${props.user_id}`)
        dataCreditRequest.value = data
        openHistorialUser.value = true
    } catch (error) {
        console.log(error)
    }
}
const SaldoDisponibleDespuesDePagarCreditoActivo = computed({
    get() {
        var disponible = 0;
        amortizacionesCreditRequest.value.map((recaudo, key) => {
            if (recaudo.status == 1) {
                disponible += recaudo.pago
                SaldoEnCreditosActivos.value = ((recaudo.capital + recaudo.interes) - recaudo.pago)
            }

        });
        return disponible
    },
    set() { },
})
const SaldoEnCreditosActivos = ref(0)

const getRecentCreditRequestUser = async () => {
    try {
        let { data } = await axios(`/api/get-recent-credit-request/${props.user_id}`)
        RecentCreditRequest.value = data
        SaldoEnCreditosActivos.value =  data.monto_de_dinero_solicitado
        amortizacionesCreditRequest.value = data.has_many_amortizaciones
    } catch (error) {
        console.log(error)
    }
}

const AddReciboDePagoARecaudo = async (data = null) => {
    try {
        recaudo.value = data
        openReciboPagoCreditRequestUser.value = true
    } catch (error) {
        console.log(error)
    }
}

const EnviarReciboDePagoARecaudo = async () => {
    try {
        let formData = new FormData();
        formData.append('recibo_de_pago_user', reciboDePAgoUser.value)
        formData.append('uui_credit_request', recaudo.value.uui_credit_request)
        formData.append('id_recaudo', recaudo.value.id)

        await axios.post('/api/add-recibo-de-pago-user', formData, { headers: { "Content-Type": "multipart/form-data" } })
        openReciboPagoCreditRequestUser.value = false

    } catch (error) {
        console.log(error)
    }
}
const handleFileReciboDePagoUser = (e) => {
    reciboDePAgoUser.value = e.target.files[0]
}
const formatCurrency = (value) => {
    const formatter = new Intl.NumberFormat("es-MX", {
        style: "currency",
        currency: "MXN",
    });
    return formatter.format(value);
}

onBeforeMount(() => {
    getRecentCreditRequestUser()
})
</script>

<template>
    <AppLayout title="Dashboard" :isCliente="isCliente">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl  mx-auto sm:px-6 lg:px-8">
                <div class="bg-white flex flex-row overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- TU CUPO TOTAL DISPONIBLE -->
                    <div class="p-4 xl:w-1/4 md:w-11/6 w-full basis-1/2">
                        <div
                            class="h-full p-6 rounded-lg border-2 border-indigo-500 flex flex-col relative overflow-hidden">
                            <span
                                class="bg-indigo-500 text-white px-3 py-1 tracking-widest text-xs absolute right-0 top-0 rounded-bl">SALDO
                                EN CRÉDITOS ACTIVOS</span>
                            <!-- <h2 class="text-sm tracking-widest title-font mb-1 font-medium">PRO</h2> -->
                            <h1
                                class="text-5xl text-gray-900 leading-none flex items-center pb-4 mb-4 border-b border-gray-200">

                                <span>{{ formatCurrency(SaldoEnCreditosActivos) }}</span>
                            </h1>
                            <p class="flex items-center text-gray-600 mb-2" v-for="(estado, n) in estadoCupoDisponible"
                                :key="n">
                                <span
                                    :class="{ 'w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0': !estado.color, 'w-4 h-4 mr-2 inline-flex items-center justify-center bg-green-400 text-white rounded-full flex-shrink-0': estado.color }">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2.5" class="w-3 h-3" viewBox="0 0 24 24">
                                        <path d="M20 6L9 17l-5-5"></path>
                                    </svg>
                                </span>{{ estado.text }}
                            </p>
                            <div class="flex gap-2 my-2">
                                <button @click="openRecaudosCreditRequestUser = true"
                                    class="flex items-center mt-auto text-white bg-indigo-500 border-0 py-2 px-4 w-full focus:outline-none hover:bg-indigo-600 rounded">
                                    Recaudos
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" class="w-4 h-4 ml-auto" viewBox="0 0 24 24">
                                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <button @click="getHistorialUser"
                                    class="flex items-center  mt-auto text-white bg-indigo-500 border-0 py-2 px-4 w-full focus:outline-none hover:bg-indigo-600 rounded">
                                    Historial de Creditos
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" class="w-4 h-4 ml-auto" viewBox="0 0 24 24">
                                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                                    </svg>
                                </button>

                            </div>
                            <p class="text-xs text-gray-500 mt-3">credito: {{ RecentCreditRequest ? RecentCreditRequest.uui
                                : 0 }}</p>
                        </div>
                    </div>
                    <!-- SALDO EN CRÉDITOS ACTIVOS -->
                    <div class="p-4 xl:w-1/4 md:w-11/6 w-full basis-1/2">
                        <div
                            class="h-full p-6 rounded-lg border-2 border-indigo-500 flex flex-col relative overflow-hidden">
                            <span
                                class="bg-indigo-500 text-white px-3 py-1 tracking-widest text-xs absolute right-0 top-0 rounded-bl">
                                TU CUPO TOTAL DISPONIBLE</span>
                            <!-- <h2 class="text-sm tracking-widest title-font mb-1 font-medium">PRO</h2> -->
                            <h1
                                class="text-5xl text-gray-900 leading-none flex items-center pb-4 mb-4 border-b border-gray-200">
                                <span>{{ formatCurrency(SaldoDisponibleDespuesDePagarCreditoActivo) }}</span>
                            </h1>

                            <div class="flex gap-2 my-2 mt-[14.5rem]">
                                <button
                                    class="flex items-center mt-auto text-white bg-indigo-500 border-0 py-2 px-4 w-full focus:outline-none hover:bg-indigo-600 rounded">
                                    Solicitar Aumento
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" class="w-4 h-4 ml-auto" viewBox="0 0 24 24">
                                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center mt-auto text-white bg-indigo-500 border-0 py-2 px-4 w-full focus:outline-none hover:bg-indigo-600 rounded">
                                    Transferir Cupo
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" class="w-4 h-4 ml-auto" viewBox="0 0 24 24">
                                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal :show="openHistorialUser" maxWidth="2x1">
            <template #title>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="openHistorialUser = !openHistorialUser">
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
                    <div class="w-full">
                        <div class="bg-white shadow-md rounded my-6">
                            <table class="min-w-max w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                        <th class="py-3 px-6 text-left">Identificador</th>
                                        <th class="py-3 px-6 text-center">Usuario</th>
                                        <th class="py-3 px-6 text-center">Monto Solicitado</th>
                                        <th class="py-3 px-6 text-center">Status</th>
                                        <th class="py-3 px-6 text-center">Motivo</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                    <tr class="border-b border-gray-200 hover:bg-gray-100"
                                        v-for="(solicitud, s) in dataCreditRequest" :key="s">
                                        <td class="py-3 px-6 text-left whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="font-medium text-bold">{{ solicitud.uui }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            <div class="flex items-center">
                                                <div class="mr-2">
                                                    <img class="w-6 h-6 rounded-full"
                                                        :src="solicitud.belong_to_user.profile_photo_url" />
                                                </div>
                                                <span>{{ solicitud.belong_to_user.name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <span>{{ formatCurrency(solicitud.monto_de_dinero_solicitado) }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <span
                                                :class="{ 'bg-yellow-200 text-black py-1 px-3 rounded-full text-xs': solicitud.status == 0, 'bg-orange-400 text-black py-1 px-3 rounded-full text-xs': solicitud.status == 1, 'bg-red-400 text-white py-1 px-3 rounded-full text-xs': solicitud.status == 2 }">
                                                {{ solicitud.status == 0 ? 'Credito pendiente por revisar' :
                                                    solicitud.status == 1 ? 'Credito aprobado para estudio' : solicitud.status
                                                        == 2 ? 'Credito Rechazado' : '' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end">
                    <!-- <btnPrimay @click.prevent="enviarCreditoAEstudio(formulario)">Enviar a estudio</btnPrimay> -->
                </div>
            </template>
        </modal>
        <modal :show="openRecaudosCreditRequestUser" maxWidth="2x1">
            <template #title>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                    @click="openRecaudosCreditRequestUser = !openRecaudosCreditRequestUser">
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
                    <div class="w-full">
                        <div class="bg-white shadow-md rounded my-6">
                            <table class="table-auto w-full ">
                                <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                    <tr>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">
                                                Periodo
                                            </div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">
                                                Capital
                                            </div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">
                                                Interés
                                            </div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">
                                                Pago
                                            </div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-right">
                                                Días de pago
                                            </div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-center">
                                                Aciones
                                            </div>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="text-sm divide-y divide-gray-100">
                                    <!-- record 1 -->
                                    <tr v-for="(pago, p) in amortizacionesCreditRequest" :key='p'>
                                        <td class="p-2">
                                            <div class="font-medium text-gray-800">
                                                {{ pago.periodo }}
                                            </div>
                                        </td>
                                        <td class="p-2">
                                            <div class="text-left">
                                                {{ formatCurrency(pago.capital) }}
                                            </div>
                                        </td>
                                        <td class="p-2">
                                            <div class="text-left font-medium text-green-500">
                                                {{ formatCurrency(pago.interes) }}
                                            </div>
                                        </td>
                                        <td class="p-2">
                                            <div class="text-left font-medium text-green-500">
                                                {{ formatCurrency(pago.pago) }}
                                            </div>
                                        </td>
                                        <td class="p-2">
                                            <div class="text-right font-medium text-green-500">
                                                {{ pago.dias_pago }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center">
                                                <div class="w-4 mr-2 transform  hover:scale-110"
                                                    @click="AddReciboDePagoARecaudo(pago)">
                                                    <svg width="15px" height="15px" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none">
                                                        <path fill="#000000" fill-rule="evenodd"
                                                            d="M9 12a1 1 0 102 0V4.26l3.827 3.48a1 1 0 001.346-1.48l-5.5-5a1 1 0 00-1.346 0l-5.5 5a1 1 0 101.346 1.48L9 4.26V12zm-5.895-.796A1 1 0 001.5 12v3.867a2.018 2.018 0 002.227 2.002c1.424-.147 3.96-.369 6.273-.369 2.386 0 5.248.236 6.795.383a2.013 2.013 0 002.205-2V12a1 1 0 10-2 0v3.884l-13.895-4.68zm0 0L2.5 11l.605.204zm0 0l13.892 4.683a.019.019 0 01-.007.005h-.006c-1.558-.148-4.499-.392-6.984-.392-2.416 0-5.034.23-6.478.38h-.009a.026.026 0 01-.013-.011V12a.998.998 0 00-.394-.796z" />
                                                    </svg>
                                                </div>
                                                <div class="w-4 mr-2 transform  hover:scale-110">
                                                    <svg width="15px" height="15px" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <title />
                                                        <g id="Complete">
                                                            <g id="download">
                                                                <g>
                                                                    <path d="M3,12.3v7a2,2,0,0,0,2,2H19a2,2,0,0,0,2-2v-7"
                                                                        fill="none" stroke="#000000" stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2" />
                                                                    <g>
                                                                        <polyline data-name="Right" fill="none" id="Right-2"
                                                                            points="7.9 12.3 12 16.3 16.1 12.3"
                                                                            stroke="#000000" stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2" />

                                                                        <line fill="none" stroke="#000000"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2" x1="12" x2="12" y1="2.7"
                                                                            y2="14.2" />
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div class="w-4 mr-2 transform  hover:scale-110">
                                                    <p class="flex items-center text-gray-600 mb-2">
                                                        <span :class="{
                                                            'w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0': pago.status == null,
                                                            'w-4 h-4 mr-2 inline-flex items-center justify-center bg-yellow-400 text-black rounded-full flex-shrink-0': pago.status == 0,
                                                            'w-4 h-4 mr-2 inline-flex items-center justify-center bg-red-400 text-white rounded-full flex-shrink-0': pago.status == 1,
                                                            'w-4 h-4 mr-2 inline-flex items-center justify-center bg-green-400 text-white rounded-full flex-shrink-0': pago.status == 2
                                                        }">
                                                            <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2.5" class="w-3 h-3"
                                                                viewBox="0 0 24 24">
                                                                <path d="M20 6L9 17l-5-5"></path>
                                                            </svg>
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end">
                    <!-- <btnPrimay @click.prevent="enviarCreditoAEstudio(formulario)">Enviar a estudio</btnPrimay> -->
                </div>
            </template>
        </modal>
        <modal :show="openReciboPagoCreditRequestUser" maxWidth="lg">
            <template #title>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                    @click="openReciboPagoCreditRequestUser = !openReciboPagoCreditRequestUser">
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
                    <div class="w-full">
                        <div class="bg-white shadow-md rounded my-6">
                            <div class="mb-3">
                                <label for="reciboDePago"
                                    class="mb-2 inline-block text-neutral-700 dark:text-neutral-200">Recibo de pago</label>
                                <input @change="handleFileReciboDePagoUser"
                                    class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary"
                                    type="file" id="reciboDePago" />
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end">
                    <btnPrimay @click.prevent="EnviarReciboDePagoARecaudo">Enviar recibo de pago</btnPrimay>
                </div>
            </template>
        </modal>
    </AppLayout>
</template>
