<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import modal from "../Components/DialogModal.vue";
import moment from "moment";
import { useCreditRequest } from '../stores/creditRequest'
import imgLogo from '../../img/logo.png'


defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
const storeCreditRequest = useCreditRequest();
const amortizaciones = computed({
    get() {
        return storeCreditRequest.amortizaciones
    },
    set(val) {
        storeCreditRequest.setAmortizaciones(val)
    }
})
const valueRange = computed({
    get() {
        return storeCreditRequest.valueRange
    },
    set(val) {
        storeCreditRequest.setValueRange(val)
    }
})

const Fecha = ref(null);
const openModal = ref(false);
const ModelTablaCalculo = computed({
    get() {
        return storeCreditRequest.ModelTablaCalculo
    },
    set(val) {
        storeCreditRequest.setModelTablaCalculo(val)
    }
})
const CalculateMeses = computed({
    get() {
        ModelTablaCalculo.value.Meses = parseFloat(parseFloat((ModelTablaCalculo.value.periodos / ModelTablaCalculo.value.pagosMensuales)).toFixed(1))
        return parseFloat(parseFloat((ModelTablaCalculo.value.periodos / ModelTablaCalculo.value.pagosMensuales)).toFixed(1))
    },
    set(values) {
        ModelTablaCalculo.value.Meses = values
    }
})
const CalculatePagoTotal = computed({
    get() {
        ModelTablaCalculo.value.pagoTotal = parseFloat(parseFloat((ModelTablaCalculo.value.InteresMensual * ModelTablaCalculo.value.Meses)).toFixed(2))
        return parseFloat(parseFloat(ModelTablaCalculo.value.InteresMensual * ModelTablaCalculo.value.Meses).toFixed(2))
    },
    set(values) {
        ModelTablaCalculo.value.pagoTotal = values
    }
})
const CalculatePagoOla = computed({
    get() {
        ModelTablaCalculo.value.PagoOla = parseFloat(parseFloat(valueRange.value * (1 + CalculatePagoTotal.value / 100)).toFixed(2))
        return parseFloat(parseFloat(valueRange.value * (1 + CalculatePagoTotal.value / 100)).toFixed(2))
    },
    set(values) {
        ModelTablaCalculo.value.pagoTotal = values
    }
})
const CalculateCuota = computed({
    get() {
        ModelTablaCalculo.value.cuota = parseFloat(parseFloat(CalculatePagoOla.value / ModelTablaCalculo.value.periodos).toFixed(2))
        return parseFloat(parseFloat(CalculatePagoOla.value / ModelTablaCalculo.value.periodos).toFixed(2))
    },
    set(values) {
        ModelTablaCalculo.value.pagoTotal = values
    }
})
const formatCurrency = (value) => {
    const formatter = new Intl.NumberFormat("es-MX", {
        style: "currency",
        currency: "MXN",
    });
    return formatter.format(value);
}
const tablaPrestamo = ref([
    { cabecera: 'Monto Prestamo' },
    { cabecera: 'Tipo de pago' },
    { cabecera: 'Pagos mensuales' },
    { cabecera: 'Números de periodos' },
    { cabecera: 'Meses' },
    { cabecera: 'Interés mensual' },
    { cabecera: 'Pago Total' },
    { cabecera: 'Pago OLA' },
]);
const calcularTabla = (capitalInicial, interes, pago, tabla) => {
    // Si el capital es menor o igual que cero, detenemos la recursión
    if (capitalInicial <= 0) {
        return tabla;
    }

    // Calculamos el nuevo capital
    const nuevoCapital = parseFloat(parseFloat(capitalInicial + interes - pago).toFixed(2));

    // Agregamos los datos al array de objetos
    tabla.push({
        periodo: tabla.length + 1,
        capital: capitalInicial,
        interes: interes,
        pago: pago,
        dias_pago: Fecha.value.add(7, 'days').format('Do [de] MMMM [del] YYYY').replace(/\b(\d{1,2})(th|st|nd|rd)\b/g, '$1')
    });
    // Llamamos recursivamente la función con el nuevo capital
    return calcularTabla(nuevoCapital, interes, pago, tabla);
}
const openSimuladorDeCredito = async () => {
    Fecha.value = moment();
    var result = await calcularTabla(parseInt(valueRange.value), parseFloat(parseFloat(((ModelTablaCalculo.value.InteresMensual / ModelTablaCalculo.value.pagosMensuales) * valueRange.value) / 100).toFixed(2)), ModelTablaCalculo.value.cuota, []);
    amortizaciones.value.splice(0, amortizaciones.value.length);
    for (let index = 0; index < result.length; index++) {
        const element = result[index];
        storeCreditRequest.setAmortizaciones(element)
    }
    openModal.value = true
}
const quieroMimCredito = async () => {
    try {
        router.visit('/credit-request')
    } catch (error) {
        console.log(error)
    }
}

