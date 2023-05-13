<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import modal from "../../Components/DialogModal.vue";
import listExpacionPanel from "../../Components/listWithExpancionPanel.vue";
import btnPrimary from "../../Components/PrimaryButton.vue";
import * as XLSX from 'xlsx';

import { ref, onMounted } from 'vue'

defineProps({
    isCliente: Boolean,
});
const dataList = ref([
    { titulo: 'PRESTAMO EMERGENTE', toggle: false },
    { titulo: 'PRESTAMO SEGUNDA OCASIÓN', toggle: false },
    { titulo: 'PRESTAMO DE PRIMERA OCASIÓN ', toggle: false },
    { titulo: 'PRESTAMO PARA TRABAJADOR INDEPENDIENTE ', toggle: false },
    { titulo: 'PRESTAMOS MAYOR QUE 20 MIL para cliente de 2da ocasión ', toggle: false },
    { titulo: 'PRESTAMOS MAYOR QUE 50 MIL para cliente de 2da ocasión ', toggle: false }
])
const openModalSubirDocumento = ref(false);
const openModalAgergarManulDatos = ref(false);
const jsonData = ref(false);
const BancoDeDatos = ref(false);
const headerTableBancoDeDatos = ref([
    { header: "Nombre de supervisor OLA ", fixed: false, value: '', class: '' }, // Nombre de supervisor OLA 
    { header: "Ciudad ", fixed: false, value: '', class: '' }, // Ciudad 
    { header: "NOMBRE COMPLETO", fixed: false, value: '', class: '' }, // NOMBRE COMPLETO	
    { header: "Fecha de nacimiento", fixed: false, value: '', class: '' }, // Fecha de nacimiento	
    { header: "Dirección ", fixed: false, value: '', class: '' }, // Dirección 	
    { header: "INE o identificación oficial", fixed: false, value: '', class: '' }, // INE o identificación oficial	
    { header: "Tipo de relación con con la persona titular del comprobante de domicilio (del cliente)", fixed: false, value: '', class: '' }, // Tipo de relación con con la persona titular del comprobante de domicilio (del cliente)
    { header: "Comprobante de domicilio del cliente", fixed: false, value: '', class: '' }, // Comprobante de domicilio del cliente	
    { header: "Comprobante de domicilio alterno", fixed: false, value: '', class: '' }, // Comprobante de domicilio alterno	
    { header: "Tipo de relación con la persona titular del comprobante de domicilio (del domicilio alterno)	", fixed: false, value: '', class: '' }, // Tipo de relación con la persona titular del comprobante de domicilio (del domicilio alterno)	
    { header: "¿Cuál es su principal fuente de ingreso? o ¿Cómo piensa pagar lo que está solicitando?", fixed: false, value: '', class: '' }, //¿Cuál es su principal fuente de ingreso? o ¿Cómo piensa pagar lo que está solicitando?
    { header: "¿Cuál es el producto que se está solicitando?", fixed: false, value: '', class: '' },// ¿Cuál es el producto que se está solicitando?
    { header: "Por favor indica el monto de dinero solicitado", fixed: false, value: '', class: '' }, // Por favor indica el monto de dinero solicitado	
    { header: "¿Cuál es la razón de tu crédito?	", fixed: false, value: '', class: '' },// ¿Cuál es la razón de tu crédito?	
    { header: "¿En cuánto tiempo piensas pagar tu crédito?", fixed: false, value: '', class: '' }, // ¿En cuánto tiempo piensas pagar tu crédito?	
    { header: "¿Bajo qué esquema está acostumbrado(a) pagar sus préstamos?", fixed: false, value: '', class: '' }, // ¿Bajo qué esquema está acostumbrado(a) pagar sus préstamos?
    { header: "¿Para qué necesita el capital?", fixed: false, value: '', class: '' }, // ¿Para qué necesita el capital?	préstamos?
    { header: "¿Tú eres trabajador independiente?", fixed: false, value: '', class: '' }, // ¿Tú eres trabajador independiente?	
    { header: "Nombre y apellido del obligado solidario para Trabajador independiente", fixed: false, value: '', class: '' }, // Nombre y apellido del obligado solidario para Trabajador independiente 
    { header: "Teléfono del obligado solidario para Trabajador independiente", fixed: false, value: '', class: '' }, // Teléfono del obligado solidario para Trabajador independiente 	 
    { header: "Escala de Referencia Gerente de Cliente OLA", fixed: false, value: '', class: '' }, // Escala de Referencia Gerente de Cliente OLA
    { header: "Correo electrónico del solicitante", fixed: false, value: '', class: '' }, // Correo electrónico del solicitante	
    { header: "¿Cuenta con una cuenta bancaria?", fixed: false, value: '', class: '' },// ¿Cuenta con una cuenta bancaria?	
    { header: "Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?", fixed: false, value: '', class: '' },// Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?	
    { header: "El local de su negocio, ¿es rentado o propiedad del solicitante?", fixed: false, value: '', class: '' },
    { header: "Por favor sube fotos del local ", fixed: false, value: '', class: '' }, // ¿Cuál es el número de su cuenta/tarjeta?	
    { header: "¿Cuál es el número de su cuenta/tarjeta?", fixed: false, value: '', class: '' },// ¿Cuál es el número de su cuenta/tarjeta?	
    { header: "¿Cuál es el número de cuenta Clabe?", fixed: false, value: '', class: '' },// ¿Cuál es el número de cuenta CLABE?	
    { header: "¿no tiene cuenta o cuenta CLABE, carga imagen la tarjeta de banco.", fixed: false, value: '', class: '' },// Si no tiene su número de cuenta o cuenta CLABE, por favor sube la imagen la tarjeta de banco.	
    { header: "Nombre y teléfono de una referencia familiar", fixed: false, value: '', class: '' }, // Nombre y teléfono de una referencia familiar	
    { header: "Nombre y teléfono de una referencia personal", fixed: false, value: '', class: '' }, // Nombre y teléfono de una referencia personal	
    { header: "Comprobante de ingresos, certificado laboral o contrato de trabajo (para maestros será aceptado talón de cheque con plaza)	", fixed: false, value: '', class: '' },// Comprobante de ingresos, certificado laboral o contrato de trabajo (para maestros será aceptado talón de cheque con plaza)	
    { header: "Nombre y teléfono de una referencia  en su empresa", fixed: false, value: '', class: '' },// Nombre y teléfono de una referencia  en su empresa
    { header: "Número de contacto (Whatsapp del solicitante)", fixed: false, value: '', class: '' },// Número de contacto (Whatsapp del solicitante)
    { header: "Quien lo refiere? Que tipo de  relación tiene (amistad, familia, vecino, etc)? ", fixed: false, value: '', class: '' },// Quien lo refiere? Que tipo de  relación tiene (amistad, familia, vecino, etc)? 	
    { header: "Tiene redes sociales? Como lo encontramos en las Redes Sociales? (LinkedIn, Facebook, instagram, etc.)", fixed: false, value: '', class: '' },// Tiene redes sociales? Como lo encontramos en las Redes Sociales? (LinkedIn, Facebook, instagram, etc.)	
    { header: "Desde cuando lo conoce? Porque lo Refiere?", fixed: false, value: '', class: '' },// Desde cuando lo conoce? Porque lo Refiere? 
    { header: "Nombre y Apellido del Aval o subrogado solidario en caso de fallecimiento", fixed: false, value: '', class: '' },// Nombre y Apellido del Aval o subrogado solidario en caso de fallecimiento
    { header: "Accion", fixed: false, value: '', class: '' }
]);
const handleFileUpload = (e) => {
    const file = e.target.files[0];
    const reader = new FileReader();
    reader.onload = (e) => {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
        jsonData.value = XLSX.utils.sheet_to_json(worksheet, {
            defval: '', // Tratar las celdas vacías como cadenas vacías
            blankrows: false, // Omitir filas vacías
            raw: false, // Convertir valores de celda a sus tipos nativos (números, fechas, etc.)
            skipHidden: false, // Omitir columnas ocultas
            dateNF: 'yyyy-mm-dd', // Formato de fecha personalizado si corresponde
        });
    }
    reader.readAsArrayBuffer(file);
}

