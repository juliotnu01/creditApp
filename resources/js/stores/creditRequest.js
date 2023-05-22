import { defineStore } from 'pinia'


export const useCreditRequest = defineStore('creditRequest', {
    state: () => ({
        amortizaciones: [],
        formulario: {
            nombre: '',
            apellido_paterno: '',
            apellido_materno: '',
            fecha_nacimiento: '',
            nacionalidad: '',
            correo: '',
            celular: '',
            telefono: '',
            file_comprobante_ine_identificacion: '',
            text_comprobante_ine_identificacion: '',
            tipo_identificacion_social: '',
            numero_curp: '',
            file_comprobante_domicilio_cliente: '',
            direccion_domicilio: '',
            relacion_per_del_titular_del_compro_domicilio_cliente: '',
            relacion_per_del_titular_del_compro_domicilio_domicilio: '',
            file_caratula_del_estado_de_cuenta: '',
            principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado: '',
            cual_es_producto_que_esta_solicitando: '',
            escala_de_referencia_gerente_de_cliente_ola: '',
            justificar_la_referencia: '',
            quien_lo_refiere: '',
            desde_cuando_lo_conoce_por_que_lo_refiere: '',
            tiene_redes_sociales: '',
            ciudad: '',
            monto_de_dinero_solicitado: 0,
            razon_del_prestamo: '',
            hace_cuanto_tiempo_debe_ese_dinero: '',
            a_que_institucion_le_debe_ese_dinero: '',
            cuenta_con_una_cuenta_bancaria: '',
            respuesta_de_cuenta_con_una_cuenta_bancaria: '',
            numero_de_cuenta_clabe: '',
            numero_de_cuenta: '',
            file_foto_de_tarjeta: '',
            por_que_no_ha_popido_pagar_su_deuda: '',
            nombre_apellido_del_obligado_solidario: '',
            telefono_obligado_solidario: '',
            correo_obligado_solidario: '',
            nacionalidad_obligado_solidario: '',
            tipo_de_identificacion_oficial: '',
            file_comprobante_ine_o_identificacion_oficial: '',
            ine_o_identificacion_oficial: '',
            numero_curp_obligado: '',
            file_comprobante_de_domicilio: '',
            domicilio: '',
            uui: '',
            id: '',
        },
        tablaAmortizaciones: [],
        valueRange: 3000,
        ModelTablaCalculo: {
            tipoPago: 'mensual',
            pagosMensuales: parseInt(1),
            periodos: parseInt(1),
            Meses: 1,
            InteresMensual: parseFloat(parseFloat(5.93).toFixed(2)),
            pagoTotal: 0,
            PagoOla: 0,
            cuota: 0
        },
    }),
    getters: {
        getteramortizaciones: (state) => state.amortizaciones,
        getterformulario: (state) => state.formulario,
        gettervalueRange: (state) => state.valueRange,
        gettertablaAmortizaciones: (state) => state.tablaAmortizaciones,
        getterModelTablaCalculo: (state) => state.ModelTablaCalculo,
    },
    actions: {
        setModelTablaCalculo(data) {
            this.ModelTablaCalculo = data
        },
        setTablaAmortizaciones(data) {
            this.tablaAmortizaciones = data
        },
        setValueRange(data) {
            // console.log({data})
            this.formulario.monto_de_dinero_solicitado = data
            this.valueRange = data
        },
        setAmortizaciones(data) {

            this.amortizaciones.push(data)

        },
        setformulario(data) {
            this.formulario = data
        }
    }
})