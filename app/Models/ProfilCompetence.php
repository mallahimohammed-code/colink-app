<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilCompetence extends Model
{
    /** @use HasFactory<\Database\Factories\ProfilCompetenceFactory> */
    use HasFactory;

    protected $fillable = [
        'niveau',
        'profil_id',
        'competence_id',
    ];

    public function profil(): BelongsTo
    {
        return $this->belongsTo(Profil::class);
    }

    public function competence(): BelongsTo
    {
        return $this->belongsTo(Competence::class);
    }
}
