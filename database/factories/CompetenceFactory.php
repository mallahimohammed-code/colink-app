<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Competence>
 */
class CompetenceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->unique()->words(2, true),
            'categorie' => fake()->randomElement([
                'Langage',
                'Framework',
                'Base de données',
                'Outils',
                'Méthodologie',
                'Design',
            ]),
        ];
    }
}
