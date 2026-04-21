<?php

namespace App\Http\Controllers;

use App\Models\Competence;
use Illuminate\Http\JsonResponse;

class CompetenceController extends Controller
{
    public function index(): JsonResponse
    {
        $competences = Competence::query()
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get(['id', 'nom', 'categorie']);

        return response()->json($competences);
    }
}
