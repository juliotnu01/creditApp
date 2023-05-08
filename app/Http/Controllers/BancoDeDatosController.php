<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BancoDeDatos;
use DB;

class BancoDeDatosController extends Controller
{
    public function index()
    {
        $banco = new BancoDeDatos();
        return response()->json($banco->all());
    }

    public function saveUploadJson(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                dd($request->all());
                for ($i = 0; $i <   count($request->all()); $i++) {
                    $banco = new BancoDeDatos();
                    $banco->apellido_materno = $request[$i]['Apellido Materno'] ?? '';
                    $banco->apellido_paterno = $request[$i]['Apellido Paterno'] ?? '';
                    $banco->aprobacion_legal = $request[$i]['Aprobación LEGAL (Revisión exhaustiva sobre los datos, direcciones, nombres y aspectos legales)'] ?? '';
                    $banco->aprobacion_operativa = $request[$i]['Aprobación OPERATIVA (El cliente debe de cumplir con los requisitos operativos que pedimos y se debe de hacer una revisión sobre la documentación y su orden)'] ?? '';
                    $banco->ciudad = $request[$i]['Ciudad '] ?? '';
                    $banco->colaborador_ola_o_cliente_ola_que_lo_referencia = $request[$i]['Colaborador OLA o cliente OLA que lo referencía'] ?? '';
                    $banco->comprobacion_domicilio_del_cliente = $request[$i]['Comprobante de domicilio del cliente'] ?? '';
                    $banco->correo_electronico_del_solicitante = $request[$i]['Correo electrónico del solicitante'] ?? '';
                    $banco->desde_cuando_lo_conoce_porquer_refiere = $request[$i]['Desde cuando lo conoce? Porque lo Refiere? '] ?? '';
                    $banco->direccion = $request[$i]['Dirección '] ?? '';
                    $banco->el_local_de_su_negocio_es_restado_o_propiedad_del_solicitante = $request[$i]['El local de su negocio, ¿es rentado o propiedad del solicitante?'] ?? '';
                    $banco->escala_de_referencia_gerente_de_cliente_ola = $request[$i]['Escala de Referencia Gerente de Cliente OLA'] ?? '';
                    $banco->fecha_de_nacimiento = $request[$i]['Fecha de nacimiento'] ?? '';
                    $banco->ine_o_identificacion_oficial = $request[$i]['INE o identificación oficial'] ?? '';
                    // $banco->marca_temporal = $request[$i][''] ?? '';
                    $banco->nombre_completo = $request[$i]['NOMBRE COMPLETO'] ?? '';
                    $banco->nombres = $request[$i]['Nombre (s)'] ?? '';
                    $banco->nombre_de_supervisort_ola = $request[$i]['Nombre de supervisor OLA '] ?? '';
                    $banco->numero_de_contacto_ws_solicitante = $request[$i]['Número de contacto (Whatsapp del solicitante)'] ?? '';
                    $banco->monto_dinero_solicitado = $request[$i]['Por favor indica el monto de dinero solicitado'] ?? '';
                    // $banco->foto_del_local = $request[$i]['Por favor sube fotos del local'] ?? '';
                    $banco->quien_lo_refiere_que_tipo_de_relacion_tiene = $request[$i]['Quien lo refiere? Que tipo de  relación tiene (amistad, familia, vecino, etc)? '] ?? '';
                    $banco->si_no_tiene_cuenta_imagen_de_la_cuenta_del_banco = $request[$i]['Si no tiene su número de cuenta o cuenta CLABE, por favor sube la imagen la tarjeta de banco.'] ?? '';
                    $banco->si_respuesta_fue_si_en_banco_tiene_cuenta = $request[$i]['Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?'] ?? '';
                    $banco->tiene_redes_sociales = $request[$i]['Tiene redes sociales? Como lo encontramos en las Redes Sociales? (LinkedIn, Facebook, instagram, etc.)'] ?? '';
                    $banco->a_que_instucion_le_debe_dinero = $request[$i]['¿A qué institución le debe ese dinero?'] ?? '';
                    $banco->cuenta_con_una_cuenta_bancaria = $request[$i]['¿Cuenta con una cuenta bancaria?'] ?? '';
                    $banco->cual_es_el_numero_de_su_cuenta_clabe = $request[$i]['¿Cuál es el número de cuenta Clabe?'] ?? '';
                    $banco->cual_es_el_numero_de_su_cuenta_tarjeta = $request[$i]['¿Cuál es el número de su cuenta/tarjeta?'] ?? '';
                    $banco->cual_es_el_producto_que_se_solicitando = $request[$i]['¿Cuál es el producto que se está solicitando?'] ?? '';
                    $banco->cual_es_la_razon_por_la_que_tiene_esa_deuda = $request[$i]['¿Cuál es la razón por la que tiene esa deuda?'] ?? '';
                    $banco->cual_es_el_principal_fuente_de_ingreso_o_como_piensa_pagar = $request[$i]['¿Cuál es su principal fuente de ingreso? o ¿Cómo piensa pagar lo que está solicitando?'] ?? '';
                    $banco->hace_cuanto_tiene_ese_dinero = $request[$i]['¿Hace cuánto tiempo debe ese dinero?'] ?? '';
                    $banco->para_que_necesita_el_capital = $request[$i]['¿Para qué necesita el capital?'] ?? '';
                    $banco->porque_no_ha_podido_pagar_su_deuda = $request[$i]['¿Porqué no ha podido pagar su deuda?'] ?? '';
                    $banco->sus_proveedores_pueden_emitir_factura = $request[$i]['¿Sus proveedores pueden emitir facturas?'] ?? '';
                    $banco->save();
                }
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
