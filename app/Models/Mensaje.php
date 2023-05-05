<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function emisor()
    {
        return $this->belongsTo(User::class, 'user_emisor_id');
    }
    public function receptor()
    {
        return $this->belongsTo(User::class, 'user_receptor_id');
    }
    public function dialogo()
    {
        return $this->hasMany(Dialogo::class, 'mensaje_id');
    }
}
