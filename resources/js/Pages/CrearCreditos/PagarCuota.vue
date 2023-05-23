<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, watch, computed } from 'vue';
import btnPrimay from '../../Components/PrimaryButton.vue'
const props = defineProps({
    credito_id: String,
});
const AmortizaiconesGenerales = ref([]);
const formatCurrency = (value) => {
    const formatter = new Intl.NumberFormat("es-MX", {
        style: "currency",
        currency: "MXN",
    });
    return formatter.format(value);
}
const getCreditosAmortizaicones = async () => {
    try {
        let { data } = await axios(route('get.credit.request'))
        AmortizaiconesGenerales.value = data.map(credito => {
            return credito.has_many_amortizaciones
        }).reduce((acumulador, elemento) => {
            return acumulador.concat(elemento);
        }, []);
        
    } catch (e) {
        console.log(e)
    }
}
onMounted(() => {
    getCreditosAmortizaicones()
})
</script>

<template>
    <AppLayout title="Crear Creditos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between ">
                Obligaciones
            </h2>
        </template>
        <div class="w-full mr-4  bg-white mt-2 border border-gray-200">
            <header class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Obligaciones  </h2>
            </header>
            <div class="p-3">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full ">
                        <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                            <tr>
                                <th class="p-2">
                                    <div class="font-semibold text-left">
                                        Identificador del credito
                                    </div>
                                </th>
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
                                    <div class="font-semibold text-right">
                                        Score
                                    </div>
                                </th>
                                <th class="p-2">
                                    <div class="font-semibold text-right">
                                        Comentario
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="text-sm divide-y divide-gray-100">
                            <tr v-for="(pago, p) in AmortizaiconesGenerales" :key='p'>
                                <td class="p-2">
                                    <div class="font-medium text-gray-800">
                                        {{ pago.uui_credit_request }}
                                    </div>
                                </td>
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
                                    <div class="text-right font-medium ">
                                        {{ pago.dias_pago }}
                                    </div>
                                </td>
                                <td class="p-2">
                                    <div class="text-right font-medium ">
                                        {{ pago.has_one_score ? pago.has_one_score.score : '' }}
                                    </div>
                                </td>
                                <td class="p-2">
                                    <div class="text-right font-medium ">
                                        {{ pago.has_one_score ? pago.has_one_score.comentario : '' }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>