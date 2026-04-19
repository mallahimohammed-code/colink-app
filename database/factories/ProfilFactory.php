<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profil>
 */
class ProfilFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->candidat(),
            'titre' => fake()->jobTitle(),
            'bio' => fake()->paragraphs(2, true),
            'localisation' => fake()->city().', '.fake()->country(),
            'disponible' => fake()->boolean(85),
        ];
    }
}
