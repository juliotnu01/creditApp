<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BancoDeDatos extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function hasManyFileDocument()
    {
        return $this->hasMany(DocumentosDeBancoDeDatos::class, 'banco_de_datos_id');
    }
}