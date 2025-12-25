<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ServerTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Token de acceso no proporcionado.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $hashedToken = hash('sha256', $token);

        /** @var ?User $user */
        $user = User::where('api_token', $hashedToken)->first();

        if (!$user || !$user->isServer() || !$user->isActive()) {
            return response()->json([
                'message' => 'Token inválido o sin permisos.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $request->setUserResolver(fn () => $user);
        auth()->setUser($user);

        return $next($request);
    }
}
