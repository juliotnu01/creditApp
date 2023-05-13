<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Models\{CreditRequest, User, Aamortizacion, EstadoCreditoActivo};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreditRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $creditRequest = CreditRequest::with('belongToUser', 'hasManyAmortizaciones', 'hasEstadoCredito')->get();
            return response()->json($creditRequest);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function indexHistorialUser($id)
    {
        try {

            $creditRequest = CreditRequest::with('belongToUser', 'hasManyAmortizaciones', 'hasEstadoCredito')
                ->where('user_id', $id)
                ->get();
            return response()->json($creditRequest);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function indexRecentCreditRequestUser($id)
    {
        try {

            $creditRequest = CreditRequest::with('belongToUser', 'hasManyAmortizaciones', 'hasEstadoCredito')->where('user_id', $id)->orderBy('id', 'DESC')->first();
            return response()->json($creditRequest);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request &$request)
    {
        try {
            return DB::transaction(function () use ($request) {

                $creditRequest = new CreditRequest();
                $creditRequest->nombre_supervisor_ola = $request->BancoDeDatos['nombre_supervisor_ola'];
                $creditRequest->ciudad = $request->BancoDeDatos['ciudad'];
                $creditRequest->nombre_completo = $request->BancoDeDatos['nombre_completo'];
                $creditRequest->fecha_de_nacimiento = $request->BancoDeDatos['fecha_de_nacimiento'];
                $creditRequest->direccion = $request->BancoDeDatos['direccion'];
                $creditRequest->ine_o_identificacion_oficial = $request->BancoDeDatos['ine_o_identificacion_oficial'];
                $creditRequest->tipo_de_relacion_persona_titular_comprobante_domicilio_cliente = $request->BancoDeDatos['tipo_de_relacion_persona_titular_comprobante_domicilio_cliente'];
                $creditRequest->comprobante_domicilio_cliente = $request->BancoDeDatos['comprobante_domicilio_cliente'];
                $creditRequest->comprobante_domicilio_alterno = $request->BancoDeDatos['comprobante_domicilio_alterno'];
                $creditRequest->tipo_de_relacion_persona_titular_comprobante_domicilio_alterno = $request->BancoDeDatos['tipo_de_relacion_persona_titular_comprobante_domicilio_alterno'];
                $creditRequest->principal_fuente_de_ingreso = $request->BancoDeDatos['principal_fuente_de_ingreso'];
                $creditRequest->producto_solicitado = $request->BancoDeDatos['producto_solicitado'];
                $creditRequest->dinero_solicitado = $request->BancoDeDatos['dinero_solicitado'];
                $creditRequest->razon_credito = $request->BancoDeDatos['razon_credito'];
                $creditRequest->en_que_tiempo_piensa_pagar = $request->BancoDeDatos['en_que_tiempo_piensa_pagar'];
                $creditRequest->bajo_que_esquema_esta_acostumbrado_a_pagar = $request->BancoDeDatos['bajo_que_esquema_esta_acostumbrado_a_pagar'];
                $creditRequest->para_que_necesita_el_dinero = $request->BancoDeDatos['para_que_necesita_el_dinero'];
                $creditRequest->cuenta_con_una_cuenta_bancaria = $request->BancoDeDatos['cuenta_con_una_cuenta_bancaria'];
                $creditRequest->banco_cuenta_bancaria = $request->BancoDeDatos['banco_cuenta_bancaria'];
                $creditRequest->numero_de_cuenta_clabe = $request->BancoDeDatos['numero_de_cuenta_clabe'];
                $creditRequest->numero_de_cuenta_tarjeta = $request->BancoDeDatos['numero_de_cuenta_tarjeta'];
                $creditRequest->si_no_tiene_tarjeta_o_cable_indique_tarjeta_de_banco = $request->BancoDeDatos['si_no_tiene_tarjeta_o_cable_indique_tarjeta_de_banco'];
                $creditRequest->nombre_telefono_referencia_familia = $request->BancoDeDatos['nombre_telefono_referencia_familia'];
                $creditRequest->nombre_telefono_referencia_personal = $request->BancoDeDatos['nombre_telefono_referencia_personal'];
                $creditRequest->comprobante_ingresos_certificado_laboral = $request->BancoDeDatos['comprobante_ingresos_certificado_laboral'];
                $creditRequest->nombre_telefono_referencia_empresa = $request->BancoDeDatos['nombre_telefono_referencia_empresa'];
                $creditRequest->eres_trabajador_independiente = $request->BancoDeDatos['eres_trabajador_independiente'];
                $creditRequest->nombre_apellido_del_solidario_trabajador_independiente = $request->BancoDeDatos['nombre_apellido_del_solidario_trabajador_independiente'];
                $creditRequest->telefono_del_solidario_trabajador_independiente = $request->BancoDeDatos['telefono_del_solidario_trabajador_independiente'];
                $creditRequest->ine_identificacion_del_solidario_trabajador_independiente = $request->BancoDeDatos['ine_identificacion_del_solidario_trabajador_independiente'];
                $creditRequest->escala_referencia_gerente_ola = $request->BancoDeDatos['escala_referencia_gerente_ola'];
                $creditRequest->correo_electronico_del_solicitante = $request->BancoDeDatos['correo_electronico_del_solicitante'];
                $creditRequest->numero_de_contacto_ws = $request->BancoDeDatos['numero_de_contacto_ws'];
                $creditRequest->quien_lo_refiere_que_tipo_de_relacion_tiene = $request->BancoDeDatos['quien_lo_refiere_que_tipo_de_relacion_tiene'];
                $creditRequest->tiene_redes_sociales = $request->BancoDeDatos['tiene_redes_sociales'];
                $creditRequest->desde_cuando_lo_conoce_porque_lo_refiere = $request->BancoDeDatos['desde_cuando_lo_conoce_porque_lo_refiere'];
                $creditRequest->nombre_apellido_aval_subrogado_solidario_fallecimiento = $request->BancoDeDatos['nombre_apellido_aval_subrogado_solidario_fallecimiento'];
                $creditRequest->caratula_del_estado_de_cuenta = $request->BancoDeDatos['caratula_del_estado_de_cuenta'];
                $creditRequest->user_id = $request->UserSelected['id'];
                $creditRequest->uui = (string)Str::uuid();    
                $creditRequest->save();


                $amortizaicones = $request->amortizaciones;
                for ($i = 0; $i < count($amortizaicones); $i++) {
                    $element  = $amortizaicones[$i];
                    $amort = new Aamortizacion();
                    $amort->periodo = $element['periodo'];
                    $amort->interes = $element['interes'];
                    $amort->capital = $element['capital'];
                    $amort->pago = $element['pago'];
                    $amort->dias_pago = date("Y-m-d", strtotime($element['dias_pago']));
                    $amort->uui_credit_request = $creditRequest->uui;
                    $amort->credit_request_id = $creditRequest->id;
                    $amort->save();
                }
            }, 5);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function enviarCreditoAEstudio(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $SolicitudDeCredito = new CreditRequest();
            $SolicitudDeCredito->find($request->id_credito)->update([
                "status" => 1
            ]);
        });
    }
    public function rechazarCredito(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $SolicitudDeCredito = new CreditRequest();
            $SolicitudDeCredito->find($request->id_credito)->update([
                "status" => 2
            ]);
        });
    }


    /**
     * Display the specified resource.
     */
    public function show(CreditRequest $creditRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CreditRequest $creditRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CreditRequest $creditRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CreditRequest $creditRequest)
    {
        //
    }
}
