<?php

namespace App\Listeners;

use App\Events\StatutCandidatureMis;
use Illuminate\Support\Facades\Log;

class LogStatutCandidatureMis
{
    public function handle(StatutCandidatureMis $event): void
    {
        Log::channel('candidatures')->info('Statut de candidature modifié', [
            'date' => now()->toDateTimeString(),
            'candidature_id' => $event->candidature->id,
            'ancien_statut' => $event->ancienStatut,
            'nouveau_statut' => $event->nouveauStatut,
        ]);
    }
}
