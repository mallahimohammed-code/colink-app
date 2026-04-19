<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competence extends Model
{
    /** @use HasFactory<\Database\Factories\CompetenceFactory> */
    use HasFactory;

    protected $fillable = [
        'nom',
        'categorie',
    ];

    public function profils(): BelongsToMany
    {
        return $this->belongsToMany(Profil::class, 'profil_competences')
            ->withPivot('niveau')
            ->withTimestamps();
    }

    public function profilCompetences(): HasMany
    {
        return $this->hasMany(ProfilCompetence::class);
    }
}
