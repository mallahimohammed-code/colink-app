<?php

namespace Database\Factories;

use App\Models\Competence;
use App\Models\Profil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProfilCompetence>
 */
class ProfilCompetenceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'profil_id' => Profil::factory(),
            'competence_id' => Competence::factory(),
            'niveau' => fake()->randomElement(['debutant', 'intermediaire', 'expert']),
        ];
    }
}
