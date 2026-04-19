<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffreController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'localisation' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'in:CDI,CDD,stage'],
            'sort' => ['nullable', 'in:asc,desc'],
        ]);

        $sort = $filters['sort'] ?? 'desc';

        $offres = Offre::query()
            ->where('actif', true)
            ->when($filters['localisation'] ?? null, fn ($q, $v) => $q->where('localisation', 'like', "%{$v}%"))
            ->when($filters['type'] ?? null, fn ($q, $v) => $q->where('type', $v))
            ->orderBy('created_at', $sort)
            ->with('user:id,name,email')
            ->paginate(10)
            ->withQueryString();

        return response()->json($offres);
    }

    public function show(Offre $offre): JsonResponse
    {
        return response()->json($offre->load('user:id,name,email'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'localisation' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:CDI,CDD,stage'],
            'actif' => ['sometimes', 'boolean'],
        ]);

        $offre = Auth::guard('api')->user()->offres()->create($data);

        return response()->json($offre, 201);
    }

    public function update(Request $request, Offre $offre): JsonResponse
    {
        if ($offre->user_id !== Auth::guard('api')->id()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $data = $request->validate([
            'titre' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'localisation' => ['sometimes', 'required', 'string', 'max:255'],
            'type' => ['sometimes', 'in:CDI,CDD,stage'],
            'actif' => ['sometimes', 'boolean'],
        ]);

        $offre->update($data);

        return response()->json($offre);
    }

    public function destroy(Offre $offre): JsonResponse
    {
        if ($offre->user_id !== Auth::guard('api')->id()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $offre->delete();

        return response()->json(['message' => 'Offre supprimée.']);
    }
}