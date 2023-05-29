<?php

namespace App\Http\Controllers;

use App\Models\{Aamortizacion,CreditRequest, ScoreAmortizacion};
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AaMortizacioncontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        try {
            return DB::transaction(function () use($request){
                $aamortizacion = new Aamortizacion();
                Storage::disk('public')->putFileAs("/documentos/recibos de pagos/$request->uui_credit_request/". substr(Carbon::now(), 0, 10) ."/" , $request->file('recibo_de_pago_user'), $request->file('recibo_de_pago_user')->getClientOriginalName());
                $urlrecibo_de_pago_user = asset(Storage::disk('public')->url("/documentos/recibos de pago/$request->uui_credit_request/".$request->file('recibo_de_pago_user')->getClientOriginalName()));
                $aamortizacion->find($request->id_recaudo)->update([
                    "ruta_recibo_de_pago" => $urlrecibo_de_pago_user,
                    "status" => 0,
                ]);
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function storeFactura(Request $request)
    {
        try {
            return DB::transaction(function () use($request){
                $aamortizacion = new Aamortizacion();
                Storage::disk('public')->putFileAs("/documentos/facturas de pagos/$request->uui_credit_request/". substr(Carbon::now(), 0, 10) ."/" , $request->file('factura_de_pago_user'), $request->file('factura_de_pago_user')->getClientOriginalName());
                $urlfactura_de_pago_user = asset(Storage::disk('public')->url("/documentos/facturas de pago/$request->uui_credit_request/".$request->file('factura_de_pago_user')->getClientOriginalName()));
                $aamortizacion->find($request->id_recaudo)->update([
                    "factura_de_recibo_de_pago" => $urlfactura_de_pago_user,
                    "status" => 1,
                ]);
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Aamortizacion $aamortizacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aamortizacion $aamortizacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aamortizacion $aamortizacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aamortizacion $aamortizacion)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function recalcularAmortizaciones(Request $request)
    {
        try {
            Aamortizacion::where('uui_credit_request', $request->uui_credit_request)->delete();
            CreditRequest::find($request['credit_request_id'])->update([
                "tipo_de_pago" => $request['tablaCalculo']['tipoPago'],
                "pagos_mensuales" => $request['tablaCalculo']['pagosMensuales'],
                "numeros_de_periodos" => $request['tablaCalculo']['periodos'],
                "meses" => $request['tablaCalculo']['Meses'],
                "interes_mensual" => $request['tablaCalculo']['InteresMensual'],
                "pago_total" => $request['tablaCalculo']['pagoTotal'],
                "pago_ola" => $request['tablaCalculo']['PagoOla'],
                "cuota" => $request['tablaCalculo']['cuota'],
            ]);

            for ($i = 0; $i < count($request->amortizaciones); $i++) {
                $element  = $request->amortizaciones[$i];
                
                $amort = new Aamortizacion();
                $amort->periodo = $element['periodo'];
                $amort->interes = $element['interes'];
                $amort->capital = $element['capital'];
                $amort->pago = $element['pago'];
                $amort->dias_pago = Carbon::createFromFormat('d \d\e F \d\e\l Y',$element['dias_pago'])->format('Y-m-d');
                $amort->uui_credit_request = $request['uui_credit_request'];
                $amort->credit_request_id = $request['credit_request_id'];
                $amort->save();
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addPagoComprobanteUser(Request $request)
    {
        try {
            return DB::transaction(function () use ($request){
                $data = json_decode($request->data);
                $file = $request->file('fileComprobante');
                
                if($file){
                    Storage::disk('public')->putFileAs("/comprobantes/fecha_comprobante_$data->fechaComprobante/creadito_$data->uui_credit_request/periodo_$data->periodo/". substr(Carbon::now(), 0, 10) ."/" , $file, $file->getClientOriginalName());
                    $urlComprobante = asset(Storage::disk('public')->url("/comprobantes/fecha_comprobante_$data->fechaComprobante/creadito_$data->uui_credit_request/periodo_$data->periodo/". substr(Carbon::now(), 0, 10) ."/".$file->getClientOriginalName()));
                }else {
                    $urlComprobante = '';
                }
                
                if($data->restante <= 0){
                    $fecha1 = Carbon::parse($data->dias_pago);
                    $fechaActual = Carbon::parse($data->fechaComprobante);
                    $diferenciaDias = $fechaActual->diffInDays($fecha1, false);
                    $amortizacion = new Aamortizacion();
                    $amortizacion->find($data->id)->update([
                        'ruta_recibo_de_pago' => $urlComprobante,
                        "fecha_comprobante" => $data->fechaComprobante,
                        "status" => 1,
                        "dias_pago_total" => $diferenciaDias,
                    ]);
                    if($data->has_one_score){
                        ScoreAmortizacion::find($data->has_one_score->id)->update([ "score" => ($data->has_one_score->score + $diferenciaDias), 'comentario' => "pago de la cuota el dia ". substr(Carbon::now(), 0, 10)  ]);
                    }else{
                        ScoreAmortizacion::create(["score" => $diferenciaDias, "amortizacions_id" => $data->id,'comentario' => "pago de la cuota el dia ". substr(Carbon::now(), 0, 10)  ]);
                    }
                }else{
                    $amortizacion = new Aamortizacion();
                    $amortizacion->find($data->id)->update([
                        'ruta_recibo_de_pago' => $urlComprobante,
                        "fecha_comprobante" => $data->fechaComprobante,
                        "status" => 0,
                        "pago" => $data->pago - $data->pagoCuota,
                        "restante" => $data->restante,
                    ]);
                    if($data->has_one_score){
                        ScoreAmortizacion::find($data->has_one_score->id)->update([ 'comentario' => "".$data->has_one_score->comentario.", abono de $data->pagoCuota el dia ". substr(Carbon::now(), 0, 10)  ]);
                    }else{
                        ScoreAmortizacion::create([ "amortizacions_id" => $data->id,'comentario' => "abono de $data->pagoCuota el dia ". substr(Carbon::now(), 0, 10)  ]);
                    }
                }
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
