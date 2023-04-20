<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Models\{CreditRequest, User, Aamortizacion};
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

            $creditRequest = CreditRequest::with('belongToUser', 'hasManyAmortizaciones')->get();
            return response()->json($creditRequest);

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function indexHistorialUser($id)
    {
        try {

            $creditRequest = CreditRequest::with('belongToUser', 'hasManyAmortizaciones')
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

            $creditRequest = CreditRequest::with('belongToUser', 'hasManyAmortizaciones')->where('user_id', $id)->orderBy('id', 'DESC')->first();
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
            return DB::transaction(function () use ($request){
                

                $nombreCliente = $request->nombre;
                $numeroCurp = $request->numero_curp;

                if($request->file_comprobante_ine_identificacion){
                    // file_comprobante_ine_identificacion
                    Storage::disk('public')->putFileAs("/documentos/clientes/$numeroCurp/". substr(Carbon::now(), 0, 10) ."/$nombreCliente/comprobante ine o identificacion/" , $request->file('file_comprobante_ine_identificacion'), $request->file('file_comprobante_ine_identificacion')->getClientOriginalName());
                    $urlFile_comprobante_ine_identificacion = Storage::disk('public')->url("/documentos/clientes/$numeroCurp/". substr(Carbon::now(), 0, 10) ."/$nombreCliente/comprobante ine o identificacion/".$request->file('file_comprobante_ine_identificacion')->getClientOriginalName());
                }else{
                    $urlFile_comprobante_ine_identificacion = null;
                }

                if($request->file_comprobante_domicilio_cliente){
                    // file_comprobante_domicilio_cliente
                    Storage::disk('public')->putFileAs("/documentos/clientes/$numeroCurp/". substr(Carbon::now(), 0, 10) ."/$nombreCliente/comprobante domicilio cliente/" , $request->file('file_comprobante_domicilio_cliente'), $request->file('file_comprobante_domicilio_cliente')->getClientOriginalName());
                    $urlfile_comprobante_domicilio_cliente = Storage::disk('public')->url("/documentos/clientes/$numeroCurp/". substr(Carbon::now(), 0, 10) ."/$nombreCliente/comprobante domicilio cliente/".$request->file('file_comprobante_domicilio_cliente')->getClientOriginalName());
                    
                }else{
                    $urlfile_comprobante_domicilio_cliente  = null;
                }

                if($request->file_caratula_del_estado_de_cuenta){
                    // file_caratula_del_estado_de_cuenta
                    Storage::disk('public')->putFileAs("/documentos/clientes/$numeroCurp/". substr(Carbon::now(), 0, 10) ."/$nombreCliente/caratula del estado de cuenta/" , $request->file('file_caratula_del_estado_de_cuenta'), $request->file('file_caratula_del_estado_de_cuenta')->getClientOriginalName());
                    $urlfile_caratula_del_estado_de_cuenta = Storage::disk('public')->url("/documentos/clientes/$numeroCurp/". substr(Carbon::now(), 0, 10) ."/$nombreCliente/caratula del estado de cuenta/".$request->file('file_caratula_del_estado_de_cuenta')->getClientOriginalName());
                }else{
                    $urlfile_caratula_del_estado_de_cuenta = null;
                }

                if($request->file_foto_de_tarjeta){
                    // file_foto_de_tarjeta
                    Storage::disk('public')->putFileAs("/documentos/clientes/$numeroCurp/". substr(Carbon::now(), 0, 10) ."/$nombreCliente/foto de tarjeta/" , $request->file('file_foto_de_tarjeta'), $request->file('file_foto_de_tarjeta')->getClientOriginalName());
                    $urlfile_foto_de_tarjeta = Storage::disk('public')->url("/documentos/clientes/$numeroCurp/". substr(Carbon::now(), 0, 10) ."/$nombreCliente/foto de tarjeta/".$request->file('file_foto_de_tarjeta')->getClientOriginalName());
                }else{
                    $urlfile_foto_de_tarjeta = null;
                }

                if($request->file_comprobante_de_domicilio){
                    // file_comprobante_ine_o_identificacion_oficial
                    Storage::disk('public')->putFileAs("/documentos/clientes/$numeroCurp/". substr(Carbon::now(), 0, 10) ."/$nombreCliente/comprobante de domicilio/" , $request->file('file_comprobante_ine_o_identificacion_oficial'), $request->file('file_comprobante_ine_o_identificacion_oficial')->getClientOriginalName());
                    $urlfile_comprobante_ine_o_identificacion_oficial = Storage::disk('public')->url("/documentos/clientes/$numeroCurp/". substr(Carbon::now(), 0, 10) ."/$nombreCliente/comprobante de domicilio/".$request->file('file_comprobante_ine_o_identificacion_oficial')->getClientOriginalName());
                    
                }else{
                    $urlfile_comprobante_ine_o_identificacion_oficial = null;
                }

                if($request->file_comprobante_de_domicilio){    
                    // file_comprobante_de_domicilio
                    Storage::disk('public')->putFileAs("/documentos/clientes/$numeroCurp/". substr(Carbon::now(), 0, 10) ."/$nombreCliente/comprobante de domicilio/" , $request->file('file_comprobante_de_domicilio'), $request->file('file_comprobante_de_domicilio')->getClientOriginalName());
                    $urlfile_comprobante_de_domicilio = Storage::disk('public')->url("/documentos/clientes/$numeroCurp/". substr(Carbon::now(), 0, 10) ."/$nombreCliente/comprobante de domicilio/".$request->file('file_comprobante_de_domicilio')->getClientOriginalName());
                }else{
                    $urlfile_comprobante_de_domicilio = null;
                }

                
                $creditRequest = new CreditRequest();
                $creditRequest->nombre = $request->nombre;
                $creditRequest->apellido_paterno = $request->apellido_paterno;
                $creditRequest->apellido_materno = $request->apellido_materno;
                $creditRequest->fecha_nacimiento = $request->fecha_nacimiento;
                $creditRequest->nacionalidad = $request->nacionalidad;
                $creditRequest->correo = $request->correo;
                $creditRequest->celular = $request->celular;
                $creditRequest->telefono = $request->telefono;
                $creditRequest->file_comprobante_ine_identificacion =  $urlFile_comprobante_ine_identificacion;
                $creditRequest->text_comprobante_ine_identificacion = $request->text_comprobante_ine_identificacion;
                $creditRequest->tipo_identificacion_social = $request->tipo_identificacion_social;
                $creditRequest->numero_curp = $request->numero_curp;
                $creditRequest->file_comprobante_domicilio_cliente = $urlfile_comprobante_domicilio_cliente ;
                $creditRequest->direccion_domicilio = $request->direccion_domicilio;
                $creditRequest->relacion_per_del_titular_del_compro_domicilio_cliente = $request->relacion_per_del_titular_del_compro_domicilio_cliente;
                $creditRequest->relacion_per_del_titular_del_compro_domicilio_domicilio = $request->relacion_per_del_titular_del_compro_domicilio_domicilio;
                $creditRequest->file_caratula_del_estado_de_cuenta = $urlfile_caratula_del_estado_de_cuenta;
                $creditRequest->principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado = $request->principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado;
                $creditRequest->cual_es_producto_que_esta_solicitando = $request->cual_es_producto_que_esta_solicitando;
                $creditRequest->escala_de_referencia_gerente_de_cliente_ola = $request->escala_de_referencia_gerente_de_cliente_ola;
                $creditRequest->justificar_la_referencia = $request->justificar_la_referencia;
                $creditRequest->quien_lo_refiere = $request->quien_lo_refiere;
                $creditRequest->desde_cuando_lo_conoce_por_que_lo_refiere = $request->desde_cuando_lo_conoce_por_que_lo_refiere;
                $creditRequest->tiene_redes_sociales = $request->tiene_redes_sociales;
                $creditRequest->ciudad = $request->ciudad;
                $creditRequest->monto_de_dinero_solicitado = $request->monto_de_dinero_solicitado;
                $creditRequest->razon_del_prestamo = $request->razon_del_prestamo;
                $creditRequest->hace_cuanto_tiempo_debe_ese_dinero = $request->hace_cuanto_tiempo_debe_ese_dinero;
                $creditRequest->a_que_institucion_le_debe_ese_dinero = $request->a_que_institucion_le_debe_ese_dinero;
                $creditRequest->cuenta_con_una_cuenta_bancaria = $request->cuenta_con_una_cuenta_bancaria;
                $creditRequest->respuesta_de_cuenta_con_una_cuenta_bancaria = $request->respuesta_de_cuenta_con_una_cuenta_bancaria;
                $creditRequest->numero_de_cuenta_clabe = $request->numero_de_cuenta_clabe;
                $creditRequest->numero_de_cuenta = $request->numero_de_cuenta;
                $creditRequest->file_foto_de_tarjeta =  $urlfile_foto_de_tarjeta;
                $creditRequest->por_que_no_ha_popido_pagar_su_deuda = $request->por_que_no_ha_popido_pagar_su_deuda;
                $creditRequest->nombre_apellido_del_obligado_solidario = $request->nombre_apellido_del_obligado_solidario;
                $creditRequest->telefono_obligado_solidario = $request->telefono_obligado_solidario;
                $creditRequest->correo_obligado_solidario = $request->correo_obligado_solidario;
                $creditRequest->nacionalidad_obligado_solidario = $request->nacionalidad_obligado_solidario;
                $creditRequest->tipo_de_identificacion_oficial = $request->tipo_de_identificacion_oficial;
                $creditRequest->file_comprobante_ine_o_identificacion_oficial = $urlfile_comprobante_ine_o_identificacion_oficial ;
                $creditRequest->ine_o_identificacion_oficial = $request->ine_o_identificacion_oficial;
                $creditRequest->numero_curp_obligado = $request->numero_curp_obligado;
                $creditRequest->file_comprobante_de_domicilio =  $urlfile_comprobante_de_domicilio;
                $creditRequest->domicilio = $request->domicilio;
                $creditRequest->uui = (string) Str::uuid();



                $credenciales = [
                    "name" => $request->nombre,
                    "email" => $request->correo,
                    "password" => $request->numero_curp,
                    "password_confirmation" => $request->numero_curp,
                    'raw_rol' => 'cliente',
                    "terms" => false,
                ];
                $login = [
                    "email" => $request->correo,
                    "password" => $request->numero_curp,
                ];
                (new CreateNewUser)->create($credenciales);
                $user = User::where('email', $request->correo)->first();
                $creditRequest->user_id = $user->id;
                $creditRequest->save();
                
                
                $amortizaicones = json_decode($request->amortizaciones);
                for ($i=0; $i < count($amortizaicones) ; $i++) { 
                    $element  = $amortizaicones[$i];
                    $amort = new Aamortizacion();
                    $amort->periodo = $element->periodo;
                    $amort->interes = $element->interes;
                    $amort->capital = $element->capital;
                    $amort->pago = $element->pago;
                    $amort->dias_pago = date("Y-m-d", strtotime($element->dias_pago));
                    $amort->uui_credit_request = $creditRequest->uui;
                    $amort->credit_request_id = $creditRequest->id;
                    $amort->save();
                }
                return response()->json($login);
            }, 5);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function enviarCreditoAEstudio(Request $request)
    {
        return DB::transaction(function () use($request) {
            $SolicitudDeCredito = new CreditRequest();
            $SolicitudDeCredito->find($request->id_credito)->update([
                "status" => 1
            ]);
        });
    }
    public function rechazarCredito(Request $request)
    {
        return DB::transaction(function () use($request) {
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