const saveUploadFile = async () => {
    try {
        let { data } = await axios.post(route('add.upload.file.json'), jsonData.value)
        geBancoDeDatos()
    } catch (error) {
        console.log(error)
    }
}
const geBancoDeDatos = async () => {
    try {
        let { data } = await axios(route('get.banco.de.datos'))
        BancoDeDatos.value = data
    } catch (error) {
        console.log(error)
    }
}



onMounted(() => {
    geBancoDeDatos()
})
</script>
<template>
    <AppLayout title="banco de datos" :isCliente="isCliente">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex gap-4 justify-between">
                <p>Banco de datos</p>
                <div class="flex gap-4">
                    <div class="float-right self-end">
                        <button class="bg-blue-500 hover:bg-blue-600 rounded-full p-3 shadow-lg float-right"
                            @click.prevent="openModalAgergarManulDatos = true">
                            <svg width="20px" height="20px" viewBox="0 0 20 20">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Dribbble-Light-Preview" transform="translate(-340.000000, -1519.000000)"
                                        fill="#ffffff">
                                        <g id="icons" transform="translate(56.000000, 160.000000)">
                                            <path
                                                d="M292.998,1369.105 L294.998,1369.105 L294.998,1367.105 L292.998,1367.105 L292.998,1369.105 Z M302,1377 L295,1377 L295,1371 L293,1371 L293,1377 L286,1377 L286,1361 L296,1361 L296,1367 L302,1367 L302,1377 Z M298,1359 L284,1359 L284,1379 L304,1379 L304,1365.081 L298,1359 Z"
                                                id="file_information-[#1694]">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </button>
                    </div>
                    <div class="">
                        <button @click.prevent="openModalSubirDocumento = true"
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
        <div class="bg-white max-w-7xl mx-auto shadow-md rounded my-6 overflow-x-scroll">
            <table class=" w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 capitalize leading-normal relative">
                        <th class="py-3 w-12 px-3 text-center bg-gray-200 text-gray-600 text-xs"
                            :class="{ ' sticky left-0 h-full bg-gray-100 w-32 ': h.fixed }"
                            v-for="(h, n) in headerTableBancoDeDatos" :key="n">
                            {{ h.header }}
                        </th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <tr class="border-b border-gray-200 hover:bg-gray-100" v-for="(data, d) in BancoDeDatos" :key="d">

                        <td class="py-3 px-6 text-center">
                            <span>{{ data.nombre_supervisor_ola }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.ciudad }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.nombre_completo }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.fecha_de_nacimiento }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.direccion }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.ine_o_identificacion_oficial }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.tipo_de_relacion_persona_titular_comprobante_domicilio_cliente }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.comprobante_domicilio_cliente }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.comprobante_domicilio_alterno }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.tipo_de_relacion_persona_titular_comprobante_domicilio_alterno }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.principal_fuente_de_ingreso }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.producto_solicitado }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.dinero_solicitado }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.razon_credito }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.en_que_tiempo_piensa_pagar }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.bajo_que_esquema_esta_acostumbrado_a_pagar }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.para_que_necesita_el_dinero }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.cuenta_con_una_cuenta_bancaria }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.banco_cuenta_bancaria }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.numero_de_cuenta_clabe }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.numero_de_cuenta_tarjeta }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.si_no_tiene_tarjeta_o_cable_indique_tarjeta_de_banco }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.nombre_telefono_referencia_familia }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.nombre_telefono_referencia_personal }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.comprobante_ingresos_certificado_laboral }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.nombre_telefono_referencia_empresa }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.eres_trabajador_independiente }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.nombre_apellido_del_solidario_trabajador_independiente }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.telefono_del_solidario_trabajador_independiente }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.ine_identificacion_del_solidario_trabajador_independiente }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.escala_referencia_gerente_ola }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.correo_electronico_del_solicitante }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.numero_de_contacto_ws }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.quien_lo_refiere_que_tipo_de_relacion_tiene }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.tiene_redes_sociales }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.desde_cuando_lo_conoce_porque_lo_refiere }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.nombre_apellido_aval_subrogado_solidario_fallecimiento }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ data.caratula_del_estado_de_cuenta }}</span>
                        </td>
                        





                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                <div class="w-4 mr-2 transform  hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                                <div class="w-4 mr-2 transform  hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </div>
                                <div class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
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
        <modal :show="openModalSubirDocumento" maxWidth="lg">
            <template #title>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 max-h-fit " @click="openModalSubirDocumento = false">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </template>
            <template #content>
                <div class="flex flex-col gap-4 mt-8 justify-center rounded-lg bg-white  dark:bg-neutral-700 w-full">
                    <input @change="handleFileUpload" type="file" class="border border-gray-300 py-2 px-4 rounded-lg w-full"
                        placeholder="Nombre">
                </div>
            </template>
            <template #footer>
                <btnPrimary @click.prevent="saveUploadFile">
                    Agregar
                </btnPrimary>
            </template>
        </modal>
        <modal :show="openModalAgergarManulDatos" maxWidth="">
            <template #title>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 max-h-fit "
                    @click="openModalAgergarManulDatos = false">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </template>
            <template #content>
                <div class="mt-8 justify-center rounded-lg bg-white  dark:bg-neutral-700 w-full">
                    <form class=" mx-2 text-[11px] grid grid-cols-5 gap-2">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="apellido_materno">
                                Apellido Materno
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="apellido_materno" type="text" placeholder="Ingresa tu apellido materno">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="apellido_paterno">
                                Apellido Paterno
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="apellido_paterno" type="text" placeholder="Ingresa tu apellido paterno">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="aprobacion_legal">
                                Aprobación Legal
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="aprobacion_legal" type="text" placeholder="Ingresa tu aprobación legal">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="aprobacion_operativa">
                                Aprobación Operativa
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="aprobacion_operativa" type="text" placeholder="Ingresa tu aprobación operativa">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="ciudad">
                                Ciudad
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="ciudad" type="text" placeholder="Ingresa tu ciudad">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2"
                                for="colaborador_ola_o_cliente_ola_que_lo_referencia">
                                Colaborador Ola o Cliente Ola que lo referencia
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="colaborador_ola_o_cliente_ola_que_lo_referencia" type="text"
                                placeholder="Ingresa el colaborador Ola o Cliente Ola que lo referencia">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="comprobacion_domicilio_del_cliente">
                                Comprobación de Domicilio del Cliente
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="comprobacion_domicilio_del_cliente" type="text"
                                placeholder="Ingresa la comprobación de domicilio del cliente">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="correo_electronico_del_solicitante">
                                Correo Electrónico del Solicitante
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="correo_electronico_del_solicitante" type="text"
                                placeholder="Ingresa tu correo electrónico">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="desde_cuando_lo_conoce_porque_refiere">
                                ¿Desde cuándo lo conoce? ¿Por qué lo refiere?
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="desde_cuando_lo_conoce_porque_refiere" type="text"
                                placeholder="Ingresa desde cuándo lo conoces y por qué lo refieres">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="direccion">
                                Dirección
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="direccion" type="text" placeholder="Ingresa tu dirección">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2"
                                for="el_local_de_su_negocio_es_restado_o_propiedad_del_solicitante">
                                ¿El local de su negocio es rentado o propiedad del solicitante?
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="el_local_de_su_negocio_es_restado_o_propiedad_del_solicitante" type="text"
                                placeholder="Ingresa la respuesta aquí">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2"
                                for="escala_de_referencia_gerente_de_cliente_ola">
                                Escala de referencia Gerente de Cliente Ola
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="escala_de_referencia_gerente_de_cliente_ola" type="text"
                                placeholder="Ingresa la escala de referencia aquí">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="fecha_de_nacimiento">
                                Fecha de Nacimiento
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="fecha_de_nacimiento" type="text" placeholder="Ingresa tu fecha de nacimiento">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="ine_o_identificacion_oficial">
                                INE o Identificación Oficial
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="ine_o_identificacion_oficial" type="text"
                                placeholder="Ingresa tu INE o Identificación Oficial">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="marca_temporal">
                                Marca Temporal
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="marca_temporal" type="text" placeholder="Ingresa la marca temporal aquí">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="nombre_completo">
                                Nombre Completo
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="nombre_completo" type="text" placeholder="Ingresa tu nombre completo">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="nombres">
                                Nombres
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="nombres" type="text" placeholder="Ingresa tus nombres">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="nombre_de_supervisort_ola">
                                Nombre de Supervisor(a) Ola
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="nombre_de_supervisort_ola" type="text"
                                placeholder="Ingresa el nombre de tu supervisor(a) Ola">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="numero_de_contacto_ws_solicitante">
                                Número de Contacto (WhatsApp) del Solicitante
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="numero_de_contacto_ws_solicitante" type="text"
                                placeholder="Ingresa tu número de contacto de WhatsApp">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="monto_dinero_solicitado">
                                Monto de Dinero Solicitado
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="monto_dinero_solicitado" type="text"
                                placeholder="Ingresa el monto de dinero que solicitas">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="foto_del_local">
                                Foto del Local
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="foto_del_local" type="text" placeholder="Ingresa la URL de la foto del local">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2"
                                for="quien_lo_refiere_que_tipo_de_relacion_tiene">
                                ¿Quién lo Refiere? ¿Qué Tipo de Relación Tiene?
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="quien_lo_refiere_que_tipo_de_relacion_tiene" type="text"
                                placeholder="Ingresa el nombre de quien lo refiere y la relación que tiene contigo">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2"
                                for="si_no_tiene_cuenta_imagen_de_la_cuenta_del_banco">
                                ¿Si no Tiene Cuenta, Imagen de la Cuenta del Banco?
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="si_no_tiene_cuenta_imagen_de_la_cuenta_del_banco" type="text"
                                placeholder="Ingresa la URL de la imagen de la cuenta del banco (si aplica)">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="tiene_redes_sociales">
                                ¿Tiene Redes Sociales?
                            </label>
                            <div class="relative">
                                <select
                                    class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                    id="tiene_redes_sociales">
                                    <option>Selecciona una opción</option>
                                    <option>Sí</option>
                                    <option>No</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                        <path d="M9 11l3 3-3 3V11z"></path>
                                        <path d="M2 10v1h6v-1H2zm12 0v1h6v-1h-6zm-5-8h-2v6H5v2h4v6h2v-6h4v-2h-4z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="a_que_instucion_le_debe_dinero">
                                ¿A qué Institución le Debe Dinero?
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="a_que_instucion_le_debe_dinero" type="text"
                                placeholder="Ingresa el nombre de la institución a la que le debes dinero">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="cuenta_con_una_cuenta_bancaria">
                                ¿Cuenta con una Cuenta Bancaria?
                            </label>
                            <div class="relative">
                                <select
                                    class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                    id="cuenta_con_una_cuenta_bancaria">
                                    <option>Selecciona una opción</option>
                                    <option>Sí</option>
                                    <option>No</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                        <path d="M9 11l3 3-3 3V11z"></path>
                                        <path d="M2 10v1h6v-1H2zm12 0v1h6v-1h-6zm-5-8h-2v6H5v2h4v6h2v-6h4v-2h-4z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2"
                                for="si_respuesta_fue_si_en_banco_tiene_cuenta">
                                Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="si_respuesta_fue_si_en_banco_tiene_cuenta" type="text" placeholder="Ingresa el banco">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="cual_es_el_numero_de_su_cuenta_clabe">
                                ¿Cuál es el Número de su Cuenta CLABE?
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="cual_es_el_numero_de_su_cuenta_clabe" type="text"
                                placeholder="Ingresa el número de tu cuenta CLABE">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="cual_es_el_numero_de_su_cuenta_tarjeta">
                                ¿Cuál es el Número de su Cuenta Tarjeta?
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="cual_es_el_numero_de_su_cuenta_tarjeta" type="text"
                                placeholder="Ingresa el número de tu cuenta tarjeta">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="cual_es_el_producto_que_se_solicitando">
                                ¿Cuál es el Producto que se está Solicitando?
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="cual_es_el_producto_que_se_solicitando" type="text"
                                placeholder="Ingresa el nombre del producto que estás solicitando">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2"
                                for="cual_es_la_razon_por_la_que_tiene_esa_deuda">
                                ¿Cuál es la Razón por la que Tiene esa Deuda?
                            </label>
                            <textarea
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="cual_es_la_razon_por_la_que_tiene_esa_deuda"
                                placeholder="Escribe aquí la razón por la que tienes esa deuda"></textarea>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                    for="fuente-de-ingreso">
                                    ¿Cuál es el principal fuente de ingreso o cómo piensa pagar?:
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    id="fuente-de-ingreso" type="text" placeholder="Ingrese su respuesta">
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                    for="tiempo-con-el-dinero">
                                    ¿Hace cuánto tiempo tiene ese dinero?:
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    id="tiempo-con-el-dinero" type="text" placeholder="Ingrese su respuesta">
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                    for="razon-de-capital">
                                    ¿Para qué necesita el capital?:
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    id="razon-de-capital" type="text" placeholder="Ingrese su respuesta">
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                    for="razon-deuda">
                                    ¿Por qué no ha podido pagar su deuda?:
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    id="razon-deuda" type="text" placeholder="Ingrese su respuesta">
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                    for="proveedores-factura">
                                    ¿Sus proveedores pueden emitir factura?:
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    id="proveedores-factura" type="text">
                            </div>
                        </div>
                        <!-- <div class="flex flex-wrap -mx-3 mb-6"> -->

                        <!-- </div> -->
                    </form>
                </div>
            </template>
            <template #footer>
                <btnPrimary>
                    Agregar
                </btnPrimary>
            </template>
        </modal>
    </AppLayout>
</template>