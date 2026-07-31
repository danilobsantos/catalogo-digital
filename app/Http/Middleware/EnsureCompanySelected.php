<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Garante que o usuário tenha um `active_company_id` válido antes de entrar
 * em rotas corporativas. Resolve automaticamente uma Company se o usuário
 * pertence a apenas uma, e dispara 422 com payload estruturado caso contrário.
 */
final class EnsureCompanySelected
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = $request->user();

        if ($user === null) {
            return $next($request);
        }

        if ($user->active_company_id !== null) {
            return $next($request);
        }

        $autoPick = $user->companies()->first();
        if ($autoPick !== null) {
            $user->forceFill(['active_company_id' => $autoPick->id])->save();

            return $next($request);
        }

        return response()->view('errors.no-company', [
            'message' => 'Você precisa pertencer a uma empresa para acessar o painel.',
        ], 422);
    }
}
