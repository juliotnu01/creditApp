<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
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
const storeCreditRequest = useCreditRequest();
const formulario = computed({
    get() {
        return storeCreditRequest.formulario
    },
    set(val) {
        storeCreditRequest.setformulario(val)
    }
})

const enviarCreditRequest = async () => {
    try {
        let {data} = await axios.post('/guardar-solicitud-de-credito', formulario.value);

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
                                <input class="w-full py-2 px-3 rounded border" type="file"
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
                                <select v-model="formulario.tipo_identificacion_social" class="w-full py-2 px-3 rounded border" id="tipo_identificacion_social">
                                    <option value="INE">INE</option>
                                    <option value="PASAPORTE">PASAPORTE</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="text_comprobante_ine_identificacion">
                                    Número Curp
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="text_comprobante_ine_identificacion"
                                    v-model="formulario.numero_curp">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="file_comprobante_domicilio_cliente">
                                    Comprobante de domicilio del cliente
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="file"
                                    id="file_comprobante_domicilio_cliente">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="text_comprobante_ine_identificacion">
                                    Direccion de domicilio
                                </label>
                                <textarea v-model="formulario.direccion_domicilio" class="w-full py-2 px-3 rounded border" id="text_comprobante_ine_identificacion"
                                    name="textarea" />
                            </div>

                            <div>
                                <label class="block mb-2 font-bold" for="relacion_con_la_persona_del_titular_del_comprobante_domicilio">
                                    Tipo de relación con con la persona titular del comprobante de domicilio (del cliente)
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="relacion_con_la_persona_del_titular_del_comprobante_domicilio"
                                    v-model="formulario.relacion_con_la_persona_del_titular_del_comprobante_domicilio">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="text_comprobante_ine_identificacion">
                                    Comprobante de domicilio alterno
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="file"
                                    id="text_comprobante_ine_identificacion">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="text_comprobante_ine_identificacion">
                                    Tipo de relación con la persona titular del comprobante de domicilio (del domicilio
                                    alterno)
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="text_comprobante_ine_identificacion"
                                    v-model="formulario.text_comprobante_ine_identificacion">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="file_caratula_del_estado_de_cuenta">
                                    Carátula del estado de cuenta *
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="file"
                                    id="file_caratula_del_estado_de_cuenta">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado">
                                    ¿Cuál es su principal fuente de ingreso? o ¿Cómo piensa pagar lo que está solicitando?
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado"
                                    v-model="formulario.principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="text_comprobante_ine_identificacion">
                                    ¿Cuál es el producto que se está solicitando?
                                </label>
                                <select v-model="formulario.cual_es_producto_que_esta_solicitando" class="w-full py-2 px-3 rounded border" id="cual_es_producto_que_esta_solicitando">
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
                                    <input v-model="formulario.escala_de_referencia_gerente_de_cliente_ola" type="range" min="1" max="5" step="1" class="mt-4 w-full">
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="justificar_la_referencia">
                                    Justificar la referencia (Solo si la escala es menor a 3)
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="justificar_la_referencia"
                                    v-model="formulario.justificar_la_referencia">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="quien_lo_refiere">
                                    Quién lo refiere? Que tipo de relación tiene (amistad, familia, vecino, etc)?
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="quien_lo_refiere"
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
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="tiene_redes_sociales"
                                    v-model="formulario.tiene_redes_sociales">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="ciudad">
                                    Ciudad
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="ciudad"
                                    v-model="formulario.ciudad">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="text_comprobante_ine_identificacion">
                                    Monto de dinero solicitado
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="text_comprobante_ine_identificacion"
                                    v-model="formulario.text_comprobante_ine_identificacion">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="razon_del_prestamo">
                                    ¿Cuál es la razón por la que tiene esa deuda?
                                </label>
                                <select v-model="formulario.razon_del_prestamo" class="w-full py-2 px-3 rounded border" id="razon_del_prestamo" name="razon_del_prestamo"
                                    required="">
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
                                <select v-model="formulario.a_que_institucion_le_debe_ese_dinero" class="w-full py-2 px-3 rounded border" id="a_que_institucion_le_debe_ese_dinero" name="a_que_institucion_le_debe_ese_dinero"
                                    required="">
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
                                        <input class="form-check-input" v-model="formulario.cuenta_con_una_cuenta_bancaria" value="1" name="have_account" id="have_account" type="radio">
                                        <label class="form-check-label">Si</label>
                                    </div>
                                    <div class="form-block mb-2 font-bold">
                                        <input class="form-check-input" v-model="formulario.cuenta_con_una_cuenta_bancaria" value="0" name="have_account" id="have_account" type="radio" checked="">
                                        <label class="form-check-label">No</label>
                                    </div>
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="respuesta_de_cuenta_con_una_cuenta_bancaria">
                                    ¿Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?
                                </label>
                                <select v-model="formulario.respuesta_de_cuenta_con_una_cuenta_bancaria" class="w-full py-2 px-3 rounded border" id="respuesta_de_cuenta_con_una_cuenta_bancaria" name="respuesta_de_cuenta_con_una_cuenta_bancaria"
                                    required="">
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
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="numero_de_cuenta_clabe"
                                    v-model="formulario.numero_de_cuenta_clabe">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="numero_de_cuenta">
                                    ¿Cuál es su número de cuenta/tarjeta?
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="numero_de_cuenta"
                                    v-model="formulario.numero_de_cuenta">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="file_foto_de_tarjeta">
                                    Sube una foto de la tarjeta que tiene, en el caso de que no sepa su cuenta ni su cuenta CLABE
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="file"
                                    id="file_foto_de_tarjeta">
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
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="telefono_obligado_solidario"
                                    v-model="formulario.telefono_obligado_solidario">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="correo_obligado_solidario">
                                    Correo del Obligado solidario
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="correo_obligado_solidario"
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
                                    id="tipo_de_identificacion_oficial"
                                    v-model="formulario.tipo_de_identificacion_oficial">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="file_comprobante_ine_o_identificacion_oficial">
                                    Comprobante INE o identificación oficial
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="file"
                                    id="file_comprobante_ine_o_identificacion_oficial">
                            </div>
                            <div>
                                <label class="block mb-2 font-bold" for="ine_o_identificacion_oficial">
                                    INE o identificación oficial 
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="ine_o_identificacion_oficial"
                                    v-model="formulario.ine_o_identificacion_oficial">
                            </div>                            
                            <div>
                                <label class="block mb-2 font-bold" for="numero_curp_obligado">
                                    Número CURP Obligado
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="text"
                                    id="numero_curp_obligado"
                                    v-model="formulario.numero_curp_obligado">
                            </div>                            
                            <div>
                                <label class="block mb-2 font-bold" for="file_comprobante_de_domicilio">
                                    Comprobante de Domicilio
                                </label>
                                <input class="w-full py-2 px-3 rounded border" type="file"
                                    id="file_comprobante_de_domicilio">
                            </div>                            
                            <div>
                                <label class="block mb-2 font-bold" for="domicilio">
                                    Domicilio 
                                </label>
                                <textarea v-model="formulario.domicilio" class="w-full py-2 px-3 rounded border" id="domicilio"
                                    name="textarea" />
                            </div>                            
                        </div>
                    </div>
                </div>
                <btnPrimay @click.prevent="enviarCreditRequest" >Enviar</btnPrimay>
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
}</style>
