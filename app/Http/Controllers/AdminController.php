<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'users' => [
                'total' => User::query()->count(),
                'by_role' => [
                    'admin' => User::query()->where('role', 'admin')->count(),
                    'recruteur' => User::query()->where('role', 'recruteur')->count(),
                    'candidat' => User::query()->where('role', 'candidat')->count(),
                ],
            ],
            'offres' => [
                'total' => Offre::query()->count(),
                'active' => Offre::query()->where('actif', true)->count(),
                'inactive' => Offre::query()->where('actif', false)->count(),
            ],
            'candidatures' => [
                'total' => Candidature::query()->count(),
                'by_statut' => [
                    'en_attente' => Candidature::query()->where('statut', 'en_attente')->count(),
                    'acceptee' => Candidature::query()->where('statut', 'acceptee')->count(),
                    'refusee' => Candidature::query()->where('statut', 'refusee')->count(),
                ],
            ],
        ]);
    }

    public function users(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'role' => ['nullable', 'in:candidat,recruteur,admin'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $users = User::query()
            ->when($filters['role'] ?? null, fn ($q, $v) => $q->where('role', $v))
            ->when($filters['search'] ?? null, fn ($q, $v) => $q->where(function ($w) use ($v) {
                $w->where('name', 'like', "%{$v}%")
                    ->orWhere('email', 'like', "%{$v}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return response()->json($users);
    }

    public function destroyUser(User $user): JsonResponse
    {
        if ($user->id === Auth::guard('api')->id()) {
            return response()->json(['message' => 'Impossible de supprimer son propre compte.'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé.']);
    }

    public function toggleOffre(Offre $offre): JsonResponse
    {
        $offre->update(['actif' => ! $offre->actif]);

        return response()->json($offre);
    }
}