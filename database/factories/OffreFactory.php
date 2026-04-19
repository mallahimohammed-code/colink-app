<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offre>
 */
class OffreFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->recruteur(),
            'titre' => fake()->jobTitle(),
            'description' => collect(fake()->paragraphs(rand(3, 6)))->implode("\n\n"),
            'localisation' => fake()->city().', '.fake()->country(),
            'type' => fake()->randomElement(['CDI', 'CDD', 'stage']),
            'actif' => fake()->boolean(90),
        ];
    }
}
