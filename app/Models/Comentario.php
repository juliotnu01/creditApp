<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function WhoDidComment()
    {
        return $this->belongsTo(User::class, 'user_id_did_comment');
    }
}
