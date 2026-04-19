<?php

namespace App\Listeners;

use App\Events\CandidatureDeposee;
use Illuminate\Support\Facades\Log;

class LogCandidatureDeposee
{
    public function handle(CandidatureDeposee $event): void
    {
        $candidature = $event->candidature->loadMissing(['profil.user', 'offre']);

        $candidat = $candidature->profil?->user?->name ?? 'Inconnu';
        $offreTitre = $candidature->offre?->titre ?? 'Offre inconnue';

        Log::channel('candidatures')->info('Candidature déposée', [
            'date' => now()->toDateTimeString(),
            'candidat' => $candidat,
            'offre' => $offreTitre,
            'candidature_id' => $candidature->id,
        ]);
    }
}
