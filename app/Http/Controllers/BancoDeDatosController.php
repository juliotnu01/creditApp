<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{BancoDeDatos, DocumentosDeBancoDeDatos};
use DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
                // dd($request->all());
                for ($i = 0; $i <   count($request->all()); $i++) {
                    $banco = new BancoDeDatos();
                    $banco->nombre_supervisor_ola = $request[$i]['Nombre de supervisor OLA '];
                    $banco->ciudad = $request[$i]['Ciudad '];
                    $banco->nombre_completo = $request[$i]['NOMBRE COMPLETO'];
                    $banco->fecha_de_nacimiento = $request[$i]['Fecha de nacimiento'];
                    $banco->direccion = $request[$i]['Dirección '];
                    $banco->ine_o_identificacion_oficial = $request[$i]['INE o identificación oficial'];
                    $banco->tipo_de_relacion_persona_titular_comprobante_domicilio_cliente = $request[$i]['Tipo de relación con con la persona titular del comprobante de domicilio (del cliente)'];
                    $banco->comprobante_domicilio_cliente = $request[$i]['Comprobante de domicilio del cliente'];
                    $banco->comprobante_domicilio_alterno = $request[$i]['Comprobante de domicilio alterno'];
                    $banco->tipo_de_relacion_persona_titular_comprobante_domicilio_alterno = $request[$i]['Tipo de relación con la persona titular del comprobante de domicilio (del domicilio alterno)'];
                    $banco->principal_fuente_de_ingreso = $request[$i]['¿Cuál es su principal fuente de ingreso? o ¿Cómo piensa pagar lo que está solicitando?'];
                    $banco->producto_solicitado = $request[$i]['¿Cuál es el producto que se está solicitando?'];
                    $banco->dinero_solicitado = $request[$i]['Por favor indica el monto de dinero solicitado'];
                    $banco->razon_credito = $request[$i]['¿Cuál es la razón de tu crédito?'];
                    $banco->en_que_tiempo_piensa_pagar = $request[$i]['¿En cuánto tiempo piensas pagar tu crédito?'];
                    $banco->bajo_que_esquema_esta_acostumbrado_a_pagar = $request[$i]['¿Bajo qué esquema está acostumbrado(a) pagar sus préstamos?'];
                    $banco->para_que_necesita_el_dinero = $request[$i]['¿Para qué necesita el capital?'];
                    $banco->cuenta_con_una_cuenta_bancaria = $request[$i]['¿Cuenta con una cuenta bancaria?'];
                    $banco->banco_cuenta_bancaria = $request[$i]['Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?'];
                    $banco->numero_de_cuenta_clabe = $request[$i]['¿Cuál es el número de cuenta CLABE?'];
                    $banco->numero_de_cuenta_tarjeta = $request[$i]['¿Cuál es el número de su cuenta/tarjeta?'];
                    $banco->si_no_tiene_tarjeta_o_cable_indique_tarjeta_de_banco = $request[$i]['Si no tiene su número de cuenta o cuenta CLABE, por favor sube la imagen la tarjeta de banco.'];
                    $banco->nombre_telefono_referencia_familia = $request[$i]['Nombre y teléfono de una referencia familiar'];
                    $banco->nombre_telefono_referencia_personal = $request[$i]['Nombre y teléfono de una referencia personal'];
                    $banco->comprobante_ingresos_certificado_laboral = $request[$i]['Comprobante de ingresos, certificado laboral o contrato de trabajo (para maestros será aceptado talón de cheque con plaza)'];
                    $banco->nombre_telefono_referencia_empresa = $request[$i]['Nombre y teléfono de una referencia  en su empresa'];
                    $banco->eres_trabajador_independiente = $request[$i]['¿Tú eres trabajador independiente?'];
                    $banco->nombre_apellido_del_solidario_trabajador_independiente = $request[$i]['Nombre y apellido del obligado solidario para Trabajador independiente '];
                    $banco->telefono_del_solidario_trabajador_independiente = $request[$i]['Teléfono del obligado solidario para Trabajador independiente '];
                    $banco->ine_identificacion_del_solidario_trabajador_independiente = $request[$i]['INE o identificación oficial del obligado solidario para Trabajador independiente '];
                    $banco->escala_referencia_gerente_ola = $request[$i]['Escala de Referencia Gerente de Cliente OLA'];
                    $banco->correo_electronico_del_solicitante = $request[$i]['Correo electrónico del solicitante'];
                    $banco->numero_de_contacto_ws = $request[$i]['Número de contacto (Whatsapp del solicitante)'];
                    $banco->quien_lo_refiere_que_tipo_de_relacion_tiene = $request[$i]['Quien lo refiere? Que tipo de  relación tiene (amistad, familia, vecino, etc)? '];
                    $banco->tiene_redes_sociales = $request[$i]['Tiene redes sociales? Como lo encontramos en las Redes Sociales? (LinkedIn, Facebook, instagram, etc.)'];
                    $banco->desde_cuando_lo_conoce_porque_lo_refiere = $request[$i]['Desde cuando lo conoce? Porque lo Refiere? '];
                    $banco->nombre_apellido_aval_subrogado_solidario_fallecimiento = $request[$i]['Nombre y Apellido del Aval o subrogado solidario en caso de fallecimiento'];
                    $banco->caratula_del_estado_de_cuenta = $request[$i]['Carátula del estado de cuenta'];
                    $banco->save();
                }
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function asociarDocumento(Request $request)
    {
        try {
            $banDeDato = new BancoDeDatos();
            $banDeDato->find($request['doc']['id'])->update([
                "user_id" => $request['user']['id']
            ]);
            return response()->json(['mensaje' => 'Documento asociado con exito']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function edit(Request $request)
    {
        try {
            $banDeDato = new BancoDeDatos();
            $banDeDato->find($request['id'])->update($request->except(['has_many_file_document']));
            return response()->json(['mensaje' => 'Documento Editado con exito']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function addDocumentFile(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                if ($request->ine_identificacion != "null") {
                    $fecha = substr(Carbon::now(), 0, 10);
                    Storage::disk('public')->putFileAs("/archivos/$fecha/comprobante ine o identificacion/", $request->file('ine_identificacion'), $request->file('ine_identificacion')->getClientOriginalName());
                    $urlFile_ine_identificacion = asset(Storage::disk('public')->url("/archivos/$fecha/comprobante ine o identificacion/" . $request->file('ine_identificacion')->getClientOriginalName()));

                    $documentoFile = new DocumentosDeBancoDeDatos();
                    $documentoFile->documento = $urlFile_ine_identificacion;
                    $documentoFile->banco_de_datos_id = $request->id_document;
                    $documentoFile->save();

                    $documentoFile->hasOneStatusDocument()->create([
                        'status' => false,
                        'nombre_documento' => 'comprobante ine o identificador',
                    ]);
                } else {
                    $urlFile_ine_identificacion = null;
                }

                if ($request->comprobante_domicilio_cliente != "null") {
                    $fecha = substr(Carbon::now(), 0, 10);
                    Storage::disk('public')->putFileAs("/archivos/$fecha/comprobante domicilio cliente/", $request->file('comprobante_domicilio_cliente'), $request->file('comprobante_domicilio_cliente')->getClientOriginalName());
                    $urlFile_comprobante_domicilio_cliente = asset(Storage::disk('public')->url("/archivos/$fecha/comprobante domicilio cliente/" . $request->file('comprobante_domicilio_cliente')->getClientOriginalName()));

                    $documentoFile = new DocumentosDeBancoDeDatos();
                    $documentoFile->documento = $urlFile_comprobante_domicilio_cliente;
                    $documentoFile->banco_de_datos_id = $request->id_document;
                    $documentoFile->save();

                    $documentoFile->hasOneStatusDocument()->create([
                        'status' => false,
                        'nombre_documento' => 'comprobante domicilio cliente',
                    ]);
                } else {
                    $urlFile_comprobante_domicilio_cliente = null;
                }

                if ($request->comprobante_domicilio_alterno != "null") {

                    $fecha = substr(Carbon::now(), 0, 10);
                    Storage::disk('public')->putFileAs("/archivos/$fecha/comprobante domicilio alterno/", $request->file('comprobante_domicilio_alterno'), $request->file('comprobante_domicilio_alterno')->getClientOriginalName());
                    $urlFile_comprobante_domicilio_alterno = asset(Storage::disk('public')->url("/archivos/$fecha/comprobante domicilio alterno/" . $request->file('comprobante_domicilio_alterno')->getClientOriginalName()));

                    $documentoFile = new DocumentosDeBancoDeDatos();
                    $documentoFile->documento = $urlFile_comprobante_domicilio_alterno;
                    $documentoFile->banco_de_datos_id = $request->id_document;
                    $documentoFile->save();

                    $documentoFile->hasOneStatusDocument()->create([
                        'status' => false,
                        'nombre_documento' => 'comprobante domicilio alterno',
                    ]);
                } else {
                    $urlFile_comprobante_domicilio_alterno = null;
                }

                if ($request->imagen_tarjeta_banco != "null") {
                    $fecha = substr(Carbon::now(), 0, 10);
                    Storage::disk('public')->putFileAs("/archivos/$fecha/imagen tarjeta banco/", $request->file('imagen_tarjeta_banco'), $request->file('imagen_tarjeta_banco')->getClientOriginalName());
                    $urlFile_imagen_tarjeta_banco = asset(Storage::disk('public')->url("/archivos/$fecha/imagen tarjeta banco/" . $request->file('imagen_tarjeta_banco')->getClientOriginalName()));

                    $documentoFile = new DocumentosDeBancoDeDatos();
                    $documentoFile->documento = $urlFile_imagen_tarjeta_banco;
                    $documentoFile->banco_de_datos_id = $request->id_document;
                    $documentoFile->save();

                    $documentoFile->hasOneStatusDocument()->create([
                        'status' => false,
                        'nombre_documento' => 'Imagen Tarjeta Banco',
                    ]);
                } else {
                    $urlFile_imagen_tarjeta_banco = null;
                }

                if ($request->foto_local_comercial != "null") {
                    $fecha = substr(Carbon::now(), 0, 10);
                    Storage::disk('public')->putFileAs("/archivos/$fecha/foto local comercial/", $request->file('foto_local_comercial'), $request->file('foto_local_comercial')->getClientOriginalName());
                    $urlFile_foto_local_comercial = asset(Storage::disk('public')->url("/archivos/$fecha/foto local comercial/" . $request->file('foto_local_comercial')->getClientOriginalName()));

                    $documentoFile = new DocumentosDeBancoDeDatos();
                    $documentoFile->documento = $urlFile_foto_local_comercial;
                    $documentoFile->banco_de_datos_id = $request->id_document;
                    $documentoFile->save();

                    $documentoFile->hasOneStatusDocument()->create([
                        'status' => false,
                        'nombre_documento' => 'Foto local comercial',
                    ]);
                } else {
                    $urlFile_foto_local_comercial = null;
                }

                if ($request->comprobante_ingresos != "null") {
                    $fecha = substr(Carbon::now(), 0, 10);
                    Storage::disk('public')->putFileAs("/archivos/$fecha/comprobante ingreso/", $request->file('comprobante_ingresos'), $request->file('comprobante_ingresos')->getClientOriginalName());
                    $urlFile_comprobante_ingresos = asset(Storage::disk('public')->url("/archivos/$fecha/comprobante ingreso/" . $request->file('comprobante_ingresos')->getClientOriginalName()));

                    $documentoFile = new DocumentosDeBancoDeDatos();
                    $documentoFile->documento = $urlFile_comprobante_ingresos;
                    $documentoFile->banco_de_datos_id = $request->id_document;
                    $documentoFile->save();

                    $documentoFile->hasOneStatusDocument()->create([
                        'status' => false,
                        'nombre_documento' => 'Comprobante Ingreso',
                    ]);
                } else {
                    $urlFile_comprobante_ingresos = null;
                }

                if ($request->comprobante_domicilio_obligado != "null") {
                    $fecha = substr(Carbon::now(), 0, 10);
                    Storage::disk('public')->putFileAs("/archivos/$fecha/comprobante domicilio obligado/", $request->file('comprobante_domicilio_obligado'), $request->file('comprobante_domicilio_obligado')->getClientOriginalName());
                    $urlFile_comprobante_domicilio_obligado = asset(Storage::disk('public')->url("/archivos/$fecha/comprobante domicilio obligado/" . $request->file('comprobante_domicilio_obligado')->getClientOriginalName()));

                    $documentoFile = new DocumentosDeBancoDeDatos();
                    $documentoFile->documento = $urlFile_comprobante_domicilio_obligado;
                    $documentoFile->banco_de_datos_id = $request->id_document;
                    $documentoFile->save();

                    $documentoFile->hasOneStatusDocument()->create([
                        'status' => false,
                        'nombre_documento' => 'comprobante domicilio obligado',
                    ]);
                } else {
                    $urlFile_comprobante_domicilio_obligado = null;
                }

                if ($request->comprobante_ine_obligado != "null") {
                    $fecha = substr(Carbon::now(), 0, 10);
                    Storage::disk('public')->putFileAs("/archivos/$fecha/comprobante ine obligado/", $request->file('comprobante_ine_obligado'), $request->file('comprobante_ine_obligado')->getClientOriginalName());
                    $urlFile_comprobante_ine_obligado = asset(Storage::disk('public')->url("/archivos/$fecha/comprobante ine obligado/" . $request->file('comprobante_ine_obligado')->getClientOriginalName()));

                    $documentoFile = new DocumentosDeBancoDeDatos();
                    $documentoFile->documento = $urlFile_comprobante_ine_obligado;
                    $documentoFile->banco_de_datos_id = $request->id_document;
                    $documentoFile->save();

                    $documentoFile->hasOneStatusDocument()->create([
                        'status' => false,
                        'nombre_documento' => 'comprobante ine obligado',
                    ]);
                } else {
                    $urlFile_comprobante_ine_obligado = null;
                }

                if ($request->garantia_1 != "null") {
                    $fecha = substr(Carbon::now(), 0, 10);
                    Storage::disk('public')->putFileAs("/archivos/$fecha/garantia 1/", $request->file('garantia_1'), $request->file('garantia_1')->getClientOriginalName());
                    $urlFilegarantia_1 = asset(Storage::disk('public')->url("/archivos/$fecha/garantia 1/" . $request->file('garantia_1')->getClientOriginalName()));

                    $documentoFile = new DocumentosDeBancoDeDatos();
                    $documentoFile->documento = $urlFilegarantia_1;
                    $documentoFile->banco_de_datos_id = $request->id_document;
                    $documentoFile->save();

                    $documentoFile->hasOneStatusDocument()->create([
                        'status' => false,
                        'nombre_documento' => 'gararantia 1',
                    ]);
                } else {
                    $urlFile_garantia_1 = null;
                }

                if ($request->garantia_2 != "null") {
                    $fecha = substr(Carbon::now(), 0, 10);
                    Storage::disk('public')->putFileAs("/archivos/$fecha/garantia 2/", $request->file('garantia_2'), $request->file('garantia_2')->getClientOriginalName());
                    $urlFile_garantia_2 = asset(Storage::disk('public')->url("/archivos/$fecha/garantia 2/" . $request->file('garantia_2')->getClientOriginalName()));

                    $documentoFile = new DocumentosDeBancoDeDatos();
                    $documentoFile->documento = $urlFile_garantia_2;
                    $documentoFile->banco_de_datos_id = $request->id_document;
                    $documentoFile->save();

                    $documentoFile->hasOneStatusDocument()->create([
                        'status' => false,
                        'nombre_documento' => 'gararantia 2',
                    ]);
                } else {
                    $urlFile_garantia_2 = null;
                }


                if ($request->caratula_cuenta != "null") {
                    $fecha = substr(Carbon::now(), 0, 10);
                    Storage::disk('public')->putFileAs("/archivos/$fecha/caratula cuenta/", $request->file('caratula_cuenta'), $request->file('caratula_cuenta')->getClientOriginalName());
                    $urlFile_caratula_cuenta = asset(Storage::disk('public')->url("/archivos/$fecha/caratula cuenta/" . $request->file('caratula_cuenta')->getClientOriginalName()));

                    $documentoFile = new DocumentosDeBancoDeDatos();
                    $documentoFile->documento = $urlFile_caratula_cuenta;
                    $documentoFile->banco_de_datos_id = $request->id_document;
                    $documentoFile->save();

                    $documentoFile->hasOneStatusDocument()->create([
                        'status' => false,
                        'nombre_documento' => 'caratula cuenta',
                    ]);
                } else {
                    $urlFile_caratula_cuenta = null;
                }
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
