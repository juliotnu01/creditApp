<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, watch, computed } from 'vue';
import PrimaryButton from '../../Components/PrimaryButton.vue'
import btnPrimary from '../../Components/PrimaryButton.vue'
import listExpacionPanel from "../../Components/listWithExpancionPanel.vue";
import modal from "../../Components/DialogModal.vue";
import moment from "moment";
import { useCreditRequest } from '../../stores/creditRequest'

const props = defineProps({
    isCliente: Boolean,
    user: String,
    uui: String
});

const openModal = ref(false);
const storeCreditRequest = useCreditRequest();
const fechaParaAgregar = ref(31);
const amortizaciones = computed({
    get() {
        return storeCreditRequest.amortizaciones
    },
    set(val) {
        storeCreditRequest.setAmortizaciones(val)
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
const Fecha = ref(null);
const usuarios = ref([]);
const results = ref([]);
const Totalresults = ref(null);
const ClienteDesderesults = ref(null);
const UltimoPrestamoresults = ref(null);
const filteredResults = ref([]);
const filteredResultsHistoryCreditRequest = ref([]);
const searchTerm = ref('');
const BancoDeDatos = ref(false);
const UserSelected = ref(false);
const ProductoSelected = ref('Selecciona el producto');
const openModalEditUser = ref(false);
const openModalSolicitarCredito = ref(false);
const openModalScore = ref(false);
const openModalViewAmortizaciones = ref(false);
const headerTableBancoDeDatos = ref([
    { header: "Nombre de supervisor OLA ", fixed: false, value: '', class: '', show: false },
    { header: "Ciudad ", fixed: false, value: '', class: '', show: false },
    { header: "NOMBRE COMPLETO", fixed: false, value: '', class: '', show: false },
    { header: "Fecha de nacimiento", fixed: false, value: '', class: '', show: false },
    { header: "Dirección ", fixed: false, value: '', class: '', show: false },
    { header: "INE o identificación oficial", fixed: false, value: '', class: '', show: false },
    { header: "Tipo de relación con con la persona titular del comprobante de domicilio (del cliente)", fixed: false, value: '', class: '', show: false },
    { header: "Comprobante de domicilio del cliente", fixed: false, value: '', class: '', show: false },
    { header: "Comprobante de domicilio alterno", fixed: false, value: '', class: '', show: false },
    { header: "Tipo de relación con la persona titular del comprobante de domicilio (del domicilio alterno)	", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuál es su principal fuente de ingreso? o ¿Cómo piensa pagar lo que está solicitando?", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuál es el producto que se está solicitando?", fixed: false, value: '', class: '', show: false },
    { header: "Por favor indica el monto de dinero solicitado", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuál es la razón de tu crédito?	", fixed: false, value: '', class: '', show: false },
    { header: "¿En cuánto tiempo piensas pagar tu crédito?", fixed: false, value: '', class: '', show: false },
    { header: "¿Bajo qué esquema está acostumbrado(a) pagar sus préstamos?", fixed: false, value: '', class: '', show: false },
    { header: "¿Para qué necesita el capital?", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuenta con una cuenta bancaria?", fixed: false, value: '', class: '', show: false },
    { header: "Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuál es el número de cuenta Clabe?", fixed: false, value: '', class: '', show: false },
    { header: "¿Cuál es el número de su cuenta/tarjeta?", fixed: false, value: '', class: '', show: false },
    { header: "¿no tiene cuenta o cuenta CLABE, carga imagen la tarjeta de banco.", fixed: false, value: '', class: '', show: false },
    { header: "Nombre y teléfono de una referencia familiar", fixed: false, value: '', class: '', show: false },
    { header: "Nombre y teléfono de una referencia personal", fixed: false, value: '', class: '', show: false },
    { header: "Comprobante de ingresos, certificado laboral o contrato de trabajo (para maestros será aceptado talón de cheque con plaza)	", fixed: false, value: '', class: '', show: false },
    { header: "Nombre y teléfono de una referencia  en su empresa", fixed: false, value: '', class: '', show: false },
    { header: "¿Tú eres trabajador independiente?", fixed: false, value: '', class: '', show: false },
    { header: "Nombre y apellido del obligado solidario ", fixed: false, value: '', class: '', show: false },
    { header: "Teléfono del obligado solidario ", fixed: false, value: '', class: '', show: false },
    { header: "INE o identificación oficial del obligado solidario  ", fixed: false, value: '', class: '', show: false },
    { header: "Escala de Referencia Gerente de Cliente OLA", fixed: false, value: '', class: '', show: false },
    { header: "Correo electrónico del solicitante", fixed: false, value: '', class: '', show: false },
    { header: "Número de contacto (Whatsapp del solicitante)", fixed: false, value: '', class: '', show: false },
    { header: "Quien lo refiere? Que tipo de  relación tiene (amistad, familia, vecino, etc)? ", fixed: false, value: '', class: '', show: false },
    { header: "Tiene redes sociales? Como lo encontramos en las Redes Sociales? (LinkedIn, Facebook, instagram, etc.)", fixed: false, value: '', class: '', show: false },
    { header: "Desde cuando lo conoce? Porque lo Refiere?", fixed: false, value: '', class: '', show: false },
    { header: "Nombre y Apellido del Aval o subrogado solidario en caso de fallecimiento", fixed: false, value: '', class: '', show: false },
    { header: "Carátula del estado de cuenta", fixed: false, value: '', class: '', show: false },
    { header: "Accion", fixed: false, value: '', class: '', show: false }
]);
const modelComentario = ref({
    comentario: '',
    user_id: null
})
const scoreDelUsuario = ref(0);
const modelDocument = ref({
    ine_identificacion: null,
    comprobante_domicilio_cliente: null,
    comprobante_domicilio_alterno: null,
    imagen_tarjeta_banco: null,
    foto_local_comercial: null,
    comprobante_ingresos: null,
    comprobante_domicilio_obligado: null,
    comprobante_ine_obligado: null,
    garantia_1: null,
    garantia_2: null,
    caratula_cuenta: null,
});
const formulario = computed({
    get() {
        return storeCreditRequest.formulario
    },
    set(val) {
        storeCreditRequest.setformulario(val)
    }
})

const handleDocumentIne_identificacion = (e) => {
    modelDocument.value.ine_identificacion = e.target.files[0]
}
const handleDocumentComprobante_domicilio_cliente = (e) => {
    modelDocument.value.comprobante_domicilio_cliente = e.target.files[0]
}
const handleDocumentComprobante_domicilio_alterno = (e) => {
    modelDocument.value.comprobante_domicilio_alterno = e.target.files[0]
}
const handleDocumentImagen_tarjeta_banco = (e) => {
    modelDocument.value.imagen_tarjeta_banco = e.target.files[0]
}
const handleDocumentFoto_local_comercial = (e) => {
    modelDocument.value.foto_local_comercial = e.target.files[0]
}
const handleDocumentComprobante_ingresos = (e) => {
    modelDocument.value.comprobante_ingresos = e.target.files[0]
}
const handleComprobante_domicilio_obligado = (e) => {
    modelDocument.value.comprobante_domicilio_obligado = e.target.files[0]
}
const handleComprobante_ine_obligado = (e) => {
    modelDocument.value.comprobante_ine_obligado = e.target.files[0]
}
const handleGarantia_1 = (e) => {
    modelDocument.value.garantia_1 = e.target.files[0]
}
const handleGarantia_2 = (e) => {
    modelDocument.value.garantia_2 = e.target.files[0]
}
const handleCaratula_cuenta = (e) => {
    modelDocument.value.caratula_cuenta = e.target.files[0]
}
const formatCurrency = (value) => {
    const formatter = new Intl.NumberFormat("es-MX", {
        style: "currency",
        currency: "MXN",
    });
    return formatter.format(value);
}
const ModelTablaCalculo = computed({
    get() {
        return storeCreditRequest.ModelTablaCalculo
    },
    set(val) {
        storeCreditRequest.setModelTablaCalculo(val)
    }
})
const CalculateMeses = computed({
    get() {
        ModelTablaCalculo.value.Meses = parseFloat(parseFloat((ModelTablaCalculo.value.periodos / ModelTablaCalculo.value.pagosMensuales)).toFixed(1))
        return parseFloat(parseFloat((ModelTablaCalculo.value.periodos / ModelTablaCalculo.value.pagosMensuales)).toFixed(1))
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
const dataList = ref([
    { titulo: '¿Cuál es su principal fuente de ingreso? o ¿Cómo piensa pagar lo que está solicitando?	', toggle: false, show: false },
    { titulo: '¿Cuál es el producto que se está solicitando?', toggle: false, show: false },
    { titulo: '¿Cuál es la razón de tu crédito?', toggle: false, show: false },
    { titulo: '¿En cuánto tiempo piensas pagar tu crédito?', toggle: false, show: false },
    { titulo: '¿Bajo qué esquema está acostumbrado(a) pagar sus préstamos?', toggle: false, show: false },
    { titulo: '¿Para qué necesita el capital?', toggle: false, show: false },
    { titulo: '¿Cuenta con una cuenta bancaria?', toggle: false, show: false },
    { titulo: 'Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?	', toggle: false, show: false },
    { titulo: '¿Cuál es el número de cuenta CLABE?', toggle: false, show: false },
    { titulo: '¿Cuál es el número de su cuenta/tarjeta?', toggle: false, show: false },
    { titulo: '¿no tiene cuenta o cuenta CLABE, carga imagen la tarjeta de banco.', toggle: false, show: false },
    { titulo: '¿Tú eres trabajador independiente?', toggle: false, show: false },
    { titulo: 'Quien lo refiere? Que tipo de  relación tiene (amistad, familia, vecino, etc)?', toggle: false, show: false },
    { titulo: 'Desde cuando lo conoce? Porque lo Refiere? ', toggle: false, show: false },

])
const dataListDocument = ref([
    { titulo: 'INE o identificación oficial', toggle: false, show: false },
    { titulo: 'Comprobante de domicilio del cliente', toggle: false, show: false },
    { titulo: 'Comprobante de domicilio alterno', toggle: false, show: false },
    { titulo: 'Si no tiene su número de cuenta o cuenta CLABE, por favor sube la imagen la tarjeta de banco.', toggle: false, show: false },
    { titulo: 'Foto del Local comercial', toggle: false, show: false },
    { titulo: 'Comprobante de ingresos, certificado laboral o contrato de trabajo (para maestros será aceptado talón de cheque con plaza)', toggle: false, show: false },
    { titulo: 'Comprobante de domicilio Obligado', toggle: false, show: false },
    { titulo: 'INE o identificación oficial del obligado solidario', toggle: false, show: false },
    { titulo: 'Sube documento de garantía 1', toggle: false, show: false },
    { titulo: 'Sube documento de garantía 2', toggle: false, show: false },
    { titulo: 'Carátula del estado de cuenta', toggle: false, show: false },


])
const getUsuarios = async () => {
    try {
        let { data } = await axios('/api/get-users-clientes');
        results.value = data
    } catch (error) {
        console.log(error)
    }
}
const selectResult = (resultSelect) => {
    UserSelected.value = resultSelect
    BancoDeDatos.value = UserSelected.value.has_one_document ?? false
    filteredResults.value = []
    Totalresults.value = resultSelect.has_many_credits_requests.length
    ClienteDesderesults.value = resultSelect.created_at
    UltimoPrestamoresults.value = resultSelect.has_many_credits_requests[resultSelect.has_many_credits_requests.length - 1]
    searchTerm.value = resultSelect.name
    filterResultsHistoryCreditRequest()

     
    scoreDelUsuario.value =  resultSelect.has_many_credits_requests.map((credito) => {
        return credito.has_many_amortizaciones.reduce((score, item) => {
            if (item.has_one_score && item.has_one_score.score) {
                return score + item.has_one_score.score;
            }
            return score;
        }, 0);
    }).reduce((acumulador, valorActual) => acumulador + valorActual) 

}
const filterResults = () => {
    filteredResults.value = results.value.filter(result =>

        result.name.toString().toLowerCase().includes(searchTerm.value.toLowerCase())
    );
}
const filterResultsHistoryCreditRequest = () => {
    filteredResultsHistoryCreditRequest.value = UserSelected.value.has_many_credits_requests.filter(result =>

        result.uui.toString().toLowerCase().includes(props.uui.toLowerCase())
    );
    ProductoSelected.value = filteredResultsHistoryCreditRequest.value[0].producto_solicitado.toUpperCase()


}
const consutarUsuario = async () => {
    BancoDeDatos.value = UserSelected.value.has_one_document
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
const openEditModalUserDocument = () => {
    openModalEditUser.value = true
}
const openSolicitarCredito = () => {
    openModalSolicitarCredito.value = true
}
const EditUserDocument = async () => {
    try {
        await axios.put(route('edit.user.document'), BancoDeDatos.value)
        openModalEditUser.value = false
    } catch (e) {
        console.log(e)
    }
}
const addDocumentFile = async () => {
    try {
        let formData = new FormData();
        formData.append('id_document', BancoDeDatos.value.id)
        formData.append('ine_identificacion', modelDocument.value.ine_identificacion)
        formData.append('comprobante_domicilio_cliente', modelDocument.value.comprobante_domicilio_cliente)
        formData.append('comprobante_domicilio_alterno', modelDocument.value.comprobante_domicilio_alterno)
        formData.append('imagen_tarjeta_banco', modelDocument.value.imagen_tarjeta_banco)
        formData.append('foto_local_comercial', modelDocument.value.foto_local_comercial)
        formData.append('comprobante_ingresos', modelDocument.value.comprobante_ingresos)
        formData.append('comprobante_domicilio_obligado', modelDocument.value.comprobante_domicilio_obligado)
        formData.append('comprobante_ine_obligado', modelDocument.value.comprobante_ine_obligado)
        formData.append('garantia_1', modelDocument.value.garantia_1)
        formData.append('garantia_2', modelDocument.value.garantia_2)
        formData.append('caratula_cuenta', modelDocument.value.caratula_cuenta)
        await axios.post(route('add.document.file'), formData, { headers: { "Content-Type": "multipart/form-data" } })
    } catch (e) {
        console.log(e)
    }
}
const calcularTabla = (capitalInicial, interes, pago, tabla, diasFecha) => {
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
        dias_pago: Fecha.value.add(diasFecha, 'days').format('Do [de] MMMM [del] YYYY').replace(/\b(\d{1,2})(th|st|nd|rd)\b/g, '$1')
    });
    // Llamamos recursivamente la función con el nuevo capital
    return calcularTabla(nuevoCapital, interes, pago, tabla, diasFecha);
}
const openSimuladorDeCredito = async () => {
    Fecha.value = moment();
    var result = await calcularTabla(parseInt(valueRange.value), parseFloat(parseFloat(((ModelTablaCalculo.value.InteresMensual / ModelTablaCalculo.value.pagosMensuales) * valueRange.value) / 100).toFixed(2)), ModelTablaCalculo.value.cuota, [], fechaParaAgregar.value);
    amortizaciones.value.splice(0, amortizaciones.value.length);
    for (let index = 0; index < result.length; index++) {
        const element = result[index];
        storeCreditRequest.setAmortizaciones(element)
    }
    openModal.value = true
}
const viewSolicitudDeCredito = async (credito) => {

    
    while (amortizaciones.value.length > 0) {
        amortizaciones.value.splice(0, 1);
    }
    for (let index = 0; index < credito.has_many_amortizaciones.length; index++) {
        const element = credito.has_many_amortizaciones[index];
        amortizaciones.value = element
    }
    valueRange.value = credito.monto_de_dinero_solicitado
    ModelTablaCalculo.value.tipoPago = credito.tipo_de_pago
    ModelTablaCalculo.value.pagosMensuales = credito.pagos_mensuales
    ModelTablaCalculo.value.InteresMensual = credito.interes_mensual
    ModelTablaCalculo.value.periodos = credito.numeros_de_periodos
    ModelTablaCalculo.value.cuota = CalculateCuota.value
    formulario.value.uui = credito.uui
    formulario.value.id = credito.id
    openModalViewAmortizaciones.value = true
}
const RecalcularCredito = async () => {
    Fecha.value = moment()
    var result = await calcularTabla(parseInt(valueRange.value), parseFloat(parseFloat(((ModelTablaCalculo.value.InteresMensual / ModelTablaCalculo.value.pagosMensuales) * valueRange.value) / 100).toFixed(2)), CalculateCuota.value, [], fechaParaAgregar.value);
    while (amortizaciones.value.length > 0) {
        amortizaciones.value.splice(0, 1);
    }
    for (let index = 0; index < result.length; index++) {
        const element = result[index];
        amortizaciones.value.push(element)
    }
    axios.post('/api/recalcular-amortizaciones', {  tablaCalculo: ModelTablaCalculo.value, 
                                                    uui_credit_request:  formulario.value.uui, 
                                                    credit_request_id: formulario.value.id, 
                                                    amortizaciones: amortizaciones.value })
}

const openModalScoreModal = async () => {
    openModalScore.value = true
}
watch(() => ModelTablaCalculo, (newValue, oldValue) => {
    switch (newValue.value.tipoPago) {
        case "mensual":
            newValue.value.pagosMensuales = 1
            fechaParaAgregar.value = 31;
            break
        case "quincenal":
            newValue.value.pagosMensuales = 2
            fechaParaAgregar.value = 15;
            break
        case "semanal":
            newValue.value.pagosMensuales = 4
            fechaParaAgregar.value = 7;
            break
        default:
            newValue.value.pagosMensuales = 1
            fechaParaAgregar.value = 31;
            break
    }

}, { deep: true })
watch(() => ProductoSelected.value, (newValue, oldValue) => {

    for (let index = 0; index < headerTableBancoDeDatos.value.length; index++) {
        const element = headerTableBancoDeDatos.value[index];
        element.show = false

    }
    for (let index = 0; index < dataList.value.length; index++) {
        const element = dataList.value[index];
        element.show = false

    }
    for (let index = 0; index < dataListDocument.value.length; index++) {
        const element = dataListDocument.value[index];
        element.show = false

    }


    if (newValue == 'PRESTAMO EMERGENTE') {
        headerTableBancoDeDatos.value[30].show = true
        headerTableBancoDeDatos.value[34].show = true
        headerTableBancoDeDatos.value[36].show = true

        // preguntas y respuestas

        dataList.value[1].show = true
        dataList.value[12].show = true
        dataList.value[13].show = true

        // Documentos

        dataListDocument.value[0].show = true
        dataListDocument.value[1].show = true
        dataListDocument.value[10].show = true



    }

    if (newValue == 'PRESTAMO SEGUNDA OCASIÓN') {
        headerTableBancoDeDatos.value[30].show = true
        headerTableBancoDeDatos.value[34].show = true
        headerTableBancoDeDatos.value[36].show = true


        // preguntas y respuestas

        dataList.value[1].show = true
        dataList.value[12].show = true
        dataList.value[13].show = true

        // Documentos

        dataListDocument.value[0].show = true
        dataListDocument.value[1].show = true
        dataListDocument.value[10].show = true

    }

    if (newValue == 'PRESTAMO DE PRIMERA OCASIÓN') {
        headerTableBancoDeDatos.value[6].show = true
        headerTableBancoDeDatos.value[9].show = true
        headerTableBancoDeDatos.value[27].show = true
        headerTableBancoDeDatos.value[28].show = true
        headerTableBancoDeDatos.value[29].show = true
        headerTableBancoDeDatos.value[30].show = true
        headerTableBancoDeDatos.value[34].show = true
        headerTableBancoDeDatos.value[36].show = true

        // preguntas y respuestas

        dataList.value[0].show = true
        dataList.value[1].show = true
        dataList.value[4].show = true
        dataList.value[5].show = true
        dataList.value[6].show = true
        dataList.value[7].show = true
        dataList.value[8].show = true
        dataList.value[9].show = true
        dataList.value[10].show = true
        dataList.value[11].show = true
        dataList.value[12].show = true
        dataList.value[13].show = true


        // Documentos

        dataListDocument.value[0].show = true
        dataListDocument.value[1].show = true
        dataListDocument.value[2].show = true
        dataListDocument.value[3].show = true
        dataListDocument.value[5].show = true
        dataListDocument.value[6].show = true
        dataListDocument.value[7].show = true
        dataListDocument.value[10].show = true

    }
    if (newValue == 'PRESTAMO PARA TRABAJADOR INDEPENDIENTE') {
        headerTableBancoDeDatos.value[6].show = true
        headerTableBancoDeDatos.value[9].show = true
        headerTableBancoDeDatos.value[30].show = true
        headerTableBancoDeDatos.value[34].show = true
        headerTableBancoDeDatos.value[36].show = true

        for (let index = 0; index < dataList.value.length; index++) {
            const element = dataList.value[index];
            element.show = true

        }

        // Documentos
        dataListDocument.value[0].show = true
        dataListDocument.value[1].show = true
        dataListDocument.value[2].show = true
        dataListDocument.value[3].show = true
        dataListDocument.value[5].show = true
        dataListDocument.value[6].show = true
        dataListDocument.value[7].show = true
        dataListDocument.value[10].show = true
    }
    if (newValue == 'PRESTAMOS MAYOR QUE 20 MIL para cliente de 2a ocasión') {
        headerTableBancoDeDatos.value[27].show = true
        headerTableBancoDeDatos.value[28].show = true
        headerTableBancoDeDatos.value[29].show = true
        headerTableBancoDeDatos.value[30].show = true
        headerTableBancoDeDatos.value[34].show = true
        headerTableBancoDeDatos.value[36].show = true

        // preguntas y respuestas

        dataList.value[1].show = true
        dataList.value[12].show = true
        dataList.value[13].show = true

        // documentos

        dataListDocument.value[0].show = true
        dataListDocument.value[1].show = true
        dataListDocument.value[6].show = true
        dataListDocument.value[7].show = true
        dataListDocument.value[10].show = true
    }

    if (newValue == 'PRESTAMOS MAYOR QUE 50 MIL para cliente de 2a ocasión') {
        headerTableBancoDeDatos.value[27].show = true
        headerTableBancoDeDatos.value[28].show = true
        headerTableBancoDeDatos.value[29].show = true
        headerTableBancoDeDatos.value[30].show = true
        headerTableBancoDeDatos.value[34].show = true
        headerTableBancoDeDatos.value[36].show = true

        // preguntas y respuestas

        dataList.value[1].show = true
        dataList.value[12].show = true
        dataList.value[13].show = true

        // documentos

        dataListDocument.value[0].show = true
        dataListDocument.value[1].show = true
        dataListDocument.value[6].show = true
        dataListDocument.value[7].show = true
        dataListDocument.value[8].show = true
        dataListDocument.value[9].show = true
        dataListDocument.value[10].show = true
    }

}, { deep: true })
const enviarCreditRequest = async () => {
    try {

        Fecha.value = moment();
        var result = await calcularTabla(parseInt(valueRange.value), parseFloat(parseFloat(((ModelTablaCalculo.value.InteresMensual / ModelTablaCalculo.value.pagosMensuales) * valueRange.value) / 100).toFixed(2)), ModelTablaCalculo.value.cuota, [], fechaParaAgregar.value);
        amortizaciones.value.splice(0, amortizaciones.value.length);
        for (let index = 0; index < result.length; index++) {
            const element = result[index];
            storeCreditRequest.setAmortizaciones(element)
        }
        let { data } = await axios.post(route('store.credit.request'), {
            amortizaciones: amortizaciones.value,
            BancoDeDatos: BancoDeDatos.value,
            UserSelected: UserSelected.value,
            ProductoSelected: ProductoSelected.value,
            monto_solicitado: valueRange.value
        });
        openModalSolicitarCredito.value = false
        getUsuarios()
    } catch (error) {
        console.log(error)
    }
}

const updateDocument = async (documentStatus, denie = null) => {
    try {
        if (denie === false) {
            documentStatus.status = 2
        }
        await axios.put(route('update.document.status'), documentStatus)
    } catch (e) {
        console.log(e)
    }
}
onMounted(async () => {
    await getUsuarios()
    if (props.user) {
        filteredResults.value = results.value.filter(result =>


            result.name.toString().toLowerCase().includes(props.user.toLowerCase())
        );
        await selectResult(filteredResults.value[0])
        filterResultsHistoryCreditRequest()
    } else {

        filteredResults.value = results.value
        filterResultsHistoryCreditRequest()
    }

})
</script>
<template>
    <AppLayout title="Crear Creditos" :isCliente="isCliente">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Análisis de Creditos
            </h2>
        </template>
        <!-- banner cards -->
        <div class="p-4 w-full" v-if="UserSelected">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-4  ">
                    <div class="flex flex-row bg-white shadow-sm rounded p-4">
                        <div
                            class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-blue-100 text-blue-500">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" stroke="currentColor" >
                            <g clip-path="url(#clip0_443_3628)">
                            <rect x="2" y="6" width="20" height="12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 10C21.4747 10 20.9546 9.89654 20.4693 9.69552C19.984 9.4945 19.543 9.19986 19.1716 8.82843C18.8001 8.45699 18.5055 8.01604 18.3045 7.53073C18.1035 7.04543 18 6.52529 18 6L22 6L22 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18 18C18 16.9391 18.4214 15.9217 19.1716 15.1716C19.9217 14.4214 20.9391 14 22 14L22 18L18 18Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 14C3.06087 14 4.07828 14.4214 4.82843 15.1716C5.57857 15.9217 6 16.9391 6 18L2 18L2 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 6C6 7.06087 5.57857 8.07828 4.82843 8.82843C4.07828 9.57857 3.06087 10 2 10L2 6H6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14.0741 9.5H11.3333C10.597 9.5 10 10.0596 10 10.75C10 11.4404 10.597 12 11.3333 12H13.1111C13.8475 12 14.4444 12.5596 14.4444 13.25C14.4444 13.9404 13.8475 14.5 13.1111 14.5H10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 9.51733V8.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 15.5173V14.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_443_3628">
                            <rect width="24" height="24" fill="white"/>
                            </clipPath>
                            </defs>
                            </svg>
                        </div>
                        <div class="flex flex-col flex-grow ml-4">
                            <div class="text-sm text-gray-500">Total De Prestamos</div>
                            <div class="font-bold text-lg">{{Totalresults}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-4  ">
                    <div class="flex flex-row bg-white shadow-sm rounded p-4">
                        <div
                            class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-green-100 text-green-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col flex-grow ml-4">
                            <div class="text-sm text-gray-500">Cliente Desde</div>
                            <div class="font-bold text-lg">{{ClienteDesderesults.substr(0,10)}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-4  ">
                    <div class="flex flex-row bg-white shadow-sm rounded p-4">
                        <div
                            class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-red-100 text-red-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="flex flex-col flex-grow ml-4">
                            <div class="text-sm text-gray-500">Ultimo Prestamo</div>
                            <div class="font-bold text-lg">{{formatCurrency(UltimoPrestamoresults.monto_de_dinero_solicitado)}} / {{UltimoPrestamoresults.created_at.substr(0,10)}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <!-- end banner cards -->
        <div class="grid grid-cols-12  justify-start">
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

                            <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" class="ml-1 min-w-max">
                                <g>
                                    <path id="Vector"
                                        d="M19 21C19 17.134 15.866 14 12 14C8.13401 14 5 17.134 5 21M12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7C16 9.20914 14.2091 11 12 11Z"
                                        stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </g>
                            </svg>
                        </h1>
                        <h1 class="text-left font-bold  px-1 text-gray-700  flex justify-between hover:bg-gray-100">
                            {{ BancoDeDatos.ine_o_identificacion_oficial }}
                            <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" class="ml-1 min-w-max">
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
                        <select v-model="ProductoSelected" disabled
                            class="bg-gray-50 border text-[9px] w-[225px] border-gray-300 text-gray-900 h-fit rounded-lg   pr-6 ">
                            <option selected>Selecciona el producto</option>
                            <option value="PRESTAMO EMERGENTE">PRESTAMO EMERGENTE</option>
                            <option value="PRESTAMO SEGUNDA OCASIÓN">PRESTAMO SEGUNDA OCASIÓN</option>
                            <option value="PRESTAMO DE PRIMERA OCASIÓN">PRESTAMO DE PRIMERA OCASIÓN </option>
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
                <div v-if="UserSelected" class="max-w-[240] w-[240px] mt-2 py-8 px-6 text-gray-600 rounded-xl border border-gray-200 bg-white">
                    <div class="mt-6 hover:cursor-pointer" @click.prevent="openModalScoreModal"> 
                        <h5 class="text-sm text-bold text-gray-700 text-center">Puntuacion del usuario</h5>
                        <div class="mt-2 flex justify-center gap-4">
                            <h3 class="text-3xl font-bold text-gray-700" :class="{'text-red-700': scoreDelUsuario < 0, 'text-green-700': scoreDelUsuario > 0  }" >
                                {{scoreDelUsuario}}
                            </h3>
                            <div class="flex items-end gap-1 text-green-500">
                                <svg class="w-3" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.00001 0L12 8H-3.05176e-05L6.00001 0Z" fill="currentColor" />
                                </svg>
                                <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" class="text-red-500">
                                    <path fill="currentColor"  d="M17.9188 8.17969H11.6888H6.07877C5.11877 8.17969 4.63877 9.33969 5.31877 10.0197L10.4988 15.1997C11.3288 16.0297 12.6788 16.0297 13.5088 15.1997L15.4788 13.2297L18.6888 10.0197C19.3588 9.33969 18.8788 8.17969 17.9188 8.17969Z" />
                                </svg>
                                
                            </div>
                        </div>
                        <span class="block text-center text-gray-500">comparacion historial de creditos</span>
                    </div> 
                </div>
            </div>
            <!-- tablas -->
            <div class="w-full mr-4  mt-2  flex flex-col gap-4  col-span-9  max-h-fit h-fit" v-if="UserSelected">
                <div class="h-fit max-h-fit  bg-white border border-solid ">
                    <header class="px-5 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800">Creditos</h2>
                    </header>
                    <div class="p-3 h-fit max-h-fi">
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full ">
                                <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                    <tr>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">Identificador</div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">Monto Solicitado</div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">Producto Solicitado</div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-center">Status</div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">Fecha Solicitud</div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">Acción</div>
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-gray-100">
                                    <tr v-for="(credito, c) in filteredResultsHistoryCreditRequest" :key="c">
                                        <td class="p-2 whitespace-nowrap text-[10px]">
                                            <div class="text-left">{{ credito.uui }}</div>
                                        </td>
                                        <td class="p-2 text-left">
                                            <span class="">{{ formatCurrency(credito.monto_de_dinero_solicitado) }}</span>
                                        </td>
                                        <td class="p-2 text-left">
                                            <span class="">{{ credito.producto_solicitado }}</span>
                                        </td>
                                        <td class="p-2 whitespace-nowrap">
                                            <div class="text-left font-medium text-green-500 text-[10px]">
                                                <span
                                                    :class="{ 'bg-yellow-200 text-black py-1 px-3 rounded-full ': credito.status == 0, 'bg-orange-400 text-black py-1 px-3 rounded-full ': credito.status == 1, 'bg-red-400 text-white py-1 px-3 rounded-full ': credito.status == 2 }">
                                                    {{ credito.status == 0 ? 'Credito pendiente por revisar' :
                                                        credito.status == 1 ? 'Credito aprobado para estudio' : credito.status
                                                            == 2 ? 'Credito Rechazado' : '' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="p-2 text-center">
                                            <span class="">{{ credito.created_at.substr(0, 10) }}</span>
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            <div class="flex item-center justify-center">
                                                <div class="w-4 mr-2 transform  hover:scale-110"
                                                    @click="viewSolicitudDeCredito(credito)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
                <div class="h-fit max-h-fit  bg-white border border-solid ">
                    <header class="px-5 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800">Documentos</h2>
                    </header>
                    <div class="p-3 h-fit max-h-fi">
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full ">
                                <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                    <tr>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">Documento</div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">Status</div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">Comentario</div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-left">Acción</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-gray-100">
                                    <tr v-for="(documento, d) in UserSelected.has_one_document.has_many_file_document"
                                        :key="d">

                                        <td class="p-2 whitespace-nowrap text-[10px]">
                                            <a :href="documento.documento"
                                                class="no-underline flex gap-2 hover:underline hover:text-indigo-400"
                                                target="_blank">
                                                {{ documento.has_one_status_document.nombre_documento }}
                                                <svg fill="#000000" width="10px " height="10px " viewBox="0 0 32 32">
                                                    <path
                                                        d="M9.239 22.889c0.195 0.195 0.451 0.293 0.707 0.293s0.511-0.098 0.707-0.293l12.114-12.209c0.39-0.39 0.39-1.024 0-1.414s-1.023-0.39-1.414 0l-12.114 12.209c-0.391 0.39-0.391 1.023 0 1.414zM14.871 20.76c0.331 1.457-0.026 2.887-1.152 4.014l-4.039 3.914c-0.85 0.849-1.98 1.317-3.182 1.317s-2.332-0.468-3.182-1.317c-1.754-1.755-1.754-4.61-0.010-6.354l3.946-4.070c0.85-0.849 1.98-1.318 3.182-1.318 0.411 0 0.807 0.073 1.193 0.179l1.561-1.561c-0.871-0.407-1.811-0.619-2.754-0.619-1.663 0-3.327 0.635-4.596 1.904l-3.936 4.061c-2.538 2.538-2.538 6.654 0 9.193 1.269 1.27 2.933 1.904 4.596 1.904s3.327-0.634 4.596-1.904l4.030-3.904c1.942-1.942 2.361-4.648 1.333-7.023zM30.098 1.899c-1.27-1.269-2.933-1.904-4.596-1.904-1.664 0-3.328 0.635-4.596 1.904l-4.029 3.905c-2.012 2.013-2.423 5.015-1.244 7.439l1.552-1.552c-0.459-1.534-0.107-3.261 1.096-4.463l4.039-3.914c0.851-0.849 1.98-1.318 3.183-1.318 1.201 0 2.332 0.469 3.181 1.318 1.754 1.755 1.754 4.611 0.010 6.354l-4.039 4.039c-0.849 0.85-1.98 1.317-3.181 1.317-0.306 0-0.576 0.031-0.87-0.029l-1.593 1.594c0.796 0.331 1.613 0.436 2.463 0.436 1.663 0 3.326-0.634 4.596-1.904l4.029-4.029c2.538-2.538 2.538-6.653-0-9.192z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </td>

                                        <td class="p-2 whitespace-nowrap">
                                            <div class="text-left font-medium text-green-500 text-[10px]">
                                                <span
                                                    :class="{ 'bg-yellow-200 text-black py-1 px-3 rounded-full ': documento.has_one_status_document.status == 0, 'bg-orange-400 text-black py-1 px-3 rounded-full ': documento.has_one_status_document.status == 1, 'bg-red-400 text-white py-1 px-3 rounded-full ': documento.has_one_status_document.status == 2 }">
                                                    {{ documento.has_one_status_document.status == 0 ? 'Pendite por revisar'
                                                        : documento.has_one_status_document.status == 1 ?
                                                            'Documento Aprobado' : documento.has_one_status_document.status == 2 ?
                                                                'Documento Rechazado' : ''
                                                    }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="p-2 text-center">

                                            <textarea id="message" rows="1"
                                                v-model="documento.has_one_status_document.comentario"
                                                @blur="updateDocument(documento.has_one_status_document)"
                                                class="p-2.5 w-full text-sm " placeholder="Your message..." />


                                        </td>
                                        <td class="p-2 text-center flex item-center justify-center gab-2">
                                            <div
                                                class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">

                                                <input
                                                    class="self-center align-middle h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-4 after:w-4 after:rounded-full after:border-none after:bg-neutral-100 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-4 checked:after:w-4 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-4 focus:after:w-4 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s]"
                                                    type="checkbox"
                                                    @change="updateDocument(documento.has_one_status_document)"
                                                    v-model="documento.has_one_status_document.status" />

                                            </div>
                                            <div class="w-4 mr-2 transform hover:text-red-500 hover:scale-110 self-center align-middle"
                                                @click.prevent="updateDocument(documento.has_one_status_document, false)">
                                                <svg fill="#000000" height="15px" width="15px" viewBox="0 0 329.328 329.328"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M164.666,0C73.871,0,0.004,73.871,0.004,164.672c0.009,90.792,73.876,164.656,164.662,164.656
                                                    c90.793,0,164.658-73.865,164.658-164.658C329.324,73.871,255.459,0,164.666,0z M164.666,30c31.734,0,60.933,11.042,83.975,29.477
                                                    L59.478,248.638c-18.431-23.04-29.471-52.237-29.474-83.967C30.004,90.413,90.413,30,164.666,30z M164.666,299.328
                                                    c-31.733,0-60.934-11.042-83.977-29.477L269.854,80.691c18.431,23.043,29.471,52.244,29.471,83.979
                                                    C299.324,238.921,238.917,299.328,164.666,299.328z" />
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-fit max-h-fit  bg-white border border-solid col-span-12 my-4  ">
                    <header class="px-5 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800">Historial</h2>
                    </header>
                <div class="p-3 h-fit max-h-fi">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full ">
                            <thead class="text-[10px] font-semibold uppercase text-gray-400 bg-gray-50">
                                <tr>
                                    <th class="p-2">
                                        <div class="font-semibold text-left">Identificador</div>
                                    </th>
                                    <th class="p-2">
                                        <div class="font-semibold text-center">Monto Solicitado</div>
                                    </th>
                                    <th class="p-2">
                                        <div class="font-semibold text-center">Interes</div>
                                    </th>
                                    <th class="p-2">
                                        <div class="font-semibold text-center">Fecha de inicio</div>
                                    </th>
                                    <th class="p-2">
                                        <div class="font-semibold text-center">Fecha de termino</div>
                                    </th>
                                    <th class="p-2">
                                        <div class="font-semibold text-center">Puntuacion credito</div>
                                    </th>
                                    <th class="p-2">
                                        <div class="font-semibold text-center">Status</div>
                                    </th>
                                    <th class="p-2">
                                        <div class="font-semibold text-center">Fecha solicitud</div>
                                    </th>
                                    

                                </tr>
                            </thead>
                            <tbody class="text-[10px] divide-y divide-gray-100">
                                <tr v-for="(credito, c) in filteredResultsHistoryCreditRequest" :key="c">
                                    <td class="p-2 whitespace-nowrap text-[10px]">
                                        <div class="text-left">{{ credito.uui }}</div>
                                    </td>
                                    <td class="p-2 text-center">
                                        <span class="">{{ formatCurrency(credito.monto_de_dinero_solicitado) }}</span>
                                    </td>
                                    <td class="p-2 text-center">
                                        <span class="">{{ credito.interes_mensual }}</span>
                                    </td>
                                    <td class="p-2 text-center">
                                        <span class="">{{ credito.has_many_amortizaciones[0].dias_pago  }}</span>
                                    </td>
                                    <td class="p-2 text-center">
                                        <span class="">{{ credito.has_many_amortizaciones[credito.has_many_amortizaciones.length -1].dias_pago  }}</span>
                                    </td>
                                    <td class="p-2 text-center">
                                        <!-- <span class="">{{ credito.has_many_credits_requests[credito.has_many_credits_requests - 1].dias_pago }}</span> -->
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left font-medium text-green-500 text-[10px]">
                                            <span
                                                :class="{ 'bg-yellow-200 text-black py-1 px-3 rounded-full ': credito.status == 0, 'bg-orange-400 text-black py-1 px-3 rounded-full ': credito.status == 1, 'bg-red-400 text-white py-1 px-3 rounded-full ': credito.status == 2 }">
                                                {{ credito.status == 0 ? 'Credito pendiente por revisar' :
                                                    credito.status == 1 ? 'Credito aprobado para estudio' : credito.status
                                                        == 2 ? 'Credito Rechazado' : '' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-2 text-center">
                                        <span class="">{{ credito.created_at.substr(0, 10) }}</span>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <modal :show="openModalViewAmortizaciones" maxWidth="">
            <template #title>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                    @click="openModalViewAmortizaciones = !openModalViewAmortizaciones">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </template>

            <template #content>
                <div class="p-6 bg-white flex justify-center gap-4">
                    <section class="antialiased w-full ">
                        <div class="w-full  mx-auto bg-white shadow-lg rounded-sm border border-gray-200">
                            <div class="overflow-x-auto p-3">
                                <table class="table-auto w-3/4">
                                    <tbody>
                                        <tr>
                                            <th class="px-4 w-2/4 py-2 text-left">Monto Prestamo</th>
                                            <td class="border px-4 py-2">
                                                 <input min="0" v-model="valueRange" type="number"
                                                        class="bg-transparent block border-2 peer px-3 w-full" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="px-4 w-2/4 py-2 text-left">Tipo de pago</th>
                                            <td class="border px-4 py-2">
                                                <div>
                                                    <select v-model="ModelTablaCalculo.tipoPago" class="w-full">
                                                        <option value="mensual">
                                                            Mensual
                                                        </option>
                                                        <option value="quincenal">
                                                            Quincenal
                                                        </option>
                                                        <option value="semanal">
                                                            Semanal
                                                        </option>
                                                    </select>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="px-4 w-2/4 py-2 text-left">Periodos</th>
                                            <td class="border px-4 py-2">
                                                <div>
                                                    <input min="0" v-model="ModelTablaCalculo.periodos" type="number"
                                                        class="bg-transparent block border-2 peer px-3 w-full" />
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="px-4 w-2/4 py-2 text-left">Pagos {{ ModelTablaCalculo.tipoPago }}
                                            </th>
                                            <td class="border px-4 py-2 ">
                                                <p class="ml-3"> {{ ModelTablaCalculo.pagosMensuales }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="px-4 w-2/4 py-2 text-left capitalize">Meses</th>
                                            <td class="border px-4 py-2">
                                                <p class="ml-3">{{ CalculateMeses }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="px-4 w-2/4 py-2 text-left">Interés mensual</th>
                                            <td class="border px-4 py-2">
                                                <div class="relative flex flex-wrap items-stretch">
                                                    <input type="nunmber" v-model="ModelTablaCalculo.InteresMensual"
                                                        class="relative m-0 block w-[30px] min-w-0 flex-auto rounded-l border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary"
                                                        aria-label="Recipient's username" aria-describedby="basic-addon2" />
                                                    <span
                                                        class="flex items-center whitespace-nowrap rounded-r border border-l-0 border-solid border-neutral-300 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                                                        id="basic-addon2">%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="px-4 w-2/4 py-2 text-left">Pago Total</th>
                                            <td class="border px-4 py-2">
                                                <div class="relative flex flex-wrap items-stretch">
                                                    <input type="nunmber" v-model="CalculatePagoTotal"
                                                        class="relative m-0 block w-[30px] min-w-0 flex-auto rounded-l border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary"
                                                        aria-label="Recipient's username" aria-describedby="basic-addon2" />
                                                    <span
                                                        class="flex items-center whitespace-nowrap rounded-r border border-l-0 border-solid border-neutral-300 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                                                        id="basic-addon2">%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="px-4 w-2/4 py-2 text-left">Pago OLA</th>
                                            <td class="border px-4 py-2">{{ formatCurrency(CalculatePagoOla) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="px-4 w-2/4 py-2 text-left">Cuota</th>
                                            <td class="border px-4 py-2">{{ formatCurrency(CalculateCuota) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="flex justify-end my-2 gap-2">
                                    <!-- <button @click.prevent="openSimuladorDeCredito"
                                        class="shadow  flex gap-4 justify-around bg-[#66c3d1] max-w-[60%] hover:bg-[#2cb6cb] focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                        type="button">
                                        Si quieres conseguir una mejor oferta o tasa de interes contacta un asesor
                                        <svg width="35px" height="35px" viewBox="0 0 24 24" class="self-center">
                                            <g id="Complete">
                                                <g id="F-Chevron">
                                                    <polyline fill="none" id="Right" points="8.5 5 15.5 12 8.5 19"
                                                        stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" />
                                                </g>
                                            </g>
                                        </svg>
                                    </button> -->
                                    <!-- <button @click.prevent="quieroMimCredito"
                                            class="shadow bg-[#66c3d1] hover:bg-[#2cb6cb] focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                            type="button">
                                            Quiero mi credito
                                        </button> -->
                                        <btnPrimary @click.prevent="RecalcularCredito()">Recalcular</btnPrimary>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="bg-white shadow-md rounded my-6">
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
                                    <div class="font-semibold text-center">
                                        Aciones
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="text-sm divide-y divide-gray-100">

                            <tr v-for="(pago, p) in amortizaciones" :key='p'>
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
                                    <div class="text-right font-medium text-green-500">
                                        {{ pago.dias_pago }}
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <div class="w-4 mr-2 transform  hover:scale-110"
                                            @click="AddReciboDePagoARecaudo(pago)">
                                            <svg width="15px" height="15px" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg" fill="none">
                                                <path fill="#000000" fill-rule="evenodd"
                                                    d="M9 12a1 1 0 102 0V4.26l3.827 3.48a1 1 0 001.346-1.48l-5.5-5a1 1 0 00-1.346 0l-5.5 5a1 1 0 101.346 1.48L9 4.26V12zm-5.895-.796A1 1 0 001.5 12v3.867a2.018 2.018 0 002.227 2.002c1.424-.147 3.96-.369 6.273-.369 2.386 0 5.248.236 6.795.383a2.013 2.013 0 002.205-2V12a1 1 0 10-2 0v3.884l-13.895-4.68zm0 0L2.5 11l.605.204zm0 0l13.892 4.683a.019.019 0 01-.007.005h-.006c-1.558-.148-4.499-.392-6.984-.392-2.416 0-5.034.23-6.478.38h-.009a.026.026 0 01-.013-.011V12a.998.998 0 00-.394-.796z" />
                                            </svg>
                                        </div>
                                        <div class="w-4 mr-2 transform  hover:scale-110">
                                            <a :href="pago.ruta_recibo_de_pago" target="_blank"
                                                class="flex items-center text-gray-600 mb-2">
                                                <svg width="15px" height="15px" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <title />
                                                    <g id="Complete">
                                                        <g id="download">
                                                            <g>
                                                                <path d="M3,12.3v7a2,2,0,0,0,2,2H19a2,2,0,0,0,2-2v-7"
                                                                    fill="none" stroke="#000000" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2" />
                                                                <g>
                                                                    <polyline data-name="Right" fill="none" id="Right-2"
                                                                        points="7.9 12.3 12 16.3 16.1 12.3" stroke="#000000"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" />

                                                                    <line fill="none" stroke="#000000"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" x1="12" x2="12" y1="2.7"
                                                                        y2="14.2" />
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="w-4 mr-2 transform  hover:scale-110">
                                            <p class="flex items-center text-gray-600 mb-2">
                                                <span :class="{
                                                    'w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0': pago.status == null,
                                                    'w-4 h-4 mr-2 inline-flex items-center justify-center bg-yellow-400 text-black rounded-full flex-shrink-0': pago.status == 0,
                                                    'w-4 h-4 mr-2 inline-flex items-center justify-center bg-green-400 text-white rounded-full flex-shrink-0': pago.status == 1,
                                                    'w-4 h-4 mr-2 inline-flex items-center justify-center bg-red-400 text-white rounded-full flex-shrink-0': pago.status == 2
                                                }">
                                                    <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2.5" class="w-3 h-3"
                                                        viewBox="0 0 24 24">
                                                        <path d="M20 6L9 17l-5-5"></path>
                                                    </svg>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                   
                </div>
            </template>

            <template #footer>
                <div class="flex justify-end">
                    <!-- <btnPrimay @click.prevent="enviarCreditoAEstudio(formulario)">Enviar a estudio</btnPrimay> -->
                </div>
            </template>
        </modal>
        
        <modal :show="openModalScore"  maxWidth="">
            <template #title>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="openModalScore = !openModalScore">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </template>

            <template #content>
                <!-- <section class="antialiased bg-gray-100 text-gray-600 h-screen px-4" x-data="app"> -->
                <div class="w-full  mx-auto bg-white shadow-lg rounded-sm mt-4">
                    
                       
                        <div class="w-full mr-4  bg-white mt-2 border border-gray-200"  v-for="(creditos , c) in UserSelected.has_many_credits_requests" :key="c">
                            <header class="px-5 py-4 border-b border-gray-100">
                                <h2 class="font-semibold text-gray-800">Credito {{creditos.uui}} </h2>
                            </header>
                            <div class="p-3">
                                <div class="overflow-x-auto">
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
                                            <tr v-for="(pago, p) in creditos.has_many_amortizaciones" :key='p'>
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
                                                        {{ pago.has_one_score ? pago.has_one_score.score : ''  }}
                                                    </div>
                                                </td>
                                                <td class="p-2">
                                                    <div class="text-right font-medium ">
                                                        {{ pago.has_one_score ? pago.has_one_score.comentario : ''  }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    
                </div>
                <!-- </section> -->
            </template>
        </modal>
    </AppLayout>
</template>