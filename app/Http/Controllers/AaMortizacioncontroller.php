<?php

namespace App\Http\Controllers;

use App\Models\Aamortizacion;
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
                $urlrecibo_de_pago_user = Storage::disk('public')->url("/documentos/recibos de pago/$request->uui_credit_request/".$request->file('recibo_de_pago_user')->getClientOriginalName());
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
}
