<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Mensaje, Dialogo};
use DB;

class MensajeController extends Controller
{

    public function index(){
        try {
            $mensaje = Mensaje::with(['emisor', 'receptor', 'dialogo:id,dialogo_emisor,created_at,updated_at,mensaje_id', 'dialogo.respuesta:id,dialogo_receptor,dialogo_padre_id,created_at,updated_at'])->get();
            return response()->json($mensaje);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function store(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $mensaje = new Mensaje();
                $mensaje->user_emisor_id = $request['emisor']['id'];
                $mensaje->user_receptor_id = $request['receptor']['id'];
                $mensaje->leido = false;
                $mensaje->save();

                $dialogo = new Dialogo();
                $dialogo->dialogo_emisor = $request['mensaje'];
                $dialogo->dialogo_emisor_id = $request['emisor']['id'];
                $dialogo->mensaje_id = $mensaje->id;
                $dialogo->save();
            }, 5);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function ResponderMensaje(Request $request)
    {
        try {
            return DB::transaction(function () use ($request){
                $mensaje = Mensaje::with(['emisor', 'receptor', 'dialogo:id,dialogo_emisor,created_at,updated_at,mensaje_id', 'dialogo.respuesta:id,dialogo_receptor,dialogo_padre_id,created_at,updated_at'])->latest()->first();
                if(!collect($mensaje->dialogo)->last()->respuesta){

                    $dialogo = new Dialogo();
                    $dialogo->dialogo_receptor = $request['respuesta'];
                    $dialogo->dialogo_receptor_id = $request['receptor']['id'];
                    $dialogo->mensaje_id = $request['mensaje'][0]['mensaje_id'];
                    $dialogo->dialogo_padre_id = collect($mensaje->dialogo)->last()->id;
                    $dialogo->save();
                    
                }else{
                    $dialogo = new Dialogo();
                    $dialogo->dialogo_emisor = $request['respuesta'];
                    $dialogo->dialogo_emisor_id = $request['receptor']['id'];
                    $dialogo->mensaje_id = $request['mensaje'][0]['mensaje_id'];
                    $dialogo->save();
                }
            },5);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
