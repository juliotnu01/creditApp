<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, watch, computed, reactive } from 'vue';
import btnPrimay from '../../Components/PrimaryButton.vue'
import moment from "moment";
import modal from "../../Components/DialogModal.vue";

const props = defineProps({
    credito_id: String,
});
const AmortizaiconesGenerales = ref([]);
const openModal = ref(false);
const modelPago = reactive({datosDePagos: null, pagos:[], });
const pagoCuota = ref(0)
const solicitudesDecreditos = ref(null);
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
        solicitudesDecreditos.value = data
        AmortizaiconesGenerales.value = data.map(credito => {
            for (const iterator of credito.has_many_amortizaciones) {
                iterator.restante = 0;
                iterator.pagoCuota = 0;
                iterator.scoreNewPago = 0;
                iterator.comentarioNewPago = '';
                iterator.fileComprobante = null;
                iterator.fechaComprobante = moment().format('YYYY-MM-DD');
            }
            return credito.has_many_amortizaciones
        }).reduce((acumulador, elemento) => {
            return acumulador.concat(elemento);
        }, []);

    } catch (e) {
        console.log(e)
    }
}

const calculateMora = (pago = null) => {

    if(pago.dias_pago_total){
        return pago.dias_pago_total
    }
    var fechaEspecifica = moment(pago.dias_pago);
    // Obtén la fecha actual
    var fechaActual = moment();
    // Calcula la diferencia en días
    var diferenciaEnDias = fechaEspecifica.diff(fechaActual, 'days');

    return diferenciaEnDias
}
const openModalParaPagar = (obligacion = null) => {
    modelPago.pagos.splice(0)
    obligacion.pagoCuota = 0
    modelPago.datosDePagos =  obligacion
    modelPago.pagos.push(obligacion)
    openModal.value = true
}
const handleFileComprobante = (e, pago) => {
    pago.fileComprobante = e.target.files[0]
}

const RegistrarPago = async () => {
    try {
        for (let index = 0; index < modelPago.pagos.length; index++) {
            const element = modelPago.pagos[index];
            let formData = new FormData();
            formData.append('fileComprobante', element.fileComprobante)
            formData.append('data', JSON.stringify(element))

            await axios.post(route('add.pago.comprobante.user'), formData,{ headers: { 'Content-Type': 'multipart/form-data' } })
            
        }
        openModal.value = false
        getCreditosAmortizaicones()
    } catch (error) {
        console.log(error)
    }
}