onMounted(async () => {
    Fecha.value = moment();
    var result = await calcularTabla(parseInt(valueRange.value), parseFloat(parseFloat(((ModelTablaCalculo.value.InteresMensual / ModelTablaCalculo.value.pagosMensuales) * valueRange.value) / 100).toFixed(2)), ModelTablaCalculo.value.cuota, []);
    amortizaciones.value.splice(0, amortizaciones.value.length);
    for (let index = 0; index < result.length; index++) {
        const element = result[index];
        storeCreditRequest.setAmortizaciones(element)
    }
})

</script>

<template>
    <Head title="Welcome" />
    <div
        class="relative sm:flex sm:justify-center sm:items-center  bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
            Dashboard</Link>

            <template v-else>
                <Link :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Log in</Link>
            </template>
        </div>
    </div>
    <div class="  ">
        <div class="flex justify-center -mb-10">
            <img :src="imgLogo" width="200" />
        </div>

        <div class="mt-10">
            <div class="grid grid-cols-1 w-full">
                <div
                    class="rounded-lg w-full h-auto gap-4  justify-around bg-neutral-100 p-6 text-neutral-700 shadow-lg dark:bg-neutral-600 dark:text-neutral-200 dark:shadow-black/30">
                    <h2 class=" text-3xl font-semibold ">
                        ¿Nuevo Cliente?
                    </h2>
                    <p class="text-sm font-semibold ml-4 mb-5">si eres nuevo cliente o no has solicitado un creditos con
                        nosotros podras configurar tu credito y solicitar uno</p>
                    <div class="gap-4 flex justify-around">
                        <div
                            class=" text-sm flex flex-col rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 md:max-w-xl md:flex-row">
                            <img class="h-96 w-full rounded-t-lg object-cover md:h-auto md:w-48 md:rounded-none md:rounded-l-lg"
                                src="https://www.portafolio.co/files/article_multimedia/uploads/2022/08/22/630423d442be3.jpeg"
                                alt="" />
                            <div class="flex flex-col justify-start p-6">
                                <div
                                    class="border-b-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                                    Información de credito
                                </div>
                                <h5 class="mb-2 text-xl font-medium text-neutral-800 dark:text-neutral-50">
                                    ¿Cuanto dinero necesitas?
                                </h5>
                                <div class="flex justify-end">
                                    <div class="relative mb-3 w-40">
                                        <div class="relative mb-4 flex flex-wrap ">
                                            <span
                                                class="flex items-center whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                                                id="basic-addon1">$</span>
                                            <input type="number" v-model="valueRange" min="3000"
                                                class="relative m-0 text-right block w-6 min-w-4 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary" />
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <input v-model="valueRange" min="3000" max="100000" type="range"
                                        class="transparent h-1.5 w-full cursor-pointer appearance-none rounded-lg border-transparent bg-neutral-200"
                                        id="customRange1" />
                                </div>
                                <div class="flex justify-between">
                                    <div>$3.000,00</div>
                                    <div>$100.000,00</div>
                                </div>

                                <div class="flex justify-between gap-1 my-2 ">
                                    <div>
                                        <label data-te-select-label-ref>Frecuencia de pagos</label>
                                        <select v-model="ModelTablaCalculo.tipoPago" class="w-full">
                                            <option value="Mensual">
                                                Mensual
                                            </option>
                                            <option value="quincenal">
                                                Quincenal
                                            </option>
                                            <option value="semanal">
                                                Semanal
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Cantidad de Cuotas</label>
                                        <select v-model="ModelTablaCalculo.pagosMensuales" data-te-select-init
                                            class="w-full">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex justify-between my-2 gap-2">
                                    <div class="block rounded-lg bg-white text-center dark:bg-neutral-700">
                                        <div
                                            class="border-b-2 border-neutral-100 px-6 dark:border-neutral-600 dark:text-neutral-50">
                                            Solicitado
                                        </div>
                                        <div class="p-2">
                                            <h5
                                                class="text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
                                                {{ formatCurrency(valueRange) }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="block rounded-lg bg-white text-center dark:bg-neutral-700">
                                        <div
                                            class="border-b-2 border-neutral-100 px-6 dark:border-neutral-600 dark:text-neutral-50">
                                            Valor de la cuota
                                        </div>
                                        <div class="p-2">
                                            <h5
                                                class="text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
                                                {{ formatCurrency(CalculateCuota) }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between my-2 gap-2">
                                    <button @click.prevent="openSimuladorDeCredito"
                                        class="shadow bg-[#66c3d1] hover:bg-[#2cb6cb] focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                        type="button">
                                        Simulador de credito
                                    </button>
                                    <button @click.prevent="quieroMimCredito"
                                        class="shadow bg-[#66c3d1] hover:bg-[#2cb6cb] focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                        type="button">
                                        Quiero mi credito
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="p-6 bg-white"> -->
                        <section class="antialiased ">
                            <div class="w-full max-w-2xl mx-auto bg-white shadow-lg rounded-sm border border-gray-200">
                                <div class="overflow-x-auto p-3">
                                    <table class="table-auto">
                                        <tbody>
                                            <tr>
                                                <th class="px-4 py-2 text-left">Monto Prestamo</th>
                                                <td class="border px-4 py-2"> {{ formatCurrency(valueRange) }}</td>
                                            </tr>
                                            <tr>
                                                <th class="px-4 py-2 text-left">Tipo de pago</th>
                                                <td class="border px-4 py-2">{{ ModelTablaCalculo.tipoPago }}</td>
                                            </tr>
                                            <tr>
                                                <th class="px-4 py-2 text-left">Pagos {{ ModelTablaCalculo.tipoPago }}
                                                </th>
                                                <td class="border px-4 py-2 ">
                                                    <p class="ml-3"> {{ ModelTablaCalculo.pagosMensuales }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="px-4 py-2 text-left">Números de periodos</th>
                                                <td class="border px-4 py-2">
                                                    <input min="0" v-model="ModelTablaCalculo.periodos" type="number"
                                                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary " />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="px-4 py-2 text-left capitalize">{{ ModelTablaCalculo.tipoPago
                                                }}</th>
                                                <td class="border px-4 py-2">
                                                    <p class="ml-3">{{ CalculateMeses }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="px-4 py-2 text-left">Interés mensual</th>
                                                <td class="border px-4 py-2">
                                                    <div class="relative flex flex-wrap items-stretch">
                                                        <input type="nunmber" v-model="ModelTablaCalculo.InteresMensual"
                                                            class="relative m-0 block w-[30px] min-w-0 flex-auto rounded-l border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary"
                                                            aria-label="Recipient's username" disabled
                                                            aria-describedby="basic-addon2" />
                                                        <span
                                                            class="flex items-center whitespace-nowrap rounded-r border border-l-0 border-solid border-neutral-300 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                                                            id="basic-addon2">%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="px-4 py-2 text-left">Pago Total</th>
                                                <td class="border px-4 py-2">
                                                    <div class="relative flex flex-wrap items-stretch">
                                                        <input type="nunmber" v-model="CalculatePagoTotal"
                                                            class="relative m-0 block w-[30px] min-w-0 flex-auto rounded-l border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary"
                                                            aria-label="Recipient's username" disabled
                                                            aria-describedby="basic-addon2" />
                                                        <span
                                                            class="flex items-center whitespace-nowrap rounded-r border border-l-0 border-solid border-neutral-300 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                                                            id="basic-addon2">%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="px-4 py-2 text-left">Pago OLA</th>
                                                <td class="border px-4 py-2">{{ formatCurrency(CalculatePagoOla) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="flex justify-end my-2 gap-2">
                                        <button @click.prevent="openSimuladorDeCredito"
                                            class="shadow  flex gap-4 justify-around bg-[#66c3d1] max-w-[60%] hover:bg-[#2cb6cb] focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                            type="button">
                                            Si quieres conseguir una mejor oferta o tasa de interes contacta un asesor
                                            <svg width="35px" height="35px" viewBox="0 0 24 24" class="self-center">
                                                <g id="Complete">
                                                    <g id="F-Chevron">
                                                        <polyline fill="none" id="Right" points="8.5 5 15.5 12 8.5 19"
                                                            stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" />
                                                    </g>
                                                </g>
                                            </svg>
                                        </button>
                                        <!-- <button @click.prevent="quieroMimCredito"
                                                class="shadow bg-[#66c3d1] hover:bg-[#2cb6cb] focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                                type="button">
                                                Quiero mi credito
                                            </button> -->
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- </div> -->
                    </div>
                </div>

            </div>
        </div>

        <div class="flex justify-center mt-16 px-6 sm:items-center sm:justify-between">
            <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-left">
                <div class="flex items-center gap-4">
                    <a href="https://github.com/sponsors/taylorotwell"
                        class="group inline-flex items-center hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            class="-mt-px mr-1 w-5 h-5 stroke-gray-400 dark:stroke-gray-600 group-hover:stroke-gray-600 dark:group-hover:stroke-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        Sponsor
                    </a>
                </div>
            </div>

            <div class="ml-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
            </div>
        </div>
    </div>

    <modal :show="openModal">
        <template #title>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="openModal = !openModal">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20">
                    <title>Close</title>
                    <path
                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
            </span>
        </template>

        <template #content>
            <!-- <section class="antialiased bg-gray-100 text-gray-600 h-screen px-4" x-data="app"> -->
            <div class="w-full max-w-2xl mx-auto bg-white shadow-lg rounded-sm border border-gray-200 my-2">
                <div class="overflow-x-auto">
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
                                    <div class="font-semibold text-left">
                                        Días de pago
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="text-sm divide-y divide-gray-100">
                            <!-- record 1 -->
                            <tr v-for="(pago, p) in amortizaciones" :key='p'>
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- </section> -->
        </template>
    </modal>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}</style>
