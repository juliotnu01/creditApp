<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import modal from "../../Components/DialogModal.vue";
import { useCreditRequest } from '../../stores/creditRequest'
import btnPrimay from '../../Components/PrimaryButton.vue'
import { ref, onMounted, computed } from 'vue';
defineProps({
    isCliente: Boolean,
});
const storeCreditRequest = useCreditRequest();
const dataCreditRequest = ref([])
const openModal = ref(false);
const openModalViewAmortizaciones = ref(false);
const formulario = computed({
    get() {
        return storeCreditRequest.formulario
    },
    set(val) {
        storeCreditRequest.setformulario(val)
    }
})
const amortizaciones = computed({
    get() {
        return storeCreditRequest.amortizaciones
    },
    set(val) {
        storeCreditRequest.setAmortizaciones(val)
    }
})
const getSolicitudesDeCreditos = async () => {
    try {
        let { data } = await axios('/api/get-credit-request')
        dataCreditRequest.value = data
    } catch (error) {
        console.log(error)
    }
}
const formatCurrency = (value) => {
    const formatter = new Intl.NumberFormat("es-MX", {
        style: "currency",
        currency: "MXN",
    });
    return formatter.format(value);
}
const viewSolicitudDeCredito = async (credito = nul) => {
    formulario.value = credito
    openModal.value = true
}

