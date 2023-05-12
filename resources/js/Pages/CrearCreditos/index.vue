<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
// import PrimaryButton from '../../Components/PrimaryButton.vue'
import listExpacionPanel from "../../Components/listWithExpancionPanel.vue";
import moment from 'moment'


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
const modelComentario = ref({
    comentario: '',
    user_id: null
})
const dataList = ref([
    { titulo: '¿Cuál es su principal fuente de ingreso? o ¿Cómo piensa pagar lo que está solicitando?	', toggle: false },
    { titulo: '¿Cuál es el producto que se está solicitando?', toggle: false },
    { titulo: '¿Cuál es la razón de tu crédito?	', toggle: false },
    { titulo: '¿En cuánto tiempo piensas pagar tu crédito?	', toggle: false },
    { titulo: 'Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?	', toggle: false },
    { titulo: '¿Cuál es el número de cuenta CLABE?	', toggle: false },
    { titulo: '¿Cuál es el número de su cuenta/tarjeta?	', toggle: false },
    { titulo: '¿Tú eres trabajador independiente?	', toggle: false },
    { titulo: 'Tiene redes sociales? Como lo encontramos en las Redes Sociales? (LinkedIn, Facebook, instagram, etc.)	', toggle: false },
    { titulo: 'Desde cuando lo conoce? Porque lo Refiere? ', toggle: false },

])
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
    BancoDeDatos.value = UserSelected.value.has_one_document ?? false
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

const clearUsuario = () => {
    UserSelected.value = false
    searchTerm.value = ''
}

const addcomentario = async () => {
    try {
        modelComentario.value.user_id = UserSelected.value.id
        await axios.post(route('add.comentario'), modelComentario.value)
        modelComentario.value.comentario = ''
        getUsuarios()
    } catch (error) {
        console.log(error)
    }
}

