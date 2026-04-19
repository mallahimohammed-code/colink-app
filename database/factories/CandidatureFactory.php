<?php

namespace Database\Factories;

use App\Models\Offre;
use App\Models\Profil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidature>
 */
class CandidatureFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'offre_id' => Offre::factory(),
            'profil_id' => Profil::factory(),
            'message' => fake()->optional(0.85)->paragraph(),
            'statut' => fake()->randomElement(['en_attente', 'acceptee', 'refusee']),
        ];
    }

    public function enAttente(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'en_attente',
        ]);
    }
}
