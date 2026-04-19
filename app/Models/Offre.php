<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offre extends Model
{
    /** @use HasFactory<\Database\Factories\OffreFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'localisation',
        'type',
        'actif',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
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
}
