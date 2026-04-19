<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidature extends Model
{
    /** @use HasFactory<\Database\Factories\CandidatureFactory> */
    use HasFactory;

    protected $fillable = [
        'offre_id',
        'profil_id',
        'message',
        'statut',
    ];

    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class);
    }

    public function profil(): BelongsTo
    {
        return $this->belongsTo(Profil::class);
    }
}
