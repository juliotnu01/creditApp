<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BancoDeDatos extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function hasManyDocument()
    {
        return $this->hasMany(DocumentosDeBancoDeDatos::class, 'bando_de_datos_id');
    }
}