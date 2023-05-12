<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Comentario;
use DB;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    public function store(Request $request)
    {
        try {
            return DB::transaction(function () use ($request){
                
                $comentario  = new Comentario();
                $comentario->comentario = $request['comentario'];
                $comentario->user_id = $request['user_id'];
                $comentario->user_id_did_comment = Auth::user()->id;
                $comentario->save();

            }, 5);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
