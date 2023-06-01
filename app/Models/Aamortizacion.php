<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aamortizacion extends Model
{
    use HasFactory;

    protected $table = "amortizacions";

    protected $guarded = [];

    public function hasOneScore()
    {
        return $this->hasOne(ScoreAmortizacion::class, 'amortizacions_id');
    }
    public function hasManyCompromisos()
    {
        return $this->hasMany(Compromiso::class, 'amortizacion_id');
    }
}
