<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aamortizacion extends Model
{
    use HasFactory;

    protected $table = "amortizacions";

    protected $guarded = [];
}
