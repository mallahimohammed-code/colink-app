<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profil extends Model
{
    /** @use HasFactory<\Database\Factories\ProfilFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'bio',
        'localisation',
        'disponible',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'disponible' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    public function profilCompetences(): HasMany
    {
        return $this->hasMany(ProfilCompetence::class);
    }

    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'profil_competences')
            ->withPivot('niveau')
            ->withTimestamps();
    }
}