const enviarCreditoAEstudio = async (credito = null) => {
    try {
        axios.post('/api/enviar-credito-a-estudio', { id_credito: credito.id, uui: credito.uui })
        getSolicitudesDeCreditos();
        openModal.value = false
    } catch (error) {
        console.log(error)
    }
}
const RechazarCreditoAEstudio = async (credito = null) => {
    try {
        axios.post('/api/rechazar-credito', { id_credito: credito.id, uui: credito.uui })
        getSolicitudesDeCreditos();

    } catch (error) {
        console.log(error)
    }
}
onMounted(() => {
    getSolicitudesDeCreditos()
})
</script>
<template>
    <AppLayout title="Solicitudes de creditos" :isCliente="isCliente">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Solicitudes de creditos
            </h2>
        </template>
        <div class="overflow-x-auto flex items-center justify-center">
            <div class="w-full lg:w-5/6">
                <div class="bg-white shadow-md rounded my-6">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Identificador</th>
                                <th class="py-3 px-6 text-center">Usuario</th>
                                <th class="py-3 px-6 text-center">Monto Solicitado</th>
                                <th class="py-3 px-6 text-center">Status</th>
                                <th class="py-3 px-6 text-center">Actions</th>
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
                                    <!-- <div class="flex items-center justify-center">
                                                                            <img class="w-6 h-6 rounded-full border-gray-200 border transform hover:scale-125"
                                                                                src="https://randomuser.me/api/portraits/men/1.jpg" />
                                                                            <img class="w-6 h-6 rounded-full border-gray-200 border -m-1 transform hover:scale-125"
                                                                                src="https://randomuser.me/api/portraits/women/2.jpg" />
                                                                            <img class="w-6 h-6 rounded-full border-gray-200 border -m-1 transform hover:scale-125"
                                                                                src="https://randomuser.me/api/portraits/men/3.jpg" />
                                                                        </div> -->
                                    <span>{{ formatCurrency(solicitud.monto_de_dinero_solicitado) }}</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span
                                        :class="{ 'bg-yellow-200 text-black py-1 px-3 rounded-full text-xs': solicitud.status == 0, 'bg-orange-400 text-black py-1 px-3 rounded-full text-xs': solicitud.status == 1, 'bg-red-400 text-white py-1 px-3 rounded-full text-xs': solicitud.status == 2 }">
                                        {{ solicitud.status == 0 ? 'Credito pendiente por revisar' : solicitud.status == 1 ?
                                            'Credito aprobado para estudio' : solicitud.status == 2 ? 'Credito Rechazado' : ''
                                        }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <div class="w-4 mr-2 transform  hover:scale-110"
                                            @click="viewSolicitudDeCredito(solicitud)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </div>
                                        <div class="w-4 mr-2 transform  hover:scale-110" @click.prevent="openModalViewAmortizaciones = true" >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </div>
                                        <div class="w-4 mr-2 transform hover:text-red-500 hover:scale-110"
                                            @click.prevent="RechazarCreditoAEstudio(solicitud)">
                                            <svg fill="#000000" height="15px" width="15px" viewBox="0 0 329.328 329.328"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M164.666,0C73.871,0,0.004,73.871,0.004,164.672c0.009,90.792,73.876,164.656,164.662,164.656
                                                                                c90.793,0,164.658-73.865,164.658-164.658C329.324,73.871,255.459,0,164.666,0z M164.666,30c31.734,0,60.933,11.042,83.975,29.477
                                                                                L59.478,248.638c-18.431-23.04-29.471-52.237-29.474-83.967C30.004,90.413,90.413,30,164.666,30z M164.666,299.328
                                                                                c-31.733,0-60.934-11.042-83.977-29.477L269.854,80.691c18.431,23.043,29.471,52.244,29.471,83.979
                                                                                C299.324,238.921,238.917,299.328,164.666,299.328z" />
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <modal :show="openModal" maxWidth="8/12">
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
                    <div
                        class="flex mt-8 flex-col rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 w-full">
                        <div class="flex flex-col justify-start p-6">
                            <div class="w-ful mx-auto mt-8">
                                <h1 class="text-2xl font-bold mb-4">Formulario de solicitud de préstamo: {{ formulario.uui
                                }}
                                </h1>
                                <div class="grid grid-cols-4 sm:grid-cols-4 lg:grid-cols-4 gap-4 text-[13px]">
                                    <div>
                                        <label class="block mb-2 font-bold" for="nombre">Nombre:</label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text" id="nombre"
                                            v-model="formulario.nombre">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="apellido_paterno">Apellido
                                            Paterno:</label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="apellido_paterno" v-model="formulario.apellido_paterno">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="apellido_materno">Apellido
                                            Materno:</label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="apellido_materno" v-model="formulario.apellido_materno">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="fecha_nacimiento">Fecha de
                                            Nacimiento:</label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="date"
                                            id="fecha_nacimiento" v-model="formulario.fecha_nacimiento">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="nacionalidad">Nacionalidad:</label>
                                    <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                        id="nacionalidad" v-model="formulario.nacionalidad">
                                </div>
                                <div>
                                    <label class="block mb-2 font-bold" for="correo">Correo:</label>
                                    <input disabled class="w-full py-2 px-3 rounded border" type="email" id="correo"
                                            v-model="formulario.correo">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="celular">Celular
                                            (Whatsapp):</label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text" id="celular"
                                            v-model="formulario.celular">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="telefono">Teléfono fijo:</label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text" id="telefono"
                                            v-model="formulario.telefono">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="text_comprobante_ine_identificacion">
                                            INE o identificación:
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="text_comprobante_ine_identificacion"
                                            v-model="formulario.text_comprobante_ine_identificacion">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="tipo_identificacion_social">
                                            Tipo de Identificación Social:
                                        </label>
                                        <select disabled v-model="formulario.tipo_identificacion_social"
                                            class="w-full py-2 px-3 rounded border" id="tipo_identificacion_social">
                                            <option value="INE">INE</option>
                                            <option value="PASAPORTE">PASAPORTE</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="numero_curp">
                                            Número Curp
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text" id="numero_curp"
                                            v-model="formulario.numero_curp">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="direccion_domicilio">
                                            Direccion de domicilio
                                        </label>
                                        <textarea disabled v-model="formulario.direccion_domicilio"
                                            class="w-full py-2 px-3 rounded border" id="direccion_domicilio"
                                            name="textarea" />
                                    </div>

                                    <div>
                                        <label class="block mb-2 font-bold"
                                            for="relacion_con_la_persona_del_titular_del_comprobante_domicilio">
                                            Tipo de relación con con la persona titular del comprobante de domicilio
                                            (del cliente)
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="relacion_con_la_persona_del_titular_del_comprobante_domicilio"
                                            v-model="formulario.relacion_per_del_titular_del_compro_domicilio_cliente">
                                    </div>
                                    <!-- <div>
                                                                            <label class="block mb-2 font-bold" for="text_comprobante_ine_identificacion">
                                                                                Comprobante de domicilio alterno
                                                                            </label>
                                                                            <input v-model="formulario." class="w-full py-2 px-3 rounded border" type="text"
                                                                                id="text_comprobante_ine_identificacion">
                                                                        </div> -->
                                    <div>
                                        <label class="block mb-2 font-bold"
                                            for="relacion_con_la_persona_del_titular_del_comprobante_domicilio">
                                            Tipo de relación con la persona titular del comprobante de domicilio
                                            (del domicilio
                                            alterno)
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="relacion_con_la_persona_del_titular_del_comprobante_domicilio"
                                            v-model="formulario.relacion_per_del_titular_del_compro_domicilio_domicilio">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold"
                                            for="principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado">
                                            ¿Cuál es su principal fuente de ingreso? o ¿Cómo piensa pagar lo que
                                            está solicitando?
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado"
                                            v-model="formulario.principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="cual_es_producto_que_esta_solicitando">
                                            ¿Cuál es el producto que se está solicitando?
                                        </label>
                                        <select disabled v-model="formulario.cual_es_producto_que_esta_solicitando"
                                            class="w-full py-2 px-3 rounded border"
                                            id="cual_es_producto_que_esta_solicitando">
                                            <option value="Reparacion critica">Reparacion critica</option>
                                            <option value="Prestamo a micro negocio">Prestamo a micro negocio
                                            </option>
                                            <option value="Credito de segunda ocacion">Credito de segunda ocacion
                                            </option>
                                            <option value="Credito a empleado o trabajador independiente">Credito a
                                                empleado o
                                                trabajador independiente</option>
                                            <option value="Prestamo emergente">Prestamo emergente</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold"
                                            for="escala_de_referencia_gerente_de_cliente_ola">
                                            Escala de Referencia Gerente de Cliente OLA
                                        </label>
                                        <div class="container">
                                            <div class="flex justify-between">
                                                <span class="text-gray-500 text-lg">1</span>
                                                <span class="text-gray-500 text-lg">2</span>
                                                <span class="text-gray-500 text-lg">3</span>
                                                <span class="text-gray-500 text-lg">4</span>
                                                <span class="text-gray-500 text-lg">5</span>
                                            </div>
                                            <input disabled id="escala_de_referencia_gerente_de_cliente_ola"
                                                v-model="formulario.escala_de_referencia_gerente_de_cliente_ola"
                                                type="range" min="1" max="5" step="1" class="mt-4 w-full">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="justificar_la_referencia">
                                            Justificar la referencia (Solo si la escala es menor a 3)
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="justificar_la_referencia" v-model="formulario.justificar_la_referencia">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="quien_lo_refiere">
                                            Quién lo refiere? Que tipo de relación tiene (amistad, familia, vecino,
                                            etc)?
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="quien_lo_refiere" v-model="formulario.quien_lo_refiere">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="desde_cuando_lo_conoce_por_que_lo_refiere">
                                            Desde cuando lo conoce? Porque lo Refiere?
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="desde_cuando_lo_conoce_por_que_lo_refiere"
                                            v-model="formulario.desde_cuando_lo_conoce_por_que_lo_refiere">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="tiene_redes_sociales">
                                            Tiene redes sociales? Como lo encontramos en las Redes Sociales?
                                            (LinkedIn, Facebook,
                                            instagram, etc.)
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="tiene_redes_sociales" v-model="formulario.tiene_redes_sociales">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="ciudad">
                                            Ciudad
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text" id="ciudad"
                                            v-model="formulario.ciudad">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="text_comprobante_ine_identificacion">
                                            Monto de dinero solicitado
                                        </label>
                                        <div class="relative mb-4 flex flex-wrap ">
                                            <span
                                                class="flex items-center whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                                                id="basic-addon1">$</span>
                                            <input disabled type="number" v-model="formulario.monto_de_dinero_solicitado"
                                                min="3000"
                                                class="relative m-0 text-right block w-6 min-w-4 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary"
                                                placeholder="Username" aria-label="Username"
                                                aria-describedby="basic-addon1" />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="razon_del_prestamo">
                                            ¿Cuál es la razón por la que tiene esa deuda?
                                        </label>
                                        <select disabled v-model="formulario.razon_del_prestamo"
                                            class="w-full py-2 px-3 rounded border" id="razon_del_prestamo"
                                            name="razon_del_prestamo" required="">
                                            <option value="">Selecciona la razón</option>
                                            <option value="prestamo por otra persona">Pidió un préstamo por una
                                                persona y luego no
                                                se lo pago</option>
                                            <option value="atraso">Se atrasó en algunos pagos y solo ha pagado
                                                intereses</option>
                                            <option value="problema">Tuvo un problema financiero</option>
                                            <option value="otro">Otro...</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="hace_cuanto_tiempo_debe_ese_dinero">
                                            ¿Hace cuánto tiempo debe ese dinero?
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="hace_cuanto_tiempo_debe_ese_dinero"
                                            v-model="formulario.hace_cuanto_tiempo_debe_ese_dinero">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="a_que_institucion_le_debe_ese_dinero">
                                            ¿A qué institución le debe ese dinero?
                                        </label>
                                        <select disabled v-model="formulario.a_que_institucion_le_debe_ese_dinero"
                                            class="w-full py-2 px-3 rounded border"
                                            id="a_que_institucion_le_debe_ese_dinero"
                                            name="a_que_institucion_le_debe_ese_dinero" required="">
                                            <option value="">Selecciona la institución</option>
                                            <option value="2">Banamex</option>
                                            <option value="3">Banco Azteca</option>
                                            <option value="1">Bancomer</option>
                                            <option value="7">Banorte/IXE</option>
                                            <option value="4">Coppel</option>
                                            <option value="8">HSBC</option>
                                            <option value="9">Prueba</option>
                                            <option value="5">Santander</option>
                                            <option value="6">Scotiabank</option>
                                            <option value="otro">Otro...</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="text_comprobante_ine_identificacion">
                                            ¿Cuenta con una cuenta bancaria?
                                        </label>
                                        <div class="form-block mb-2 font-bold">
                                            <input disabled class="form-check-input"
                                                v-model="formulario.cuenta_con_una_cuenta_bancaria" value="1"
                                                name="have_account" id="have_account" type="radio">
                                            <label class="form-check-label">Si</label>
                                        </div>
                                        <div class="form-block mb-2 font-bold">
                                            <input class="form-check-input"
                                                v-model="formulario.cuenta_con_una_cuenta_bancaria" value="0"
                                                name="have_account" id="have_account" type="radio" checked="">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold"
                                            for="respuesta_de_cuenta_con_una_cuenta_bancaria">
                                            ¿Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?
                                        </label>
                                        <select disabled v-model="formulario.respuesta_de_cuenta_con_una_cuenta_bancaria"
                                            class="w-full py-2 px-3 rounded border"
                                            id="respuesta_de_cuenta_con_una_cuenta_bancaria"
                                            name="respuesta_de_cuenta_con_una_cuenta_bancaria" required="">
                                            <option value="">Selecciona la institución</option>
                                            <option value="2">Banamex</option>
                                            <option value="3">Banco Azteca</option>
                                            <option value="1">Bancomer</option>
                                            <option value="7">Banorte/IXE</option>
                                            <option value="4">Coppel</option>
                                            <option value="8">HSBC</option>
                                            <option value="9">Prueba</option>
                                            <option value="5">Santander</option>
                                            <option value="6">Scotiabank</option>
                                            <option value="otro">Otro...</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="numero_de_cuenta_clabe">
                                            ¿Cuál es el número de cuenta Clabe?
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="numero_de_cuenta_clabe" v-model="formulario.numero_de_cuenta_clabe">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="numero_de_cuenta">
                                            ¿Cuál es su número de cuenta/tarjeta?
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="numero_de_cuenta" v-model="formulario.numero_de_cuenta">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="por_que_no_ha_popido_pagar_su_deuda">
                                            ¿Porqué no ha podido pagar su deuda?
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="por_que_no_ha_popido_pagar_su_deuda"
                                            v-model="formulario.por_que_no_ha_popido_pagar_su_deuda">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="nombre_apellido_del_obligado_solidario">
                                            Nombre y apellido del obligado solidario
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="nombre_apellido_del_obligado_solidario"
                                            v-model="formulario.nombre_apellido_del_obligado_solidario">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="telefono_obligado_solidario">
                                            Teléfono del obligado solidario
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="telefono_obligado_solidario"
                                            v-model="formulario.telefono_obligado_solidario">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="correo_obligado_solidario">
                                            Correo del Obligado solidario
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="correo_obligado_solidario" v-model="formulario.correo_obligado_solidario">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="nacionalidad_obligado_solidario">
                                            Nacionalidad del Obligado solidario
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="nacionalidad_obligado_solidario"
                                            v-model="formulario.nacionalidad_obligado_solidario">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="tipo_de_identificacion_oficial">
                                            Tipo de identificación oficial
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="tipo_de_identificacion_oficial"
                                            v-model="formulario.tipo_de_identificacion_oficial">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="ine_o_identificacion_oficial">
                                            INE o identificación oficial
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="ine_o_identificacion_oficial"
                                            v-model="formulario.ine_o_identificacion_oficial">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="numero_curp_obligado">
                                            Número CURP Obligado
                                        </label>
                                        <input disabled class="w-full py-2 px-3 rounded border" type="text"
                                            id="numero_curp_obligado" v-model="formulario.numero_curp_obligado">
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-bold" for="domicilio">
                                            Domicilio
                                        </label>
                                        <textarea disabled v-model="formulario.domicilio"
                                            class="w-full py-2 px-3 rounded border" id="domicilio" name="textarea" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto max-w-6/12 flex items-center justify-center">
                            <div class="w-auto  max-w-4/12 ">
                                <div class="bg-white shadow-md rounded my-6">
                                    <table class=" mx-20 table-auto">
                                        <thead>
                                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                                <th class="py-3 px-6 text-center">Caratula del estado de cuenta</th>
                                                <th class="py-3 px-6 text-center">Comprobante de domicilio</th>
                                                <th class="py-3 px-6 text-center">Comprobante domicilio cliente</th>
                                                <th class="py-3 px-6 text-center">Comprobante ine identificacion</th>
                                                <th class="py-3 px-6 text-center">Comprobante ine o identificacion oficial
                                                </th>
                                                <th class="py-3 px-6 text-center">Foto de tarjeta
                                                </th>
                                                <th class="py-3 px-6 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 text-sm font-light">
                                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                <td class="py-3 px-6 text-center whitespace-nowrap">

                                                    <a target="_blank" :href="formulario.file_caratula_del_estado_de_cuenta"
                                                        class="relative inline-block text-base font-medium text-indigo-500">
                                                        <span class="block">ver documento</span>
                                                    </a>
                                                </td>
                                                <td class="py-3 px-6 text-center whitespace-nowrap">

                                                    <a target="_blank" :href="formulario.file_comprobante_de_domicilio"
                                                        class="relative inline-block text-base font-medium text-indigo-500">
                                                        <span class="block">ver documento</span>
                                                    </a>
                                                </td>
                                                <td class="py-3 px-6 text-center whitespace-nowrap">

                                                    <a target="_blank" :href="formulario.file_comprobante_domicilio_cliente"
                                                        class="relative inline-block text-base font-medium text-indigo-500">
                                                        <span class="block">ver documento</span>
                                                    </a>
                                                </td>
                                                <td class="py-3 px-6 text-center whitespace-nowrap">

                                                    <a target="_blank"
                                                        :href="formulario.file_comprobante_ine_identificacion"
                                                        class="relative inline-block text-base font-medium text-indigo-500">
                                                        <span class="block">ver documento</span>
                                                    </a>
                                                </td>
                                                <td class="py-3 px-6 text-center whitespace-nowrap">

                                                    <a target="_blank"
                                                        :href="formulario.file_comprobante_ine_o_identificacion_oficial"
                                                        class="relative inline-block text-base font-medium text-indigo-500">
                                                        <span class="block">ver documento</span>
                                                    </a>
                                                </td>
                                                <td class="py-3 px-6 text-center whitespace-nowrap">

                                                    <a target="_blank" :href="formulario.file_foto_de_tarjeta"
                                                        class="relative inline-block text-base font-medium text-indigo-500">
                                                        <span class="block">ver documento</span>
                                                    </a>
                                                </td>

                                                <td class="py-3 px-6 text-center">
                                                    <div class="flex item-center justify-center">
                                                        <div class="w-4 mr-2 transform  hover:scale-110">
                                                            <svg width="15px" height="15px" stroke="currentColor"
                                                                viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <g id="Interface / Check_All_Big">
                                                                    <path
                                                                        d="M7 12L11.9497 16.9497L22.5572 6.34326M2.0498 12.0503L6.99955 17M17.606 6.39355L12.3027 11.6969"
                                                                        stroke="#000000" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                                </g>
                                                            </svg>
                                                        </div>
                                                        <div class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                                            <svg fill="#000000" height="15px" width="15px"
                                                                viewBox="0 0 329.328 329.328" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M164.666,0C73.871,0,0.004,73.871,0.004,164.672c0.009,90.792,73.876,164.656,164.662,164.656
                                                                            c90.793,0,164.658-73.865,164.658-164.658C329.324,73.871,255.459,0,164.666,0z M164.666,30c31.734,0,60.933,11.042,83.975,29.477
                                                                            L59.478,248.638c-18.431-23.04-29.471-52.237-29.474-83.967C30.004,90.413,90.413,30,164.666,30z M164.666,299.328
                                                                            c-31.733,0-60.934-11.042-83.977-29.477L269.854,80.691c18.431,23.043,29.471,52.244,29.471,83.979
                                                                            C299.324,238.921,238.917,299.328,164.666,299.328z" />
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
                    </div>




                </template>
                <template #footer>
                    <div class="flex justify-end">
                        <btnPrimay @click.prevent="enviarCreditoAEstudio(formulario)">Enviar a estudio</btnPrimay>
                    </div>
                </template>
            </modal>
            <modal :show="openModalViewAmortizaciones" maxWidth="2x1" >
                <template #title>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="openModalViewAmortizaciones = !openModalViewAmortizaciones">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                        </svg>
                    </span>
                </template>

                <template #content>
                    <div class="flex mt-8 w-full flex-col rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 w-full">
                        <div class="overflow-x-auto max-w-6/12 flex items-center justify-center">
                            <div class="w-auto  max-w-4/12 ">
                                <div class="bg-white shadow-md rounded my-6">
                                    <table class=" mx-20 table-auto">
                                        <thead>
                                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                                <th class="py-3 px-6 text-center">Periodo</th>
                                                <th class="py-3 px-6 text-center">Capital</th>
                                                <th class="py-3 px-6 text-center">Interes</th>
                                                <th class="py-3 px-6 text-center">Pago</th>
                                                <th class="py-3 px-6 text-center">Dias de pago</th>
                                                <th class="py-3 px-6 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 text-sm font-light">
                                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                <td class="py-3 px-6 text-center whitespace-nowrap">

                                                    <a target="_blank" :href="formulario.file_caratula_del_estado_de_cuenta"
                                                        class="relative inline-block text-base font-medium text-indigo-500">
                                                        <span class="block">{{amortizaciones}}</span>
                                                    </a>
                                                </td>
                                                

                                                <td class="py-3 px-6 text-center">
                                                    <div class="flex item-center justify-center">
                                                        <div class="w-4 mr-2 transform  hover:scale-110">
                                                            <svg width="15px" height="15px" stroke="currentColor"
                                                                viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <g id="Interface / Check_All_Big">
                                                                    <path
                                                                        d="M7 12L11.9497 16.9497L22.5572 6.34326M2.0498 12.0503L6.99955 17M17.606 6.39355L12.3027 11.6969"
                                                                        stroke="#000000" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                                </g>
                                                            </svg>
                                                        </div>
                                                        <div class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                                            <svg fill="#000000" height="15px" width="15px"
                                                                viewBox="0 0 329.328 329.328" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M164.666,0C73.871,0,0.004,73.871,0.004,164.672c0.009,90.792,73.876,164.656,164.662,164.656
                                                                            c90.793,0,164.658-73.865,164.658-164.658C329.324,73.871,255.459,0,164.666,0z M164.666,30c31.734,0,60.933,11.042,83.975,29.477
                                                                            L59.478,248.638c-18.431-23.04-29.471-52.237-29.474-83.967C30.004,90.413,90.413,30,164.666,30z M164.666,299.328
                                                                            c-31.733,0-60.934-11.042-83.977-29.477L269.854,80.691c18.431,23.043,29.471,52.244,29.471,83.979
                                                                            C299.324,238.921,238.917,299.328,164.666,299.328z" />
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
                    </div>




                </template>
                <template #footer>
                    <div class="flex justify-end">
                        <btnPrimay @click.prevent="enviarCreditoAEstudio(formulario)">Enviar a estudio</btnPrimay>
                    </div>
                </template>
            </modal>
    </div>
</AppLayout></template>