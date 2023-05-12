<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentosDeBancoDeDatos extends Model
{
    use HasFactory;

    public function hasOneStatusDocument()
    {
        return $this->hasOne(StatusDocumento::class, 'documentos_de_banco_de_datos_id');
    }
}
