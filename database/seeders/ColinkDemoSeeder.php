<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\Offre;
use App\Models\Profil;
use App\Models\User;
use Illuminate\Database\Seeder;

class ColinkDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCompetences();

        User::factory()->admin()->create([
            'name' => 'Admin One',
            'email' => 'admin1@colink.test',
        ]);

        User::factory()->admin()->create([
            'name' => 'Admin Two',
            'email' => 'admin2@colink.test',
        ]);

        $recruiters = User::factory()->count(5)->recruteur()->create();

        foreach ($recruiters as $user) {
            Offre::factory()
                ->count(fake()->numberBetween(2, 3))
                ->for($user)
                ->create();
        }

        $candidates = User::factory()->count(10)->candidat()->create();

        $competences = Competence::query()->get();

        foreach ($candidates as $user) {
            $profil = Profil::factory()->for($user)->create();

            $count = min(fake()->numberBetween(3, 6), $competences->count());
            $picked = $competences->random($count);

            foreach ($picked as $competence) {
                $profil->competences()->attach($competence->id, [
                    'niveau' => fake()->randomElement(['debutant', 'intermediaire', 'expert']),
                ]);
            }
        }
    }

    private function seedCompetences(): void
    {
        $items = [
            ['nom' => 'PHP', 'categorie' => 'Langage'],
            ['nom' => 'JavaScript', 'categorie' => 'Langage'],
            ['nom' => 'TypeScript', 'categorie' => 'Langage'],
            ['nom' => 'Python', 'categorie' => 'Langage'],
            ['nom' => 'Java', 'categorie' => 'Langage'],
            ['nom' => 'Laravel', 'categorie' => 'Framework'],
            ['nom' => 'Symfony', 'categorie' => 'Framework'],
            ['nom' => 'React', 'categorie' => 'Framework'],
            ['nom' => 'Vue.js', 'categorie' => 'Framework'],
            ['nom' => 'Node.js', 'categorie' => 'Framework'],
            ['nom' => 'SQL', 'categorie' => 'Base de données'],
            ['nom' => 'Docker', 'categorie' => 'Outils'],
            ['nom' => 'Git', 'categorie' => 'Outils'],
            ['nom' => 'Agile', 'categorie' => 'Méthodologie'],
            ['nom' => 'Scrum', 'categorie' => 'Méthodologie'],
            ['nom' => 'Figma', 'categorie' => 'Design'],
            ['nom' => 'HTML/CSS', 'categorie' => 'Design'],
        ];

        foreach ($items as $row) {
            Competence::firstOrCreate(
                ['nom' => $row['nom']],
                ['categorie' => $row['categorie']]
            );
        }
    }
}