watch(() => ProductoSelected.value, (newValue, oldValue) => {

    for (let index = 0; index < headerTableBancoDeDatos.value.length; index++) {
        const element = headerTableBancoDeDatos.value[index];
        element.show = false

    }

    if (newValue == 'PRESTAMO SEGUNDA OCASIÓN') {
        headerTableBancoDeDatos.value[7].show = true
        headerTableBancoDeDatos.value[9].show = true
        headerTableBancoDeDatos.value[11].show = true
        headerTableBancoDeDatos.value[10].show = true
        headerTableBancoDeDatos.value[12].show = true
        headerTableBancoDeDatos.value[13].show = true
    }

    if (newValue == 'PRESTAMO PARA TRABAJADOR INDEPENDIENTE') {
        headerTableBancoDeDatos.value[7].show = true
        headerTableBancoDeDatos.value[8].show = true
        // headerTableBancoDeDatos.value[9].show = true
        headerTableBancoDeDatos.value[10].show = true
        headerTableBancoDeDatos.value[11].show = true
        headerTableBancoDeDatos.value[12].show = true // p
        headerTableBancoDeDatos.value[13].show = true // p
        // headerTableBancoDeDatos.value[14].show = true
        headerTableBancoDeDatos.value[15].show = true // p
        // headerTableBancoDeDatos.value[16].show = true
        // headerTableBancoDeDatos.value[17].show = true
        // headerTableBancoDeDatos.value[18].show = true
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
        <div class="  grid grid-cols-12  justify-start">
            <div class=" max-w-[240px] flex flex-col col-span-3 ">
                <div class="bg-white p-2 rounded-lg text-center">
                    <img src="https://i.ytimg.com/vi/mtXQ-m2xPEY/maxresdefault.jpg" alt=""
                        class="h-20 w-full object-cover content-center rounded-t-lg" />
                    <div class="flex justify-center">
                        <svg class="w-20 h-20 rounded-full object-cover content-center -mt-10 border-4 border-white"
                            width="20px" height="20px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
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

                    <div class=" w-full col-span-2 relative">
                        <input type="text" class="border  border-gray-300 py-2 px-4 rounded-lg w-full" placeholder="Search"
                            v-model="searchTerm" @input="filterResults">
                        <span class="flex absolute right-1 top-1/2 -mt-2 mr-1 items-center" @click.prevent="clearUsuario"
                            v-if="UserSelected">
                            <svg fill="#000000" width="15px" height="15px" viewBox="0 0 24 24"
                                class="hover:cursor-pointer hover:text-red-700">
                                <path class="fill-current "
                                    d="M1,20a1,1,0,0,0,1,1h8a1,1,0,0,0,0-2H3.071A7.011,7.011,0,0,1,10,13a5.044,5.044,0,1,0-3.377-1.337A9.01,9.01,0,0,0,1,20ZM10,5A3,3,0,1,1,7,8,3,3,0,0,1,10,5Zm12.707,9.707L20.414,17l2.293,2.293a1,1,0,1,1-1.414,1.414L19,18.414l-2.293,2.293a1,1,0,0,1-1.414-1.414L17.586,17l-2.293-2.293a1,1,0,0,1,1.414-1.414L19,15.586l2.293-2.293a1,1,0,0,1,1.414,1.414Z" />
                            </svg>
                        </span>
                        <span class="flex absolute right-1 top-1/2 -mt-2 mr-1 items-center" @click.prevent="clearUsuario"
                            v-if="UserSelected">
                            <svg width="800px" height="800px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="m199.04 672.64 193.984 112 224-387.968-193.92-112-224 388.032zm-23.872 60.16 32.896 148.288 144.896-45.696L175.168 732.8zM455.04 229.248l193.92 112 56.704-98.112-193.984-112-56.64 98.112zM104.32 708.8l384-665.024 304.768 175.936L409.152 884.8h.064l-248.448 78.336L104.32 708.8zm384 254.272v-64h448v64h-448z"
                                    fill="#000000" />
                            </svg>
                        </span>
                        <ul v-if="filteredResults.length > 0"
                            class="mt-2 z-10 bg-white absolute mb-2 border border-gray-300 rounded-lg shadow-lg">
                            <li v-for="(result, r) in filteredResults" :key="r">
                                <div @click.prevent="selectResult(result)"
                                    class="flex drop-shadow-md flex-row py-4 px-2 items-center border-2 m-1 rounded-lg  border-l-4 border-blue-400 hover:cursor-pointer">
                                    <div class="mr-2">
                                        <svg width="40px" height="40px" viewBox="0 0 32 32"
                                            xmlns="http://www.w3.org/2000/svg">
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
                                                        fill="#fff1b6" stroke="#333" stroke-miterlimit="10"
                                                        stroke-width="2" />
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

                    <div class="relative flex flex-col mt-2" v-if="UserSelected">

                        <h1 class="text-right text-[10px]  px-1 text-gray-400 hover:bg-gray-100">Cliente id: {{
                            UserSelected.id
                        }}</h1>
                        <h1 class="text-right text-[10px]  text-gray-400 hover:bg-gray-100">Creado: {{
                            UserSelected.created_at.substr(0, 10) }}</h1>
                        <h1 class="text-left font-bold  px-1 text-gray-700  flex justify-between hover:bg-gray-100">
                            {{ BancoDeDatos.nombre_completo }}
                            <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" class="ml-1 min-w-max"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="User / User_Card_ID">
                                    <path id="Vector"
                                        d="M6 18C6.06366 18 6.12926 18 6.19691 18H12M6 18C5.01173 17.9992 4.49334 17.9868 4.0918 17.7822C3.71547 17.5905 3.40973 17.2837 3.21799 16.9074C3 16.4796 3 15.9203 3 14.8002V9.2002C3 8.08009 3 7.51962 3.21799 7.0918C3.40973 6.71547 3.71547 6.40973 4.0918 6.21799C4.51962 6 5.08009 6 6.2002 6H17.8002C18.9203 6 19.4796 6 19.9074 6.21799C20.2837 6.40973 20.5905 6.71547 20.7822 7.0918C21 7.5192 21 8.07899 21 9.19691V14.8031C21 15.921 21 16.48 20.7822 16.9074C20.5905 17.2837 20.2837 17.5905 19.9074 17.7822C19.48 18 18.921 18 17.8031 18H12M6 18C6.00004 16.8954 7.34317 16 9 16C10.6569 16 12 16.8954 12 18M6 18C6 18 6 17.9999 6 18ZM18 14H14M18 11H15M9 13C7.89543 13 7 12.1046 7 11C7 9.89543 7.89543 9 9 9C10.1046 9 11 9.89543 11 11C11 12.1046 10.1046 13 9 13Z"
                                        stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </g>
                            </svg>
                        </h1>
                        <p class="text-gray-500 mt-1 px-1 text-left flex justify-between hover:bg-gray-100">
                            {{ BancoDeDatos.correo_electronico_del_solicitante }}
                            <svg height="15px" width="15px" version="1.1" viewBox="0 0 512 512" xml:space="preserve"
                                class="ml-1 min-w-max">
                                <g>
                                    <path class="st0" d="M510.746,110.361c-2.128-10.754-6.926-20.918-13.926-29.463c-1.422-1.794-2.909-3.39-4.535-5.009
		c-12.454-12.52-29.778-19.701-47.531-19.701H67.244c-17.951,0-34.834,7-47.539,19.708c-1.608,1.604-3.099,3.216-4.575,5.067
		c-6.97,8.509-11.747,18.659-13.824,29.428C0.438,114.62,0,119.002,0,123.435v265.137c0,9.224,1.874,18.206,5.589,26.745
		c3.215,7.583,8.093,14.772,14.112,20.788c1.516,1.509,3.022,2.901,4.63,4.258c12.034,9.966,27.272,15.45,42.913,15.45h377.51
		c15.742,0,30.965-5.505,42.967-15.56c1.604-1.298,3.091-2.661,4.578-4.148c5.818-5.812,10.442-12.49,13.766-19.854l0.438-1.05
		c3.646-8.377,5.497-17.33,5.497-26.628V123.435C512,119.06,511.578,114.649,510.746,110.361z M34.823,99.104
		c0.951-1.392,2.165-2.821,3.714-4.382c7.689-7.685,17.886-11.914,28.706-11.914h377.51c10.915,0,21.115,4.236,28.719,11.929
		c1.313,1.327,2.567,2.8,3.661,4.272l2.887,3.88l-201.5,175.616c-6.212,5.446-14.21,8.443-22.523,8.443
		c-8.231,0-16.222-2.99-22.508-8.436L32.19,102.939L34.823,99.104z M26.755,390.913c-0.109-0.722-0.134-1.524-0.134-2.341V128.925
		l156.37,136.411L28.199,400.297L26.755,390.913z M464.899,423.84c-6.052,3.492-13.022,5.344-20.145,5.344H67.244
		c-7.127,0-14.094-1.852-20.142-5.344l-6.328-3.668l159.936-139.379l17.528,15.246c10.514,9.128,23.922,14.16,37.761,14.16
		c13.89,0,27.32-5.032,37.827-14.16l17.521-15.253L471.228,420.18L464.899,423.84z M485.372,388.572
		c0,0.803-0.015,1.597-0.116,2.304l-1.386,9.472L329.012,265.409l156.36-136.418V388.572z" />
                                </g>
                            </svg>
                        </p>
                        <p class="text-gray-500 mt-1 px-1 text-left flex justify-between hover:bg-gray-100"
                            v-if="BancoDeDatos">
                            {{ BancoDeDatos.numero_de_contacto_ws }}
                            <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" class="ml-1 min-w-max"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15 3C16.5315 3.17014 17.9097 3.91107 19 5C20.0903 6.08893 20.8279 7.46869 21 9M14.5 6.5C15.2372 6.64382 15.9689 6.96892 16.5 7.5C17.0311 8.03108 17.3562 8.76284 17.5 9.5M8.20049 15.799C1.3025 8.90022 2.28338 5.74115 3.01055 4.72316C3.10396 4.55862 5.40647 1.11188 7.87459 3.13407C14.0008 8.17945 6.5 8 11.3894 12.6113C16.2788 17.2226 15.8214 9.99995 20.8659 16.1249C22.8882 18.594 19.4413 20.8964 19.2778 20.9888C18.2598 21.717 15.0995 22.6978 8.20049 15.799Z"
                                    stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </p>
                        <p class="text-gray-500 mt-1 px-1 text-left flex justify-between hover:bg-gray-100"
                            v-if="BancoDeDatos">
                            {{ BancoDeDatos.direccion }}
                            <svg width="15px" height="15px" viewBox="0 0 1024 1024" fill="#000000" class="ml-1 min-w-max"
                                version="1.1" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M512 1012.8c-253.6 0-511.2-54.4-511.2-158.4 0-92.8 198.4-131.2 283.2-143.2h3.2c12 0 22.4 8.8 24 20.8 0.8 6.4-0.8 12.8-4.8 17.6-4 4.8-9.6 8.8-16 9.6-176.8 25.6-242.4 72-242.4 96 0 44.8 180.8 110.4 463.2 110.4s463.2-65.6 463.2-110.4c0-24-66.4-70.4-244.8-96-6.4-0.8-12-4-16-9.6-4-4.8-5.6-11.2-4.8-17.6 1.6-12 12-20.8 24-20.8h3.2c85.6 12 285.6 50.4 285.6 143.2 0.8 103.2-256 158.4-509.6 158.4z m-16.8-169.6c-12-11.2-288.8-272.8-288.8-529.6 0-168 136.8-304.8 304.8-304.8S816 145.6 816 313.6c0 249.6-276.8 517.6-288.8 528.8l-16 16-16-15.2zM512 56.8c-141.6 0-256.8 115.2-256.8 256.8 0 200.8 196 416 256.8 477.6 61.6-63.2 257.6-282.4 257.6-477.6C768.8 172.8 653.6 56.8 512 56.8z m0 392.8c-80 0-144.8-64.8-144.8-144.8S432 160 512 160c80 0 144.8 64.8 144.8 144.8 0 80-64.8 144.8-144.8 144.8zM512 208c-53.6 0-96.8 43.2-96.8 96.8S458.4 401.6 512 401.6c53.6 0 96.8-43.2 96.8-96.8S564.8 208 512 208z"
                                    fill="" />
                            </svg>
                        </p>
                        <p class="text-gray-500 mt-1 px-1 text-left flex justify-between hover:bg-gray-100"
                            v-if="BancoDeDatos">
                            {{ BancoDeDatos.principal_fuente_de_ingreso }}
                            <svg width="15px" height="15px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                                class="ml-1 min-w-max">
                                <path fill-rule="evenodd"
                                    d="M5.508 2.466L4.356 5H2.5A1.5 1.5 0 001 6.5v1.882l.503.251a19 19 0 0016.994 0L19 8.382V6.5A1.5 1.5 0 0017.5 5h-1.856l-1.152-2.534A2.5 2.5 0 0012.216 1H7.784a2.5 2.5 0 00-2.276 1.466zM7.784 3a.5.5 0 00-.455.293L6.553 5h6.894l-.776-1.707A.5.5 0 0012.216 3H7.784z"
                                    fill="#5C5F62" />
                                <path
                                    d="M19 10.613a20.986 20.986 0 01-8 2.003V14a1 1 0 01-2 0v-1.384c-2.74-.131-5.46-.798-8-2.003V17.5A1.5 1.5 0 002.5 19h15a1.5 1.5 0 001.5-1.5v-6.887z"
                                    fill="#5C5F62" />
                            </svg>
                        </p>
                    </div>

                    <div class=" w-full my-2 flex justify-center " v-if="UserSelected">
                        <select v-model="ProductoSelected"
                            class="bg-gray-50 border text-[9px] w-[225px] border-gray-300 text-gray-900 h-fit rounded-lg   pr-6 ">
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


                    <div class="flex relative" v-if="UserSelected">
                        <input class="pt-2 pb-2 pl-3  h-8 mt-2 bg-slate-100 dark:bg-slate-600 rounded-lg " type="text"
                            placeholder="Write a comment" v-model="modelComentario.comentario" />
                        <span class="flex absolute right-1 top-2/4 -mt-1 items-center hover:cursor-pointer"
                            @click.prevent="addcomentario">
                            <svg class="fill-blue-500 dark:fill-slate-50 self-center align-middle "
                                style="width: 17px; height: 17px;" viewBox="0 0 24 24">
                                <path d="M2,21L23,12L2,3V10L17,12L2,14V21Z"></path>
                            </svg>
                        </span>
                    </div>
                    <!-- comentarios -->
                    <div class="pt-6" v-if="UserSelected">
                        <!-- Comment row -->
                        <div class=" pb-4" v-for="(comentario, c ) in UserSelected.has_many_comentarios" :key="c">
                            <div class="flex flex-col text-right text-xs">
                                <div class="flex gap-2">
                                    <a class="inline-block text-base font-bold mr-2" href="#">{{
                                        comentario.who_did_comment.name
                                    }}</a>
                                    <span
                                        class="text-slate-500 dark:text-slate-300 text-[9px] self-center align-middle  ">25
                                        minutes ago</span>
                                </div>
                                <div class="">
                                    <p class="text-right">{{ comentario.comentario }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- End comments row -->

                        <!-- More comments -->
                        <div class="w-full">
                            <a href="#"
                                class="py-3 px-4 w-full block bg-slate-100 dark:bg-slate-700 text-center rounded-lg font-medium hover:bg-slate-200 dark:hover:bg-slate-600 transition ease-in-out delay-75">Show
                                more comments</a>
                        </div>
                        <!-- End More comments -->

                    </div>
                </div>
                <div v-if="UserSelected"
                    class="max-w-[240] w-[240px] mt-2 py-8 px-6 text-gray-600 rounded-xl border border-gray-200 bg-white">
                    <svg class="w-40 m-auto" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M27.9985 2.84071C31.2849 2.84071 34.539 3.488 37.5752 4.74562C40.6113 6.00324 43.3701 7.84657 45.6938 10.1703C48.0176 12.4941 49.861 15.2529 51.1186 18.289C52.3762 21.3252 53.0235 24.5793 53.0235 27.8657C53.0235 31.152 52.3762 34.4061 51.1186 37.4423C49.861 40.4785 48.0176 43.2372 45.6938 45.561C43.3701 47.8848 40.6113 49.7281 37.5752 50.9857C34.539 52.2433 31.2849 52.8906 27.9985 52.8906C24.7122 52.8906 21.4581 52.2433 18.4219 50.9857C15.3857 49.7281 12.627 47.8848 10.3032 45.561C7.97943 43.2372 6.1361 40.4785 4.87848 37.4423C3.62086 34.4061 2.97357 31.152 2.97357 27.8657C2.97357 24.5793 3.62086 21.3252 4.87848 18.289C6.13611 15.2529 7.97943 12.4941 10.3032 10.1703C12.627 7.84656 15.3857 6.00324 18.4219 4.74562C21.4581 3.488 24.7122 2.84071 27.9985 2.84071L27.9985 2.84071Z"
                            stroke="#e4e4f2" stroke-width="3" />
                        <path
                            d="M27.301 2.50958C33.0386 2.35225 38.6614 4.13522 43.26 7.57004C47.8585 11.0049 51.1637 15.8907 52.641 21.437C54.1182 26.9834 53.6811 32.8659 51.4002 38.133C49.1194 43.4001 45.1283 47.7437 40.0726 50.4611C35.0169 53.1785 29.1923 54.1108 23.541 53.1071C17.8897 52.1034 12.7423 49.2225 8.93145 44.9305C5.12062 40.6384 2.86926 35.1861 2.54159 29.4558C2.21391 23.7254 3.82909 18.0521 7.12582 13.3536"
                            stroke="url(#paint0_linear_622:13696)" stroke-width="5" stroke-linecap="round" />
                        <path
                            d="M13.3279 30.7467C13.3912 29.48 13.8346 28.5047 14.6579 27.8207C15.4939 27.124 16.5896 26.7757 17.9449 26.7757C18.8696 26.7757 19.6612 26.9404 20.3199 27.2697C20.9786 27.5864 21.4726 28.0234 21.8019 28.5807C22.1439 29.1254 22.3149 29.746 22.3149 30.4427C22.3149 31.2407 22.1059 31.9184 21.6879 32.4757C21.2826 33.0204 20.7949 33.3877 20.2249 33.5777V33.6537C20.9596 33.8817 21.5296 34.287 21.9349 34.8697C22.3529 35.4524 22.5619 36.1997 22.5619 37.1117C22.5619 37.8717 22.3846 38.5494 22.0299 39.1447C21.6879 39.74 21.1749 40.2087 20.4909 40.5507C19.8196 40.88 19.0089 41.0447 18.0589 41.0447C16.6276 41.0447 15.4622 40.6837 14.5629 39.9617C13.6636 39.2397 13.1886 38.1757 13.1379 36.7697H15.7219C15.7472 37.3904 15.9562 37.8907 16.3489 38.2707C16.7542 38.638 17.3052 38.8217 18.0019 38.8217C18.6479 38.8217 19.1419 38.6444 19.4839 38.2897C19.8386 37.9224 20.0159 37.4537 20.0159 36.8837C20.0159 36.1237 19.7752 35.579 19.2939 35.2497C18.8126 34.9204 18.0652 34.7557 17.0519 34.7557H16.5009V32.5707H17.0519C18.8506 32.5707 19.7499 31.969 19.7499 30.7657C19.7499 30.221 19.5852 29.7967 19.2559 29.4927C18.9392 29.1887 18.4769 29.0367 17.8689 29.0367C17.2736 29.0367 16.8112 29.2014 16.4819 29.5307C16.1652 29.8474 15.9816 30.2527 15.9309 30.7467H13.3279ZM25.6499 37.9477C26.8659 36.9344 27.8349 36.092 28.5569 35.4207C29.2789 34.7367 29.8806 34.0274 30.3619 33.2927C30.8433 32.558 31.0839 31.836 31.0839 31.1267C31.0839 30.4807 30.9319 29.974 30.6279 29.6067C30.3239 29.2394 29.8553 29.0557 29.2219 29.0557C28.5886 29.0557 28.1009 29.271 27.7589 29.7017C27.4169 30.1197 27.2396 30.696 27.2269 31.4307H24.6429C24.6936 29.9107 25.1433 28.758 25.9919 27.9727C26.8533 27.1874 27.9426 26.7947 29.2599 26.7947C30.7039 26.7947 31.8123 27.181 32.5849 27.9537C33.3576 28.7137 33.7439 29.7207 33.7439 30.9747C33.7439 31.9627 33.4779 32.9064 32.9459 33.8057C32.4139 34.705 31.8059 35.4904 31.1219 36.1617C30.4379 36.8204 29.5449 37.6184 28.4429 38.5557H34.0479V40.7597H24.6619V38.7837L25.6499 37.9477Z"
                            fill="currentColor" />
                        <path
                            d="M36.1948 28.8842C36.1948 29.4438 36.2557 29.8634 36.3775 30.1432C36.4992 30.423 36.6967 30.5628 36.9699 30.5628C37.5097 30.5628 37.7796 30.0033 37.7796 28.8842C37.7796 27.7717 37.5097 27.2155 36.9699 27.2155C36.6967 27.2155 36.4992 27.3537 36.3775 27.6302C36.2557 27.9067 36.1948 28.3247 36.1948 28.8842ZM38.456 28.8842C38.456 29.6347 38.3293 30.2024 38.0758 30.5875C37.8257 30.9693 37.457 31.1602 36.9699 31.1602C36.5091 31.1602 36.1504 30.9644 35.8936 30.5727C35.6402 30.181 35.5135 29.6182 35.5135 28.8842C35.5135 28.1371 35.6352 27.5742 35.8788 27.1957C36.1257 26.8172 36.4894 26.6279 36.9699 26.6279C37.4472 26.6279 37.8142 26.8238 38.0709 27.2155C38.3276 27.6071 38.456 28.1634 38.456 28.8842ZM40.5395 31.7774C40.5395 32.3402 40.6003 32.7615 40.7221 33.0413C40.8439 33.3178 41.043 33.456 41.3195 33.456C41.596 33.456 41.8001 33.3194 41.9317 33.0462C42.0634 32.7697 42.1292 32.3468 42.1292 31.7774C42.1292 31.2145 42.0634 30.7982 41.9317 30.5283C41.8001 30.2551 41.596 30.1185 41.3195 30.1185C41.043 30.1185 40.8439 30.2551 40.7221 30.5283C40.6003 30.7982 40.5395 31.2145 40.5395 31.7774ZM42.8056 31.7774C42.8056 32.5245 42.6789 33.0906 42.4254 33.4757C42.1753 33.8575 41.8067 34.0484 41.3195 34.0484C40.8521 34.0484 40.4917 33.8526 40.2383 33.4609C39.9881 33.0693 39.8631 32.5081 39.8631 31.7774C39.8631 31.0302 39.9849 30.4674 40.2284 30.0889C40.4753 29.7104 40.839 29.5211 41.3195 29.5211C41.7869 29.5211 42.1506 29.7153 42.4106 30.1037C42.6739 30.4888 42.8056 31.0467 42.8056 31.7774ZM41.5318 26.7316L37.5278 33.9497H36.8021L40.8061 26.7316H41.5318Z"
                            fill="white" />
                        <path
                            d="M23.5776 18.4198H25.5424V22.8407H23.5776V18.4198ZM30.4545 16.455H32.4193V22.8407H30.4545V16.455ZM27.0161 13.5078H28.9809V22.8407H27.0161V13.5078Z"
                            fill="#6A6A9F" />
                        <defs>
                            <linearGradient id="paint0_linear_622:13696" x1="5.54791e-07" y1="42.0001" x2="54.6039"
                                y2="41.9535" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#E323FF" />
                                <stop offset="1" stop-color="#7517F8" />
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="mt-6">
                        <h5 class="text-xl text-gray-700 text-center">Ask to customize</h5>
                        <div class="mt-2 flex justify-center gap-4">
                            <h3 class="text-3xl font-bold text-gray-700">28</h3>
                            <div class="flex items-end gap-1 text-green-500">
                                <svg class="w-3" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.00001 0L12 8H-3.05176e-05L6.00001 0Z" fill="currentColor" />
                                </svg>
                                <span>2%</span>
                            </div>
                        </div>
                        <span class="block text-center text-gray-500">Compared to last week 13</span>
                    </div>
                    <table class="mt-6 -mb-2 w-full text-gray-600">
                        <tbody>
                            <tr>
                                <td class="py-2">Tailored ui</td>
                                <td class="text-gray-500">896</td>
                                <td>
                                    <svg class="w-16 ml-auto" viewBox="0 0 68 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.4" width="17" height="21" rx="1" fill="#e4e4f2" />
                                        <rect opacity="0.4" x="19" width="14" height="21" rx="1" fill="#e4e4f2" />
                                        <rect opacity="0.4" x="35" width="14" height="21" rx="1" fill="#e4e4f2" />
                                        <rect opacity="0.4" x="51" width="17" height="21" rx="1" fill="#e4e4f2" />
                                        <path
                                            d="M0 7C8.62687 7 7.61194 16 17.7612 16C27.9104 16 25.3731 9 34 9C42.6269 9 44.5157 5 51.2537 5C57.7936 5 59.3731 14.5 68 14.5"
                                            stroke="url(#paint0_linear_622:13631)" stroke-width="2"
                                            stroke-linejoin="round" />
                                        <defs>
                                            <linearGradient id="paint0_linear_622:13631" x1="68" y1="7.74997" x2="1.69701"
                                                y2="8.10029" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#E323FF" />
                                                <stop offset="1" stop-color="#7517F8" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2">Customize</td>
                                <td class="text-gray-500">1200</td>
                                <td>
                                    <svg class="w-16 ml-auto" viewBox="0 0 68 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.4" width="17" height="21" rx="1" fill="#e4e4f2" />
                                        <rect opacity="0.4" x="19" width="14" height="21" rx="1" fill="#e4e4f2" />
                                        <rect opacity="0.4" x="35" width="14" height="21" rx="1" fill="#e4e4f2" />
                                        <rect opacity="0.4" x="51" width="17" height="21" rx="1" fill="#e4e4f2" />
                                        <path
                                            d="M0 12.929C8.69077 12.929 7.66833 9.47584 17.8928 9.47584C28.1172 9.47584 25.5611 15.9524 34.2519 15.9524C42.9426 15.9524 44.8455 10.929 51.6334 10.929C58.2217 10.929 59.3092 5 68 5"
                                            stroke="url(#paint0_linear_622:13640)" stroke-width="2"
                                            stroke-linejoin="round" />
                                        <defs>
                                            <linearGradient id="paint0_linear_622:13640" x1="34" y1="5" x2="34" y2="15.9524"
                                                gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#8AFF6C" />
                                                <stop offset="1" stop-color="#02C751" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2">Other</td>
                                <td class="text-gray-500">12</td>
                                <td>
                                    <svg class="w-16 ml-auto" viewBox="0 0 68 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.4" width="17" height="21" rx="1" fill="#e4e4f2" />
                                        <rect opacity="0.4" x="19" width="14" height="21" rx="1" fill="#e4e4f2" />
                                        <rect opacity="0.4" x="35" width="14" height="21" rx="1" fill="#e4e4f2" />
                                        <rect opacity="0.4" x="51" width="17" height="21" rx="1" fill="#e4e4f2" />
                                        <path
                                            d="M0 6C8.62687 6 6.85075 12.75 17 12.75C27.1493 12.75 25.3731 9 34 9C42.6269 9 42.262 13.875 49 13.875C55.5398 13.875 58.3731 6 67 6"
                                            stroke="url(#paint0_linear_622:13649)" stroke-width="2"
                                            stroke-linejoin="round" />
                                        <defs>
                                            <linearGradient id="paint0_linear_622:13649" x1="67" y1="7.96873" x2="1.67368"
                                                y2="8.44377" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#FFD422" />
                                                <stop offset="1" stop-color="#FF7D05" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="w-full flex flex-col mr-4 col-span-9 h-fit" v-show="BancoDeDatos && UserSelected">
                <div class='flex mb-2 w-full gap-4 text-[9px]'>
                    <div class="rounded-xl w-full border p-5 shadow-md  bg-white">
                        <div class="flex w-full items-center justify-between border-b pb-3">
                            <div class="flex items-center space-x-3">

                                <div class="text-lg font-bold text-slate-700">{{ BancoDeDatos.nombre_supervisor_ola }}
                                </div>
                            </div>
                            <div class="flex items-center space-x-8 gap-4">
                                Supervisor Ola
                                <button class="rounded-2xl border bg-neutral-100 px-3 py-1 text-xs font-semibold">
                                    ver
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-xl w-full border p-5 shadow-md  bg-white">
                        <div class="flex w-full items-center justify-between border-b pb-3">
                            <div class="flex items-center space-x-3">
                                <div class="text-lg font-bold text-slate-700">
                                    {{ BancoDeDatos.nombre_telefono_referencia_personal }}
                                </div>
                            </div>
                            <div class="flex items-center space-x-8 gap-4">
                                Ref. Personal
                                <button class="rounded-2xl border bg-neutral-100 px-3 py-1 text-xs font-semibold">
                                    ver
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3 bg-white">
                    <header class="px-5 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800">Customers</h2>
                    </header>
                    <div class="overflow-x-auto">
                        <table class="table-auto  w-full">
                            <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">

                                <tr class="bg-gray-200 text-gray-600 capitalize leading-normal relative p-2">
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
                                        <span>{{ BancoDeDatos.colaborador_ola_o_cliente_ola_que_lo_referencia ?? ''
                                        }}</span>
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
                                        <span>{{ moment(BancoDeDatos.fecha_de_nacimiento).format('l') ?? '' }}</span>
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
                                        <span>{{ BancoDeDatos.cual_es_el_principal_fuente_de_ingreso_o_como_piensa_pagar ??
                                            ''
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
                                        <span>{{ BancoDeDatos.el_local_de_su_negocio_es_restado_o_propiedad_del_solicitante
                                            ??
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
                                        <span>{{ BancoDeDatos.si_no_tiene_cuenta_imagen_de_la_cuenta_del_banco ?? ''
                                        }}</span>
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
                <div class=" flex gap-2 mt-2">
                    <form class=" bg-white mb-2 max-w-[350px] py-6 px-9">
                        <div class="mb-6 pt-4">
                            <label class="mb-5 block text-xl font-semibold text-[#07074D]">
                                Upload File
                            </label>

                            <div class="mb-8">
                                <input type="file" name="file" id="file" class="sr-only" />
                                <label for="file"
                                    class="relative flex min-h-[200px] items-center justify-center rounded-md border border-dashed border-[#e0e0e0] p-12 text-center">
                                    <div>
                                        <span class="mb-2 block text-xl font-semibold text-[#07074D]">
                                            Drop files here
                                        </span>
                                        <span class="mb-2 block text-base font-medium text-[#6B7280]">
                                            Or
                                        </span>
                                        <span
                                            class="inline-flex rounded border border-[#e0e0e0] py-2 px-7 text-base font-medium text-[#07074D]">
                                            Browse
                                        </span>
                                    </div>
                                </label>
                            </div>

                            <div class="mb-5 rounded-md bg-[#F5F7FB] py-4 px-8">
                                <div class="flex items-center justify-between">
                                    <span class="truncate pr-3 text-base font-medium text-[#07074D]">
                                        banner-design.png
                                    </span>
                                    <button class="text-[#07074D]">
                                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M0.279337 0.279338C0.651787 -0.0931121 1.25565 -0.0931121 1.6281 0.279338L9.72066 8.3719C10.0931 8.74435 10.0931 9.34821 9.72066 9.72066C9.34821 10.0931 8.74435 10.0931 8.3719 9.72066L0.279337 1.6281C-0.0931125 1.25565 -0.0931125 0.651788 0.279337 0.279338Z"
                                                fill="currentColor" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M0.279337 9.72066C-0.0931125 9.34821 -0.0931125 8.74435 0.279337 8.3719L8.3719 0.279338C8.74435 -0.0931127 9.34821 -0.0931123 9.72066 0.279338C10.0931 0.651787 10.0931 1.25565 9.72066 1.6281L1.6281 9.72066C1.25565 10.0931 0.651787 10.0931 0.279337 9.72066Z"
                                                fill="currentColor" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-5 rounded-md bg-[#F5F7FB] py-4 px-8">
                                <div class="flex items-center justify-between">
                                    <span class="truncate pr-3 text-base font-medium text-[#07074D]">
                                        banner-design.png
                                    </span>
                                    <button class="text-[#07074D]">
                                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M0.279337 0.279338C0.651787 -0.0931121 1.25565 -0.0931121 1.6281 0.279338L9.72066 8.3719C10.0931 8.74435 10.0931 9.34821 9.72066 9.72066C9.34821 10.0931 8.74435 10.0931 8.3719 9.72066L0.279337 1.6281C-0.0931125 1.25565 -0.0931125 0.651788 0.279337 0.279338Z"
                                                fill="currentColor" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M0.279337 9.72066C-0.0931125 9.34821 -0.0931125 8.74435 0.279337 8.3719L8.3719 0.279338C8.74435 -0.0931127 9.34821 -0.0931123 9.72066 0.279338C10.0931 0.651787 10.0931 1.25565 9.72066 1.6281L1.6281 9.72066C1.25565 10.0931 0.651787 10.0931 0.279337 9.72066Z"
                                                fill="currentColor" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-5 rounded-md bg-[#F5F7FB] py-4 px-8">
                                <div class="flex items-center justify-between">
                                    <span class="truncate pr-3 text-base font-medium text-[#07074D]">
                                        banner-design.png
                                    </span>
                                    <button class="text-[#07074D]">
                                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M0.279337 0.279338C0.651787 -0.0931121 1.25565 -0.0931121 1.6281 0.279338L9.72066 8.3719C10.0931 8.74435 10.0931 9.34821 9.72066 9.72066C9.34821 10.0931 8.74435 10.0931 8.3719 9.72066L0.279337 1.6281C-0.0931125 1.25565 -0.0931125 0.651788 0.279337 0.279338Z"
                                                fill="currentColor" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M0.279337 9.72066C-0.0931125 9.34821 -0.0931125 8.74435 0.279337 8.3719L8.3719 0.279338C8.74435 -0.0931127 9.34821 -0.0931123 9.72066 0.279338C10.0931 0.651787 10.0931 1.25565 9.72066 1.6281L1.6281 9.72066C1.25565 10.0931 0.651787 10.0931 0.279337 9.72066Z"
                                                fill="currentColor" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button
                                class="hover:shadow-form w-full rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none">
                                Send File
                            </button>
                        </div>
                    </form>
                    <div class=" flex flex-col ">
                        <div class="bg-white border h-full  w-full">
                            <listExpacionPanel :data="dataList" title="Preguntas y respuestas"></listExpacionPanel>
                        </div>
                        <div class="w-full mr-4  bg-white mt-2 border border-gray-200">
                            <header class="px-5 py-4 border-b border-gray-100">
                                <h2 class="font-semibold text-gray-800">Customers</h2>
                            </header>
                            <div class="p-3">
                                <div class="overflow-x-auto">
                                    <table class="table-auto w-full">
                                        <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                            <tr>
                                                <th class="p-2 whitespace-nowrap">
                                                    <div class="font-semibold text-left">Name</div>
                                                </th>
                                                <th class="p-2 whitespace-nowrap">
                                                    <div class="font-semibold text-left">Email</div>
                                                </th>
                                                <th class="p-2 whitespace-nowrap">
                                                    <div class="font-semibold text-left">Spent</div>
                                                </th>
                                                <th class="p-2 whitespace-nowrap">
                                                    <div class="font-semibold text-center">Country</div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm divide-y divide-gray-100">
                                            <tr>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3"><img
                                                                class="rounded-full"
                                                                src="https://raw.githubusercontent.com/cruip/vuejs-admin-dashboard-template/main/src/images/user-36-05.jpg"
                                                                width="40" height="40" alt="Alex Shatov"></div>
                                                        <div class="font-medium text-gray-800">Alex Shatov</div>
                                                    </div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-left">alexshatov@gmail.com</div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-left font-medium text-green-500">$2,890.66</div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-lg text-center">??</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3"><img
                                                                class="rounded-full"
                                                                src="https://raw.githubusercontent.com/cruip/vuejs-admin-dashboard-template/main/src/images/user-36-06.jpg"
                                                                width="40" height="40" alt="Philip Harbach"></div>
                                                        <div class="font-medium text-gray-800">Philip Harbach</div>
                                                    </div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-left">philip.h@gmail.com</div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-left font-medium text-green-500">$2,767.04</div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-lg text-center">??</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3"><img
                                                                class="rounded-full"
                                                                src="https://raw.githubusercontent.com/cruip/vuejs-admin-dashboard-template/main/src/images/user-36-07.jpg"
                                                                width="40" height="40" alt="Mirko Fisuk"></div>
                                                        <div class="font-medium text-gray-800">Mirko Fisuk</div>
                                                    </div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-left">mirkofisuk@gmail.com</div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-left font-medium text-green-500">$2,996.00</div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-lg text-center">??</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3"><img
                                                                class="rounded-full"
                                                                src="https://raw.githubusercontent.com/cruip/vuejs-admin-dashboard-template/main/src/images/user-36-08.jpg"
                                                                width="40" height="40" alt="Olga Semklo"></div>
                                                        <div class="font-medium text-gray-800">Olga Semklo</div>
                                                    </div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-left">olga.s@cool.design</div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-left font-medium text-green-500">$1,220.66</div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-lg text-center">??</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3"><img
                                                                class="rounded-full"
                                                                src="https://raw.githubusercontent.com/cruip/vuejs-admin-dashboard-template/main/src/images/user-36-09.jpg"
                                                                width="40" height="40" alt="Burak Long"></div>
                                                        <div class="font-medium text-gray-800">Burak Long</div>
                                                    </div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-left">longburak@gmail.com</div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-left font-medium text-green-500">$1,890.66</div>
                                                </td>
                                                <td class="p-2 whitespace-nowrap">
                                                    <div class="text-lg text-center">??</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</AppLayout></template>