<?php

namespace App\Http\Controllers;

use App\Models\Competence;
use App\Models\Profil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = Auth::guard('api')->user();

        if ($user->profil()->exists()) {
            return response()->json(['message' => 'Profil déjà créé.'], 409);
        }

        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'localisation' => ['required', 'string', 'max:255'],
            'disponible' => ['sometimes', 'boolean'],
        ]);

        $profil = $user->profil()->create($data);

        return response()->json($profil->load('competences'), 201);
    }

    public function show(): JsonResponse
    {
        $profil = Auth::guard('api')->user()->profil()->with('competences')->first();

        if (! $profil) {
            return response()->json(['message' => 'Profil introuvable.'], 404);
        }

        return response()->json($profil);
    }

    public function update(Request $request): JsonResponse
    {
        $profil = Auth::guard('api')->user()->profil;

        if (! $profil) {
            return response()->json(['message' => 'Profil introuvable.'], 404);
        }

        $data = $request->validate([
            'titre' => ['sometimes', 'required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'localisation' => ['sometimes', 'required', 'string', 'max:255'],
            'disponible' => ['sometimes', 'boolean'],
        ]);

        $profil->update($data);

        return response()->json($profil->load('competences'));
    }

    public function attachCompetence(Request $request): JsonResponse
    {
        $profil = Auth::guard('api')->user()->profil;

        if (! $profil) {
            return response()->json(['message' => 'Profil introuvable.'], 404);
        }

        $data = $request->validate([
            'competence_id' => ['required', 'integer', 'exists:competences,id'],
            'niveau' => ['required', 'in:debutant,intermediaire,expert'],
        ]);

        if ($profil->competences()->where('competences.id', $data['competence_id'])->exists()) {
            return response()->json(['message' => 'Compétence déjà ajoutée.'], 409);
        }

        $profil->competences()->attach($data['competence_id'], [
            'niveau' => $data['niveau'],
        ]);

        return response()->json($profil->load('competences'), 201);
    }

    public function detachCompetence(Competence $competence): JsonResponse
    {
        $profil = Auth::guard('api')->user()->profil;

        if (! $profil) {
            return response()->json(['message' => 'Profil introuvable.'], 404);
        }

        $profil->competences()->detach($competence->id);

        return response()->json(['message' => 'Compétence retirée.']);
    }
}
