<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Offre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidatureController extends Controller
{
    public function store(Request $request, Offre $offre): JsonResponse
    {
        if (! $offre->actif) {
            return response()->json(['message' => 'Offre inactive.'], 422);
        }

        $profil = Auth::guard('api')->user()->profil;

        if (! $profil) {
            return response()->json(['message' => 'Profil requis pour postuler.'], 422);
        }

        $alreadyApplied = Candidature::query()
            ->where('offre_id', $offre->id)
            ->where('profil_id', $profil->id)
            ->exists();

        if ($alreadyApplied) {
            return response()->json(['message' => 'Déjà postulé à cette offre.'], 409);
        }

        $data = $request->validate([
            'message' => ['nullable', 'string'],
        ]);

        $candidature = Candidature::create([
            'offre_id' => $offre->id,
            'profil_id' => $profil->id,
            'message' => $data['message'] ?? null,
            'statut' => 'en_attente',
        ]);

        return response()->json($candidature, 201);
    }

    public function mine(): JsonResponse
    {
        $profil = Auth::guard('api')->user()->profil;

        if (! $profil) {
            return response()->json(['data' => [], 'message' => 'Aucun profil.'], 200);
        }

        $candidatures = Candidature::query()
            ->where('profil_id', $profil->id)
            ->with(['offre:id,user_id,titre,localisation,type,actif'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return response()->json($candidatures);
    }

    public function forOffre(Offre $offre): JsonResponse
    {
        if ($offre->user_id !== Auth::guard('api')->id()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $candidatures = $offre->candidatures()
            ->with(['profil:id,user_id,titre,localisation,disponible', 'profil.user:id,name,email'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return response()->json($candidatures);
    }

    public function updateStatut(Request $request, Candidature $candidature): JsonResponse
    {
        $candidature->loadMissing('offre');

        if (! $candidature->offre || $candidature->offre->user_id !== Auth::guard('api')->id()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $data = $request->validate([
            'statut' => ['required', 'in:en_attente,acceptee,refusee'],
        ]);

        $candidature->update(['statut' => $data['statut']]);

        return response()->json($candidature);
    }
}
