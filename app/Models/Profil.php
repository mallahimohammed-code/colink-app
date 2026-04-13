<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $fillable = [
        'user_id',
        'titre',
        'bio',
        'localisation',
        'disponible',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