watch(() => modelPago.pagos, (newValue, oldValue) => {
    newValue[newValue.length -1].restante = parseFloat( parseFloat(newValue[newValue.length -1].pago - newValue[newValue.length -1].pagoCuota).toFixed(2) )
    const ultimorestante = newValue[newValue.length -1].restante 
    
    if(ultimorestante < 0 ){
        let valorAbsoluto = Math.abs(ultimorestante);
        const credito = solicitudesDecreditos.value.find(credito => credito.uui == modelPago.datosDePagos.uui_credit_request)
        const siguienteAmortizacion = credito.has_many_amortizaciones.find(amortizacion => amortizacion.periodo == newValue[newValue.length -1].periodo + 1 )
        if(siguienteAmortizacion){
            siguienteAmortizacion.pagoCuota = valorAbsoluto
            modelPago.pagos.push(siguienteAmortizacion)
        }
    }else{
        newValue.splice(1)
    }
    console.log(modelPago.pagos)
}, { deep: true }) 
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
                <h2 class="font-semibold text-gray-800">Obligaciones </h2>
            </header>
            <div class="p-3">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                            <tr>
                                <th class="p-2">
                                    <div class="font-semibold text-left">
                                        Identificador del credito
                                    </div>
                                </th>
                                <th class="p-2">
                                    <div class="font-semibold text-center">
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
                                    <div class="font-semibold text-center">
                                        Días de pago
                                    </div>
                                </th>
                                <th class="p-2">
                                    <div class="font-semibold text-center">
                                        Días de mora
                                    </div>
                                </th>
                                <th class="p-2">
                                    <div class="font-semibold text-right">
                                        Score
                                    </div>
                                </th>
                                <th class="p-2">
                                    <div class="font-semibold text-center">
                                        Status
                                    </div>
                                </th>
                                <th class="p-2">
                                    <div class="font-semibold text-right">
                                        Comentario
                                    </div>
                                </th>
                                <th class="p-2">
                                    <div class="font-semibold text-right">
                                        Accion
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
                                    <div class="font-medium text-gray-800 text-center">
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
                                    <div class="text-center font-medium whitespace-no-wrap">
                                        {{ pago.dias_pago }}
                                    </div>
                                </td>
                                <td class="p-2">
                                    <div class="text-center font-medium ">
                                        <span
                                            :class="{
                                                'bg-green-200 text-black py-1 px-3 rounded-full  ': calculateMora(pago) >= 0,
                                                'bg-red-200 text-black py-1 px-3 rounded-full  ': calculateMora(pago) < 0
                                            }">
                                            {{ calculateMora(pago) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-2">
                                    <div class="text-right font-medium ">
                                        {{ pago.has_one_score ? pago.has_one_score.score : '' }}
                                    </div>
                                </td>
                                <td class="p-2">
                                    <!-- <div class="text-right font-medium ">
                                        {{ pago.status}}
                                    </div> -->
                                    <div class="text-left font-medium text-green-500 text-[10px]">
                                        <span
                                            :class="{ 'bg-yellow-200 text-black py-1 px-3 rounded-full whitespace-no-wrap ': pago.status == 0, 'bg-orange-400 text-black py-1 px-3 rounded-full whitespace-no-wrap ': pago.status == 1, 'bg-red-400 text-white py-1 px-3 rounded-full whitespace-no-wrap ': pago.status == 2 }">
                                            {{ pago.status == 0 ? 'No Pagado' : pago.status == 1 ?
                                                'En Revisión' : pago.status == 2 ? 'Pagado' : ''
                                            }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-2">
                                    <div class="text-right font-medium ">
                                        {{ pago.has_one_score ? pago.has_one_score.comentario : '' }}
                                    </div>
                                </td>
                                <td class="p-2 text-center flex justify-center gap-3">
                                    
                                        <svg  @click.prevent="openModalParaPagar(pago)" fill="currentColor" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 hover:text-green-500 hover:cursor-pointer hover:shadow-md">                                            <g><g><rect x="333.913" y="100.174" width="77.913" height="33.391" /></g></g>
                                            <g><g><rect x="333.913" y="211.478" width="77.913" height="33.391" /></g></g>
                                            <g><g><rect x="411.826" y="133.565" width="33.391" height="77.913" /></g></g>
                                            <g><g><rect x="300.522" y="133.565" width="33.391" height="77.913" /></g></g>
                                            <g><g><rect x="155.826" width="66.783" height="33.391" /></g></g>
                                            <g><g><rect x="155.826" y="100.174" width="66.783" height="33.391" /></g></g>
                                            <g><g><rect x="222.609" y="33.391" width="33.391" height="66.783" /></g></g>
                                            <g><g><rect x="122.435" y="33.391" width="33.391" height="66.783" /></g></g>
                                            <g><g><rect y="278.261" width="100.174" height="233.739" /></g></g>
                                            <g><g><polygon points="389.565,311.652 389.565,345.043 345.043,345.043 345.043,378.435 211.478,378.435 211.478,345.043 
			345.043,345.043 345.043,278.261 133.565,278.261 133.565,478.609 166.957,478.609 166.957,512 345.043,512 345.043,478.609 
			411.826,478.609 411.826,445.217 445.217,445.217 445.217,411.826 478.609,411.826 478.609,378.435 512,378.435 512,311.652" /></g></g>
                                        </svg>

                                        <svg fill="currentColor" viewBox="0 0 1000 1000" class="w-5 h-5 hover:text-orange-500 hover:cursor-pointer hover:shadow-md">
                                            <path d="M984 612L789 414q-4-4-8.5-2t-4.5 7v95q-90 15-163 53-82 42-142.5 111T384 829q-13 40-16 82-1 23 0 46 1 4 5.5 4.5t5.5-3.5q16-58 52-106 43-59 106-95 60-34 128-43 56-8 111 0v103q0 4 4.5 6t8.5-1l195-199q2-2 2-5t-2-6zM70 252v447q0 14 9.5 23.5T103 732h246q7 0 12-4.5t5-11.5v-22q0-7-5-12t-12-5H125V296h568v87q0 7 4.5 12t11.5 5h21q7 0 12-5t5-12V252H70zm677-32v-55q0-13-9.5-23T714 132H613v-13q0-12-6-23t-16.5-17-22.5-6-22.5 6T529 96t-6 23v13H293v-13q0-19-13-32t-31.5-13T217 87t-13 32v13H103q-14 0-23.5 10T70 165v55h677z"/>
                                        </svg>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <modal :show="openModal" maxWidth=" " >
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
                <div class="w-full  mx-auto bg-white shadow-lg rounded-sm border border-gray-200 my-2">
                    <div class="overflow-x-auto p-2">
                        <!-- <pre>{{modelPago}}</pre> -->
                        <div class="flex gap-2 justify-between w-full mb-4">
                            <div class="flex flex-col" >
                                <span class=" text-lg  " >capital:  {{formatCurrency(modelPago.datosDePagos.capital)}} </span>
                                <span class=" text-lg  " >Interes:  {{formatCurrency(modelPago.datosDePagos.interes)}} </span>
                                <span class=" text-lg  " >Cuota:  {{formatCurrency(modelPago.datosDePagos.pago)}} </span>
                            </div>
                            <div class="flex flex-col" >
                                <span class="tex-[10px]" >Credito: {{modelPago.datosDePagos.uui_credit_request}} </span> 
                                <span class=" text-lg  " >Dia de pago:  {{modelPago.datosDePagos.dias_pago}} </span>
                                <span class=" text-lg  " >fecha comprobante:  <input v-model="modelPago.datosDePagos.fechaComprobante" class=" h-2 mb-2  px-4 py-2 border rounded" type="date"></span>
                                
                                <span :class="{
                                            'bg-green-200 text-black py-1 px-3 rounded-full ': calculateMora(modelPago.datosDePagos.dias_pago) >= 0,
                                            'bg-red-200 text-black py-1 px-3 rounded-full ': calculateMora(modelPago.datosDePagos.dias_pago) < 0
                                        }">
                                        Dia de mora:   {{ calculateMora(modelPago.datosDePagos.dias_pago) }}
                                </span>
                            </div>
                        </div>
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
                                            Pago
                                        </div>
                                    </th>
                                    <th class="p-2">
                                        <div class="font-semibold text-left">
                                            Restante
                                        </div>
                                    </th>
                                    <th class="p-2">
                                        <div class="font-semibold text-left">
                                            comprobante
                                        </div>
                                    </th>
                                    <th class="p-2">
                                        <div class="font-semibold text-left">
                                            Score
                                        </div>
                                    </th>
                                    <th class="p-2">
                                        <div class="font-semibold text-left">
                                            Status
                                        </div>
                                    </th>
                                    <th class="p-2">
                                        <div class="font-semibold text-left">
                                            Comentario
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">
                                <tr  v-for="(pago, p) in modelPago.pagos" :key="p" >
                                    <td class="p-2">
                                        <div class="font-medium text-gray-800">
                                            {{ pago.periodo }}
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <div class="font-medium text-gray-800">
                                            <input type="number" v-model="pago.pagoCuota" 
                                                class="relative m-0 w-full text-right block  min-w-4 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none" />
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-left">
                                            {{ pago.restante < 0 ? 0 : pago.restante}}
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-left flex justify-start font-medium text-green-500 " >
                                            <input type="file" ref="fileInput" @change="handleFileComprobante($event, pago)" />
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-left font-medium ">
                                            {{pago.has_one_score ?  pago.has_one_score.score : '' }}
                                            <!-- <input type="number" v-model="pago.has_one_score.score" 
                                                class="relative m-0 w-full text-right block  min-w-4 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none" /> -->
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-left font-medium ">
                                            {{pago.status }}
                                            <!-- <input type="number" v-model="pago.has_one_score.score" 
                                                class="relative m-0 w-full text-right block  min-w-4 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none" /> -->
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-right font-medium " v-if="pago.has_one_score">
                                            <!-- {{pago.has_one_score ?  pago.has_one_score.comentario : '' }} -->
                                            <input type="text" v-model="pago.has_one_score.comentario" 
                                                class="relative m-0 w-full text-right block  min-w-4 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none" />
                                        </div>
                                        <div class="text-right font-medium " v-else>
                                            <!-- {{pago.has_one_score ?  pago.has_one_score.comentario : '' }} -->
                                            <input type="text" v-model="pago.comentarioNewPago" 
                                                class="relative m-0 w-full text-right block  min-w-4 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none" />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
            <template #footer>
                <btnPrimay @click.prevent="RegistrarPago">
                    Registrar Pago
                </btnPrimay>
            </template>
        </modal>
    </AppLayout>
</template>