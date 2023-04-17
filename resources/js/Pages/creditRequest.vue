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
const Fecha = moment();
const valueRange = ref(3000);
const openModal = ref(false);
const ModelTablaCalculo = ref({
    tipoPago: 'Mensual',
    pagosMensuales: parseInt(4),
    periodos: parseInt(16),
    Meses: 4,
    InteresMensual: parseFloat(parseFloat(5.81).toFixed(2)),
    pagoTotal: 0,
    PagoOla: 0,
    cuota: 0
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
const formattedValue = computed({
    get() {
        tablaPrestamo.value[0].valuec = formatCurrency(valueRange.value)
        return formatCurrency(valueRange.value)
    },
    set(value) {
        // Eliminar los caracteres no numéricos del valor
        const newValue = value.replace(/[^\d]/g, '')
        // Actualizar el valor numérico
        valueRange.value = Number(newValue)
    }
})
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
const tablaSimulador = ref([]);
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

                <!-- <Link v-if="canRegister" :href="route('register')"
                                                                class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                            Register</Link> -->
            </template>
        </div>

        <div class="w-11/12 mx-auto p-2 lg:p-8">
            <div
                class="flex flex-col rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 w-full">
                
                <div class="flex flex-col justify-start p-6">
                    
                    
                </div>
            </div>
        </div>
    </div>
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
