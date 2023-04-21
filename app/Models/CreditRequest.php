<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditRequest extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function belongToUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hasManyAmortizaciones()
    {
        return $this->hasMany(Aamortizacion::class, 'credit_request_id');
    }
    public function hasEstadoCredito()
    {
        return $this->hasOne(EstadoCreditoActivo::class, 'credit_request_id');
    }
}
