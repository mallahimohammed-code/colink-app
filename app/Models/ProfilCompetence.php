<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilCompetence extends Model
{
    protected $fillable = [
        'niveau',
        'profil_id',
        'competence_id',
    ];

    public function profil()
    {
        return $this->belongsTo(Profil::class);
    }

    public function competence()
    {
        return $this->belongsTo(Competence::class);
    }
}
