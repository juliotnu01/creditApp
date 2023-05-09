<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, watch } from 'vue';
import PrimaryButton from '../../Components/PrimaryButton.vue'


const props = defineProps({
    isCliente: Boolean,
});
const usuarios = ref([]);
const results = ref([]);
const filteredResults = ref([]);
const searchTerm = ref('');
const BancoDeDatos = ref(false);
const UserSelected = ref(false);
const ProductoSelected = ref('Selecciona el producto');
const headerTableBancoDeDatos = ref([
    { header: "Marca temporal", fixed: false, value: '', class: '', show: false },
    { header: "Nombre de supervisor OLA ", fixed: false, value: '', class: '', show: false },
    { header: "Ciudad ", fixed: false, value: '', class: '', show: false },
    { header: "Colaborador OLA o cliente OLA que lo referencía", fixed: false, value: '', class: '', show: false },
    { header: "Nombre (s)", fixed: false, value: '', class: '', show: false },
    { header: "Apellido Paterno", fixed: false, value: '', class: '', show: false },
    { header: "Apellido Materno", fixed: false, value: '', class: '', show: false },
    { header: "NOMBRE COMPLETO", fixed: false, value: '', class: '', show: false },
    { header: "Fecha de nacimiento", fixed: false, value: '', class: '', show: false },
    { header: "Dirección ", fixed: false, value: '', class: '', show: false },
    { header: "INE o identificación oficial", fixed: false, value: '', class: '', show: false },
    { header: "Comprobante de domicilio del cliente", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuál es su principal fuente de ingreso? o ¿Cómo piensa pagar lo que está solicitando?", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuál es el producto que se está solicitando?", fixed: false, value: '', class: '', show: false },
    { header: "Por favor indica el monto de dinero solicitado", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuál es la razón por la que tiene esa deuda?", fixed: false, value: '', class: '', show: false },
    { header: "¿Hace cuánto tiempo debe ese dinero?", fixed: false, value: '', class: '', show: false },
    { header: "¿A qué institución le debe ese dinero?", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuenta con una cuenta bancaria?", fixed: false, value: '', class: '', show: false },
    { header: "Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?", fixed: false, value: '', class: '', show: false },
    { header: "¿Porqué no ha podido pagar su deuda?", fixed: false, value: '', class: '', show: false },
    { header: "Por favor sube todos los documentos referentes al crédito o deuda que tiene", fixed: false, value: '', class: '', show: false },
    { header: "¿Para qué necesita el capital?", fixed: false, value: '', class: '', show: false },
    { header: "¿Sus proveedores pueden emitir facturas?", fixed: false, value: '', class: '', show: false },
    { header: "El local de su negocio, ¿es rentado o propiedad del solicitante?", fixed: false, value: '', class: '', show: false },
    { header: "Por favor sube fotos del local ", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuál es el número de su cuenta/tarjeta?", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuál es el número de cuenta Clabe?", fixed: false, value: '', class: '', show: false },
    { header: "¿no tiene cuenta o cuenta CLABE, carga imagen la tarjeta de banco.", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuál es su número de cuenta/tarjeta?", fixed: false, value: '', class: '', show: false },
    { header: "Escala de Referencia Gerente de Cliente OLA", fixed: false, value: '', class: '', show: false },
    { header: "Correo electrónico del solicitante", fixed: false, value: '', class: '', show: false },
    { header: "Número de contacto (Whatsapp del solicitante)", fixed: false, value: '', class: '', show: false },
    { header: "Quien lo refiere? Que tipo de  relación tiene (amistad, familia, vecino, etc)? ", fixed: false, value: '', class: '', show: false },
    { header: "Tiene redes sociales? Como lo encontramos en las Redes Sociales? (LinkedIn, Facebook, instagram, etc.)", fixed: false, value: '', class: '', show: false },
    { header: "Desde cuando lo conoce? Porque lo Refiere? ", fixed: false, value: '', class: '', show: false },
    { header: "Aprobación OPERATIVA (El cliente debe de cumplir con los requisitos operativos que pedimos y se debe de hacer una revisión sobre la documentación y su orden)", fixed: false, value: '', class: '', show: false },
    { header: "Aprobación LEGAL (Revisión exhaustiva sobre los datos, direcciones, nombres y aspectos legales)", fixed: false, value: '', class: '', show: false },
    { header: "Accion", fixed: false, value: '', class: '', show: true }
]);
const getUsuarios = async () => {
    try {
        let { data } = await axios('/api/get-users-clientes');
        results.value = data
    } catch (error) {
        console.log(error)
    }
}
const selectResult = (result) => {
    UserSelected.value = result
    filteredResults.value = []
    searchTerm.value = result.name
}
const filterResults = () => {
    filteredResults.value = results.value.filter(result =>

        result.name.toString().toLowerCase().includes(searchTerm.value.toLowerCase())
    );
}
const consutarUsuario = async () => {
    BancoDeDatos.value = UserSelected.value.has_one_document
}
const formatCurrency = (value) => {
    const formatter = new Intl.NumberFormat("es-MX", {
        style: "currency",
        currency: "MXN",
    });
    return formatter.format(value);
}

watch(() => ProductoSelected.value, (newValue, oldValue) => {
    switch (newValue) {
        case 'PRESTAMO SEGUNDA OCASIÓN':
            headerTableBancoDeDatos.value[1].show = true
            headerTableBancoDeDatos.value[2].show = true
            headerTableBancoDeDatos.value[7].show = true
            headerTableBancoDeDatos.value[9].show = true
            headerTableBancoDeDatos.value[11].show = true
            headerTableBancoDeDatos.value[10].show = true
            headerTableBancoDeDatos.value[12].show = true
            headerTableBancoDeDatos.value[13].show = true

            break;
        case 'PRESTAMO PARA TRABAJADOR INDEPENDIENTE':
            headerTableBancoDeDatos.value[1].show = true
            headerTableBancoDeDatos.value[2].show = true
            headerTableBancoDeDatos.value[7].show = true
            headerTableBancoDeDatos.value[8].show = true
            headerTableBancoDeDatos.value[9].show = true
            headerTableBancoDeDatos.value[10].show = true
            headerTableBancoDeDatos.value[11].show = true
            headerTableBancoDeDatos.value[12].show = true
            headerTableBancoDeDatos.value[13].show = true
            headerTableBancoDeDatos.value[14].show = true
            headerTableBancoDeDatos.value[15].show = true
            headerTableBancoDeDatos.value[16].show = true
            headerTableBancoDeDatos.value[17].show = true
            headerTableBancoDeDatos.value[18].show = true

            break;

        default:

            for (let index = 0; index < headerTableBancoDeDatos.value.length; index++) {
                const element = headerTableBancoDeDatos.value[index];
                element.show = true

            }
            break;
    }

}, { deep: true })
onMounted(() => {
    getUsuarios()
})
</script>
<template>
    <AppLayout title="Crear Creditos" :isCliente="isCliente">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Creditos
            </h2>
        </template>
        <div class="grid grid-cols-12 gap-2 ">
            <div class="col-span-9 grid grid-cols-10 gap-4 justify-center">
                <div class=" w-full col-span-2 relative">
                    <input type="text" class="border border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="Search"
                        v-model="searchTerm" @input="filterResults">

                    <ul v-if="filteredResults.length > 0"
                        class="mt-2 z-10 absolute mb-2 border border-gray-300 rounded-lg shadow-lg">
                        <li v-for="(result, r) in filteredResults" :key="r">
                            <div @click.prevent="selectResult(result)"
                                class="flex drop-shadow-md flex-row py-4 px-2 items-center border-2 m-1 rounded-lg  border-l-4 border-blue-400 hover:cursor-pointer">
                                <div class="mr-2">
                                    <svg width="40px" height="40px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                        <g id="avatar" transform="translate(-1407 -182)">
                                            <circle id="Ellipse_16" data-name="Ellipse 16" cx="15" cy="15" r="15"
                                                transform="translate(1408 183)" fill="#e8f7f9" stroke="#333"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                            <g id="Group_49" data-name="Group 49">
                                                <circle id="Ellipse_17" data-name="Ellipse 17" cx="4.565" cy="4.565"
                                                    r="4.565" transform="translate(1418.435 192.13)" fill="#fff1b6"
                                                    stroke="#333" stroke-miterlimit="10" stroke-width="2" />
                                                <path id="Path_53" data-name="Path 53"
                                                    d="M1423,213a14.928,14.928,0,0,0,9.4-3.323,9.773,9.773,0,0,0-18.808,0A14.928,14.928,0,0,0,1423,213Z"
                                                    fill="#fff1b6" stroke="#333" stroke-miterlimit="10" stroke-width="2" />
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <div class="w-full">
                                    <div class="text-lg font-semibold">{{ result.name }}</div>
                                    <span class="text-gray-500">{{ result.email }}</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class=" w-full col-span-5 flex justify-center max-h-[40px]">
                    <select v-model="ProductoSelected"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Selecciona el producto</option>
                        <option value="PRESTAMO EMERGENTE">PRESTAMO EMERGENTE</option>
                        <option value="PRESTAMO SEGUNDA OCASIÓN">PRESTAMO SEGUNDA OCASIÓN</option>
                        <option value="PRESTAMO DE PRIMERA OCASIÓN ">PRESTAMO DE PRIMERA OCASIÓN </option>
                        <option value="PRESTAMO PARA TRABAJADOR INDEPENDIENTE">PRESTAMO PARA TRABAJADOR INDEPENDIENTE
                        </option>
                        <option value="PRESTAMOS MAYOR QUE 20 MIL para cliente de 2a ocasión">PRESTAMOS MAYOR QUE 20 MIL
                            para cliente de 2a ocasión</option>
                        <option value="PRESTAMOS MAYOR QUE 50 MIL para cliente de 2a ocasión">PRESTAMOS MAYOR QUE 50 MIL
                            para cliente de 2a ocasión</option>
                    </select>
                </div>
                <div class=" w-full col-span-3 flex justify-around gap-2  max-h-[40px]">
                    <PrimaryButton class="flex gap-2" @click.prevent="consutarUsuario">
                        Consultar
                        <svg width="17px" height="17px" viewBox="0 -0.5 21 21">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Dribbble-Light-Preview" transform="translate(-219.000000, -280.000000)"
                                    fill="#ffffff">
                                    <g id="icons" transform="translate(56.000000, 160.000000)">
                                        <path
                                            d="M184,138.586 L182.5153,140 L176.57545,134.343 L178.06015,132.929 L184,138.586 Z M170.35,132 C167.45515,132 165.1,129.757 165.1,127 C165.1,124.243 167.45515,122 170.35,122 C173.24485,122 175.6,124.243 175.6,127 C175.6,129.757 173.24485,132 170.35,132 L170.35,132 Z M170.35,120 C166.2907,120 163,123.134 163,127 C163,130.866 166.2907,134 170.35,134 C174.4093,134 177.7,130.866 177.7,127 C177.7,123.134 174.4093,120 170.35,120 L170.35,120 Z"
                                            id="search_left-[#1506]">
                                        </path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </PrimaryButton>
                    <PrimaryButton class="flex gap-2 bg-blue-500">
                        Crear Credito
                        <!-- <svg width="17px" height="17px" viewBox="0 -0.5 21 21">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Dribbble-Light-Preview" transform="translate(-219.000000, -280.000000)"
                                    fill="#ffffff">
                                    <g id="icons" transform="translate(56.000000, 160.000000)">
                                        <path
                                            d="M184,138.586 L182.5153,140 L176.57545,134.343 L178.06015,132.929 L184,138.586 Z M170.35,132 C167.45515,132 165.1,129.757 165.1,127 C165.1,124.243 167.45515,122 170.35,122 C173.24485,122 175.6,124.243 175.6,127 C175.6,129.757 173.24485,132 170.35,132 L170.35,132 Z M170.35,120 C166.2907,120 163,123.134 163,127 C163,130.866 166.2907,134 170.35,134 C174.4093,134 177.7,130.866 177.7,127 C177.7,123.134 174.4093,120 170.35,120 L170.35,120 Z"
                                            id="search_left-[#1506]">
                                        </path>
                                    </g>
                                </g>
                            </g>
                        </svg> -->
                    </PrimaryButton>
                </div>
                <div class="bg-white col-span-10  max-w-[73rem]  shadow-md rounded my-6 overflow-x-scroll"
                    v-if="BancoDeDatos">
                    <table class=" w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 capitalize leading-normal relative">
                                <th class="py-3 w-12 px-3 text-center bg-gray-200 text-gray-600 text-xs"
                                    :class="{ ' sticky left-0 h-full bg-gray-100 w-32 ': h.fixed }"
                                    v-for="(h, n) in headerTableBancoDeDatos" :key="n" v-show="h.show">
                                    {{ h.header }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[0].show">
                                    <span>{{ BancoDeDatos.marca_temporal ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[1].show">
                                    <span>{{ BancoDeDatos.nombre_de_supervisort_ola ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[2].show">
                                    <span>{{ BancoDeDatos.ciudad ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[3].show">
                                    <span>{{ BancoDeDatos.colaborador_ola_o_cliente_ola_que_lo_referencia ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[4].show">
                                    <span>{{ BancoDeDatos.nombres ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[5].show">
                                    <span>{{ BancoDeDatos.apellido_paterno ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[6].show">
                                    <span>{{ BancoDeDatos.apellido_materno ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[7].show">
                                    <span>{{ BancoDeDatos.nombre_completo ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[8].show">
                                    <span>{{ BancoDeDatos.fecha_de_nacimiento ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[9].show">
                                    <span>{{ BancoDeDatos.direccion ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[10].show">
                                    <span>{{ BancoDeDatos.ine_o_identificacion_oficial ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[11].show">
                                    <span>{{ BancoDeDatos.comprobacion_domicilio_del_cliente ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[12].show">
                                    <span>{{ BancoDeDatos.cual_es_el_principal_fuente_de_ingreso_o_como_piensa_pagar ?? ''
                                    }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[13].show">
                                    <span>{{ BancoDeDatos.cual_es_el_producto_que_se_solicitando ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[14].show">
                                    <span>{{ BancoDeDatos.monto_dinero_solicitado ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[15].show">
                                    <span>{{ BancoDeDatos.cual_es_la_razon_por_la_que_tiene_esa_deuda ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[16].show">
                                    <span>{{ BancoDeDatos.hace_cuanto_tiene_ese_dinero ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[17].show">
                                    <span>{{ BancoDeDatos.a_que_instucion_le_debe_dinero ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[18].show">
                                    <span>{{ BancoDeDatos.cuenta_con_una_cuenta_bancaria ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[19].show">
                                    <span>{{ BancoDeDatos.si_respuesta_fue_si_en_banco_tiene_cuenta ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[20].show">
                                    <span>{{ BancoDeDatos.porque_no_ha_podido_pagar_su_deuda ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[21].show">
                                    <span>{{ BancoDeDatos.para_que_necesita_el_capital ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[22].show">
                                    <span>{{ BancoDeDatos.sus_proveedores_pueden_emitir_factura ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[23].show">
                                    <span>{{ BancoDeDatos.el_local_de_su_negocio_es_restado_o_propiedad_del_solicitante ??
                                        '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[24].show">
                                    <span>{{ BancoDeDatos.foto_del_local ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[25].show">
                                    <span>{{ BancoDeDatos.foto_del_local ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[26].show">
                                    <span>{{ BancoDeDatos.cual_es_el_numero_de_su_cuenta_tarjeta ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[27].show">
                                    <span>{{ BancoDeDatos.cual_es_el_numero_de_su_cuenta_clabe ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[28].show">
                                    <span>{{ BancoDeDatos.cual_es_el_numero_de_su_cuenta_clabe ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[29].show">
                                    <span>{{ BancoDeDatos.si_no_tiene_cuenta_imagen_de_la_cuenta_del_banco ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[30].show">
                                    <span>{{ BancoDeDatos.escala_de_referencia_gerente_de_cliente_ola ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[31].show">
                                    <span>{{ BancoDeDatos.correo_electronico_del_solicitante ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[32].show">
                                    <span>{{ BancoDeDatos.numero_de_contacto_ws_solicitante ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[33].show">
                                    <span>{{ BancoDeDatos.quien_lo_refiere_que_tipo_de_relacion_tiene ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[34].show">
                                    <span>{{ BancoDeDatos.tiene_redes_sociales ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[35].show">
                                    <span>{{ BancoDeDatos.desde_cuando_lo_conoce_porquer_refiere ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[36].show">
                                    <span>{{ BancoDeDatos.aprobacion_operativa ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[37].show">
                                    <span>{{ BancoDeDatos.aprobacion_legal ?? '' }}</span>
                                </td>
                                <td class="py-3 px-6 text-center" v-show="headerTableBancoDeDatos[38].show">
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
            </div>
            <div class=" col-span-3">
                
            </div>
        </div>
        <div class="flex mt-8 justify-center rounded-lg bg-white  dark:bg-neutral-700 w-full" v-if="UserSelected">
            <div class="w-full">
                
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
                                v-for="(solicitud, s) in UserSelected.has_many_credits_requests" :key="s">
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
    </AppLayout>
</template>