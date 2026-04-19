<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * @param  string  ...$roleParts  e.g. role:admin or role:admin,recruteur
     */
    public function handle(Request $request, Closure $next, string ...$roleParts): Response
    {
        $user = auth('api')->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $allowed = collect($roleParts)
            ->flatMap(fn (string $chunk) => preg_split('/[|,]/', $chunk))
            ->map(fn (string $r) => trim($r))
            ->filter()
            ->values()
            ->all();

        if ($allowed !== [] && ! in_array($user->role, $allowed, true)) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}