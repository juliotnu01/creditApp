<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import modal from "../Components/DialogModal.vue";
import moment from "moment";
import { useCreditRequest } from '../stores/creditRequest'


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

const Fecha = moment();
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
        ModelTablaCalculo.value.Meses = parseInt((ModelTablaCalculo.value.periodos / ModelTablaCalculo.value.pagosMensuales))
        return parseInt((ModelTablaCalculo.value.periodos / ModelTablaCalculo.value.pagosMensuales))
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
        dias_pago: Fecha.add(7, 'days').format('Do [de] MMMM [del] YYYY').replace(/\b(\d{1,2})(th|st|nd|rd)\b/g, '$1')
    });
    // Llamamos recursivamente la función con el nuevo capital
    return calcularTabla(nuevoCapital, interes, pago, tabla);
}
const openSimuladorDeCredito = async () => {

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
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
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

        <div class="max-w-7x2 mx-auto p-2 lg:p-8">
            <div class="flex justify-center">
                <svg viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="h-16 w-auto bg-gray-100 dark:bg-gray-900">
                    <path
                        d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.70891 0.105946 7.65531 0.133367 7.6067C0.152063 7.5743 0.179485 7.54812 0.20192 7.51821C0.230588 7.47832 0.256763 7.43719 0.290416 7.40229C0.319084 7.37362 0.356476 7.35243 0.388883 7.32751C0.425029 7.29759 0.457436 7.26518 0.498568 7.2415L12.4779 0.345059C12.6296 0.257786 12.8015 0.211853 12.9765 0.211853C13.1515 0.211853 13.3234 0.257786 13.475 0.345059L25.4531 7.2415H25.4556C25.4955 7.26643 25.5292 7.29759 25.5653 7.32626C25.5977 7.35119 25.6339 7.37362 25.6625 7.40104C25.6974 7.43719 25.7224 7.47832 25.7523 7.51821C25.7735 7.54812 25.8021 7.5743 25.8196 7.6067C25.8483 7.65656 25.8645 7.70891 25.8844 7.76126C25.8944 7.78993 25.9118 7.8161 25.9193 7.84602C25.9423 7.93096 25.954 8.01853 25.9542 8.10652V33.7317L35.9355 27.9844V14.8846C35.9355 14.7973 35.948 14.7088 35.9704 14.6253C35.9792 14.5954 35.9954 14.5692 36.0053 14.5405C36.0253 14.4882 36.0427 14.4346 36.0702 14.386C36.0888 14.3536 36.1163 14.3274 36.1375 14.2975C36.1674 14.2576 36.1923 14.2165 36.2272 14.1816C36.2559 14.1529 36.292 14.1317 36.3244 14.1068C36.3618 14.0769 36.3942 14.0445 36.4341 14.0208L48.4147 7.12434C48.5663 7.03694 48.7383 6.99094 48.9133 6.99094C49.0883 6.99094 49.2602 7.03694 49.4118 7.12434L61.3899 14.0208C61.4323 14.0457 61.4647 14.0769 61.5021 14.1055C61.5333 14.1305 61.5694 14.1529 61.5981 14.1803C61.633 14.2165 61.6579 14.2576 61.6878 14.2975C61.7103 14.3274 61.7377 14.3536 61.7551 14.386C61.7838 14.4346 61.8 14.4882 61.8199 14.5405C61.8312 14.5692 61.8474 14.5954 61.8548 14.6253ZM59.893 27.9844V16.6121L55.7013 19.0252L49.9104 22.3593V33.7317L59.8942 27.9844H59.893ZM47.9149 48.5566V37.1768L42.2187 40.4299L25.953 49.7133V61.2003L47.9149 48.5566ZM1.99677 9.83281V48.5566L23.9562 61.199V49.7145L12.4841 43.2219L12.4804 43.2194L12.4754 43.2169C12.4368 43.1945 12.4044 43.1621 12.3682 43.1347C12.3371 43.1097 12.3009 43.0898 12.2735 43.0624L12.271 43.0586C12.2386 43.0275 12.2162 42.9888 12.1887 42.9539C12.1638 42.9203 12.1339 42.8916 12.114 42.8567L12.1127 42.853C12.0903 42.8156 12.0766 42.7707 12.0604 42.7283C12.0442 42.6909 12.023 42.656 12.013 42.6161C12.0005 42.5688 11.998 42.5177 11.9931 42.4691C11.9881 42.4317 11.9781 42.3943 11.9781 42.3569V15.5801L6.18848 12.2446L1.99677 9.83281ZM12.9777 2.36177L2.99764 8.10652L12.9752 13.8513L22.9541 8.10527L12.9752 2.36177H12.9777ZM18.1678 38.2138L23.9574 34.8809V9.83281L19.7657 12.2459L13.9749 15.5801V40.6281L18.1678 38.2138ZM48.9133 9.14105L38.9344 14.8858L48.9133 20.6305L58.8909 14.8846L48.9133 9.14105ZM47.9149 22.3593L42.124 19.0252L37.9323 16.6121V27.9844L43.7219 31.3174L47.9149 33.7317V22.3593ZM24.9533 47.987L39.59 39.631L46.9065 35.4555L36.9352 29.7145L25.4544 36.3242L14.9907 42.3482L24.9533 47.987Z"
                        fill="#FF2D20" />
                </svg>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    <div
                        class="flex flex-col rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 md:max-w-xl md:flex-row">
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
                                    <!-- <div class="inline-flex">
                                        {{ formattedValue }}
                                    </div> -->
                                    <div class="relative mb-4 flex flex-wrap ">
                                        <span
                                            class="flex items-center whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                                            id="basic-addon1">$</span>
                                        <input type="number" v-model="valueRange" min="3000"
                                            class="relative m-0 text-right block w-6 min-w-4 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary"
                                            placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" />
                                    </div>
                                </div>
                            </div>
                            <div>
                                <input v-model="valueRange" min="3000" max="10000" type="range"
                                    class="transparent h-1.5 w-full cursor-pointer appearance-none rounded-lg border-transparent bg-neutral-200"
                                    id="customRange1" />
                            </div>
                            <div class="flex justify-between">
                                <div>$3.000</div>
                                <div>$10.000</div>
                            </div>

                            <div class="flex justify-between gap-1 my-2">
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
                                    <select v-model="ModelTablaCalculo.pagosMensuales" data-te-select-init class="w-full">
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
                                        <h5 class="text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
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
                                        <h5 class="text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
                                            {{ formatCurrency(CalculateCuota) }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between my-2 gap-2">
                                <button @click.prevent="openSimuladorDeCredito"
                                    class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                    type="button">
                                    Simulador de credito
                                </button>
                                <button @click.prevent="quieroMimCredito"
                                    class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                    type="button">
                                    Quiero mi credito
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 bg-white">
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
                                                <th class="px-4 py-2 text-left">Pagos {{ ModelTablaCalculo.tipoPago }}</th>
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
                                                <th class="px-4 py-2 text-left">Meses</th>
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

                                    </div>
                                </div>
                            </div>
                        </section>
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
    </div>

    <modal :show="openModal" closeable>
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
}
</style>
