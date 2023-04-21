<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import modal from "../Components/DialogModal.vue";
import moment from "moment";
import { useCreditRequest } from '../stores/creditRequest'

import btnPrimay from '../Components/PrimaryButton.vue'


defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
const ModelTablaCalculo = computed({
    get(){
        return storeCreditRequest.ModelTablaCalculo
    },
    set(val){
        storeCreditRequest.setModelTablaCalculo(val)
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
const storeCreditRequest = useCreditRequest();
const formulario = computed({
    get() {
        return storeCreditRequest.formulario
    },
    set(val) {
        storeCreditRequest.setformulario(val)
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
const enviarCreditRequest = async () => {
    try {
        const formData = new FormData()
        formData.append('file_comprobante_ine_identificacion', formulario.value.file_comprobante_ine_identificacion)
        formData.append('file_comprobante_domicilio_cliente', formulario.value.file_comprobante_domicilio_cliente)
        formData.append('file_caratula_del_estado_de_cuenta', formulario.value.file_caratula_del_estado_de_cuenta)
        formData.append('file_foto_de_tarjeta', formulario.value.file_foto_de_tarjeta)
        formData.append('file_comprobante_ine_o_identificacion_oficial', formulario.value.file_comprobante_ine_o_identificacion_oficial)
        formData.append('file_comprobante_de_domicilio', formulario.value.file_comprobante_de_domicilio)
        formData.append('nombre', formulario.value.nombre);
        formData.append('apellido_paterno', formulario.value.apellido_paterno);
        formData.append('apellido_materno', formulario.value.apellido_materno);
        formData.append('fecha_nacimiento', formulario.value.fecha_nacimiento);
        formData.append('nacionalidad', formulario.value.nacionalidad);
        formData.append('correo', formulario.value.correo);
        formData.append('celular', formulario.value.celular);
        formData.append('telefono', formulario.value.telefono);
        formData.append('text_comprobante_ine_identificacion', formulario.value.text_comprobante_ine_identificacion);
        formData.append('tipo_identificacion_social', formulario.value.tipo_identificacion_social);
        formData.append('numero_curp', formulario.value.numero_curp);
        formData.append('direccion_domicilio', formulario.value.direccion_domicilio);
        formData.append('relacion_per_del_titular_del_compro_domicilio_cliente', formulario.value.relacion_per_del_titular_del_compro_domicilio_cliente);
        formData.append('relacion_per_del_titular_del_compro_domicilio_domicilio', formulario.value.relacion_per_del_titular_del_compro_domicilio_domicilio);
        formData.append('principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado', formulario.value.principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado);
        formData.append('cual_es_producto_que_esta_solicitando', formulario.value.cual_es_producto_que_esta_solicitando);
        formData.append('escala_de_referencia_gerente_de_cliente_ola', formulario.value.escala_de_referencia_gerente_de_cliente_ola);
        formData.append('justificar_la_referencia', formulario.value.justificar_la_referencia);
        formData.append('quien_lo_refiere', formulario.value.quien_lo_refiere);
        formData.append('desde_cuando_lo_conoce_por_que_lo_refiere', formulario.value.desde_cuando_lo_conoce_por_que_lo_refiere);
        formData.append('tiene_redes_sociales', formulario.value.tiene_redes_sociales);
        formData.append('ciudad', formulario.value.ciudad);
        formData.append('monto_de_dinero_solicitado', valueRange.value);
        formData.append('razon_del_prestamo', formulario.value.razon_del_prestamo);
        formData.append('hace_cuanto_tiempo_debe_ese_dinero', formulario.value.hace_cuanto_tiempo_debe_ese_dinero);
        formData.append('a_que_institucion_le_debe_ese_dinero', formulario.value.a_que_institucion_le_debe_ese_dinero);
        formData.append('cuenta_con_una_cuenta_bancaria', formulario.value.cuenta_con_una_cuenta_bancaria);
        formData.append('respuesta_de_cuenta_con_una_cuenta_bancaria', formulario.value.respuesta_de_cuenta_con_una_cuenta_bancaria);
        formData.append('numero_de_cuenta_clabe', formulario.value.numero_de_cuenta_clabe);
        formData.append('numero_de_cuenta', formulario.value.numero_de_cuenta);
        formData.append('por_que_no_ha_popido_pagar_su_deuda', formulario.value.por_que_no_ha_popido_pagar_su_deuda);
        formData.append('nombre_apellido_del_obligado_solidario', formulario.value.nombre_apellido_del_obligado_solidario);
        formData.append('telefono_obligado_solidario', formulario.value.telefono_obligado_solidario);
        formData.append('correo_obligado_solidario', formulario.value.correo_obligado_solidario);
        formData.append('nacionalidad_obligado_solidario', formulario.value.nacionalidad_obligado_solidario);
        formData.append('tipo_de_identificacion_oficial', formulario.value.tipo_de_identificacion_oficial);
        formData.append('ine_o_identificacion_oficial', formulario.value.ine_o_identificacion_oficial);
        formData.append('numero_curp_obligado', formulario.value.numero_curp_obligado);
        formData.append('domicilio', formulario.value.domicilio);
        formData.append('amortizaciones', JSON.stringify(amortizaciones.value));
        formData.append('tipo_de_pago', ModelTablaCalculo.value.tipoPago);
        formData.append('pagos_mensuales', ModelTablaCalculo.value.pagosMensuales);
        formData.append('numeros_de_periodos', ModelTablaCalculo.value.periodos);
        formData.append('meses', ModelTablaCalculo.value.Meses);
        formData.append('interes_mensual', ModelTablaCalculo.value.InteresMensual);
        formData.append('pago_total', ModelTablaCalculo.value.pagoTotal);
        formData.append('pago_ola', ModelTablaCalculo.value.PagoOla);
        formData.append('cuota', ModelTablaCalculo.value.cuota);
        let { data } = await axios.post('/guardar-solicitud-de-credito', formData, { headers: { "Content-Type": "multipart/form-data" } });
        const form = useForm({
            email: data.email,
            password: data.password,
            remember: false,
        });
        
        form.transform(data => ({
            ...data,
            remember: form.remember ? 'on' : '',
        })).post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    } catch (error) {
        console.log(error)
    }
}

const handleFileComprobanteIneOIdentificacion = (e) => {
    formulario.value.file_comprobante_ine_identificacion = e.target.files[0]
}
const handleComprobanteDomicilioCliente = (e) => {
    formulario.value.file_comprobante_domicilio_cliente = e.target.files[0]
}
const handleCaratulaDelEstadoDeCuenta = (e) => {
    formulario.value.file_caratula_del_estado_de_cuenta = e.target.files[0]
}
const handleFotoDeTarjeta = (e) => {
    formulario.value.file_foto_de_tarjeta = e.target.files[0]
}
const handleComprobanteIneOIdentifacionOficial = (e) => {
    formulario.value.file_comprobante_ine_o_identificacion_oficial = e.target.files[0]
}
const handleComprobanteDocimilio = (e) => {
    formulario.value.file_comprobante_de_domicilio = e.target.files[0]
}

</script>

<template>
    <Head title="Welcome" />
    <!-- <div class="fixed top-0 left-0 w-full h-full flex items-center justify-center z-50 bg-gray-900 bg-opacity-50">
            <div class="spinner w-20 h-20 border-4 border-t-4 border-gray-200 rounded-full animate-spin"></div>>
        </div>
        <div class="fixed top-0 right-0 mt-4 mr-4 bg-white border border-gray-300 p-4 rounded-md shadow-lg z-50">
            <div class="font-medium text-gray-800">Este es un toast</div>
            <div class="text-sm text-gray-600 mt-1">Este toast es creado con HTML y Tailwind CSS</div>
        </div> -->
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

        <div class="w-11/12 mx-auto p-2 lg:p-8">
            <div
                class="flex flex-col rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 w-full">

                <div class="flex flex-col justify-start p-6">
                    <div class="w-ful mx-auto mt-8">
                        <h1 class="text-2xl font-bold mb-4">Formulario de solicitud de préstamo</h1>
                        <div class="grid grid-cols-4 sm:grid-cols-4 lg:grid-cols-4 gap-4 text-[13px]">
                            <div>
                                <label class="block mb-2 font-bold" for="nombre">Nombre:</label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="nombre"
                                    v-model="formulario.nombre">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="apellido_paterno">Apellido Paterno:</label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="apellido_paterno"
                                    v-model="formulario.apellido_paterno">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="apellido_materno">Apellido Materno:</label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="apellido_materno"
                                    v-model="formulario.apellido_materno">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="fecha_nacimiento">Fecha de Nacimiento:</label>
                                <input class="w-full py-2 px-3 rounded border" type="date" id="fecha_nacimiento"
                                    v-model="formulario.fecha_nacimiento">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="nacionalidad">Nacionalidad:</label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="nacionalidad"
                                    v-model="formulario.nacionalidad">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="correo">Correo:</label>
                                <input class="w-full py-2 px-3 rounded border" type="email" id="correo"
                                    v-model="formulario.correo">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="celular">Celular (Whatsapp):</label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="celular"
                                    v-model="formulario.celular">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="telefono">Teléfono fijo:</label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="telefono"
                                    v-model="formulario.telefono">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="file_comprobante_ine_identificacion">
                                    Comprobante INE o Identificación oficial:
                                </label>
                                <input class="w-full py-2 px-3 rounded border"
                                    @change="handleFileComprobanteIneOIdentificacion" type="file"
                                    id="file_comprobante_ine_identificacion">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="text_comprobante_ine_identificacion">
                                    INE o identificación:
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="text_comprobante_ine_identificacion"
                                    v-model="formulario.text_comprobante_ine_identificacion">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="tipo_identificacion_social">
                                    Tipo de Identificación Social:
                                </label>
                                <select v-model="formulario.tipo_identificacion_social"
                                    class="w-full py-2 px-3 rounded border" id="tipo_identificacion_social">
                                    <option value="INE">INE</option>
                                    <option value="PASAPORTE">PASAPORTE</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="numero_curp">
                                    Número Curp
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="numero_curp"
                                    v-model="formulario.numero_curp">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="file_comprobante_domicilio_cliente">
                                    Comprobante de domicilio del cliente
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="file"
                                    id="file_comprobante_domicilio_cliente" @change="handleComprobanteDomicilioCliente">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="direccion_domicilio">
                                    Direccion de domicilio
                                </label>
                                <textarea v-model="formulario.direccion_domicilio" class="w-full py-2 px-3 rounded border"
                                    id="direccion_domicilio" name="textarea" />
                            </div>

                            <div>
                                <label class="block mb-2 font-bold"
                                    for="relacion_con_la_persona_del_titular_del_comprobante_domicilio">
                                    Tipo de relación con con la persona titular del comprobante de domicilio (del cliente)
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
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
                                    Tipo de relación con la persona titular del comprobante de domicilio (del domicilio
                                    alterno)
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="relacion_con_la_persona_del_titular_del_comprobante_domicilio"
                                    v-model="formulario.relacion_per_del_titular_del_compro_domicilio_domicilio">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="file_caratula_del_estado_de_cuenta">
                                    Carátula del estado de cuenta *
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="file"
                                    id="file_caratula_del_estado_de_cuenta" @change="handleCaratulaDelEstadoDeCuenta">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold"
                                    for="principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado">
                                    ¿Cuál es su principal fuente de ingreso? o ¿Cómo piensa pagar lo que está solicitando?
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado"
                                    v-model="formulario.principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="cual_es_producto_que_esta_solicitando">
                                    ¿Cuál es el producto que se está solicitando?
                                </label>
                                <select v-model="formulario.cual_es_producto_que_esta_solicitando"
                                    class="w-full py-2 px-3 rounded border" id="cual_es_producto_que_esta_solicitando">
                                    <option value="Reparacion critica">Reparacion critica</option>
                                    <option value="Prestamo a micro negocio">Prestamo a micro negocio</option>
                                    <option value="Credito de segunda ocacion">Credito de segunda ocacion</option>
                                    <option value="Credito a empleado o trabajador independiente">Credito a empleado o
                                        trabajador independiente</option>
                                    <option value="Prestamo emergente">Prestamo emergente</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="escala_de_referencia_gerente_de_cliente_ola">
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
                                    <input id="escala_de_referencia_gerente_de_cliente_ola"
                                        v-model="formulario.escala_de_referencia_gerente_de_cliente_ola" type="range"
                                        min="1" max="5" step="1" class="mt-4 w-full">
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="justificar_la_referencia">
                                    Justificar la referencia (Solo si la escala es menor a 3)
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="justificar_la_referencia"
                                    v-model="formulario.justificar_la_referencia">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="quien_lo_refiere">
                                    Quién lo refiere? Que tipo de relación tiene (amistad, familia, vecino, etc)?
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="quien_lo_refiere"
                                    v-model="formulario.quien_lo_refiere">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="desde_cuando_lo_conoce_por_que_lo_refiere">
                                    Desde cuando lo conoce? Porque lo Refiere?
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="desde_cuando_lo_conoce_por_que_lo_refiere"
                                    v-model="formulario.desde_cuando_lo_conoce_por_que_lo_refiere">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="tiene_redes_sociales">
                                    Tiene redes sociales? Como lo encontramos en las Redes Sociales? (LinkedIn, Facebook,
                                    instagram, etc.)
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="tiene_redes_sociales"
                                    v-model="formulario.tiene_redes_sociales">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="ciudad">
                                    Ciudad
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="ciudad"
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
                                    <input type="number" v-model="valueRange" min="3000"
                                        class="relative m-0 text-right block w-6 min-w-4 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary"
                                        placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" />
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="razon_del_prestamo">
                                    ¿Cuál es la razón por la que tiene esa deuda?
                                </label>
                                <select v-model="formulario.razon_del_prestamo" class="w-full py-2 px-3 rounded border"
                                    id="razon_del_prestamo" name="razon_del_prestamo" required="">
                                    <option value="">Selecciona la razón</option>
                                    <option value="prestamo por otra persona">Pidió un préstamo por una persona y luego no
                                        se lo pago</option>
                                    <option value="atraso">Se atrasó en algunos pagos y solo ha pagado intereses</option>
                                    <option value="problema">Tuvo un problema financiero</option>
                                    <option value="otro">Otro...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="hace_cuanto_tiempo_debe_ese_dinero">
                                    ¿Hace cuánto tiempo debe ese dinero?
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="hace_cuanto_tiempo_debe_ese_dinero"
                                    v-model="formulario.hace_cuanto_tiempo_debe_ese_dinero">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="a_que_institucion_le_debe_ese_dinero">
                                    ¿A qué institución le debe ese dinero?
                                </label>
                                <select v-model="formulario.a_que_institucion_le_debe_ese_dinero"
                                    class="w-full py-2 px-3 rounded border" id="a_que_institucion_le_debe_ese_dinero"
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
                                    <input class="form-check-input" v-model="formulario.cuenta_con_una_cuenta_bancaria"
                                        value="1" name="have_account" id="have_account" type="radio">
                                    <label class="form-check-label">Si</label>
                                </div>
                                <div class="form-block mb-2 font-bold">
                                    <input class="form-check-input" v-model="formulario.cuenta_con_una_cuenta_bancaria"
                                        value="0" name="have_account" id="have_account" type="radio" checked="">
                                    <label class="form-check-label">No</label>
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="respuesta_de_cuenta_con_una_cuenta_bancaria">
                                    ¿Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?
                                </label>
                                <select v-model="formulario.respuesta_de_cuenta_con_una_cuenta_bancaria"
                                    class="w-full py-2 px-3 rounded border" id="respuesta_de_cuenta_con_una_cuenta_bancaria"
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
                                <input class="w-full py-2 px-3 rounded border" type="text" id="numero_de_cuenta_clabe"
                                    v-model="formulario.numero_de_cuenta_clabe">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="numero_de_cuenta">
                                    ¿Cuál es su número de cuenta/tarjeta?
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="numero_de_cuenta"
                                    v-model="formulario.numero_de_cuenta">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="file_foto_de_tarjeta">
                                    Sube una foto de la tarjeta que tiene, en el caso de que no sepa su cuenta ni su cuenta
                                    CLABE
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="file" id="file_foto_de_tarjeta"
                                    @change="handleFotoDeTarjeta">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="por_que_no_ha_popido_pagar_su_deuda">
                                    ¿Porqué no ha podido pagar su deuda?
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="por_que_no_ha_popido_pagar_su_deuda"
                                    v-model="formulario.por_que_no_ha_popido_pagar_su_deuda">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="nombre_apellido_del_obligado_solidario">
                                    Nombre y apellido del obligado solidario
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="nombre_apellido_del_obligado_solidario"
                                    v-model="formulario.nombre_apellido_del_obligado_solidario">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="telefono_obligado_solidario">
                                    Teléfono del obligado solidario
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="telefono_obligado_solidario"
                                    v-model="formulario.telefono_obligado_solidario">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="correo_obligado_solidario">
                                    Correo del Obligado solidario
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="correo_obligado_solidario"
                                    v-model="formulario.correo_obligado_solidario">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="nacionalidad_obligado_solidario">
                                    Nacionalidad del Obligado solidario
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="nacionalidad_obligado_solidario"
                                    v-model="formulario.nacionalidad_obligado_solidario">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="tipo_de_identificacion_oficial">
                                    Tipo de identificación oficial
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="tipo_de_identificacion_oficial" v-model="formulario.tipo_de_identificacion_oficial">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="file_comprobante_ine_o_identificacion_oficial">
                                    Comprobante INE o identificación oficial
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="file"
                                    id="file_comprobante_ine_o_identificacion_oficial"
                                    @change="handleComprobanteIneOIdentifacionOficial">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="ine_o_identificacion_oficial">
                                    INE o identificación oficial
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="ine_o_identificacion_oficial"
                                    v-model="formulario.ine_o_identificacion_oficial">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="numero_curp_obligado">
                                    Número CURP Obligado
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text" id="numero_curp_obligado"
                                    v-model="formulario.numero_curp_obligado">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="file_comprobante_de_domicilio">
                                    Comprobante de Domicilio
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="file"
                                    id="file_comprobante_de_domicilio" @change="handleComprobanteDocimilio">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="domicilio">
                                    Domicilio
                                </label>
                                <textarea v-model="formulario.domicilio" class="w-full py-2 px-3 rounded border"
                                    id="domicilio" name="textarea" />
                            </div>
                        </div>
                    </div>
                </div>
                <btnPrimay @click.prevent="enviarCreditRequest">Enviar</btnPrimay>
            </div>
        </div>
    </div>
</template>

<style>
.spinner {
    border-top-color: transparent;
    margin: auto;
}

.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
