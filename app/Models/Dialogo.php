<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialogo extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function belongToMensaje()
    {
        return $this->belongsTo(Mensaje::class, 'mensaje_id');
    }
    public function respuesta()
    {
        return $this->hasOne(Dialogo::class, 'dialogo_padre_id');
    }
}
