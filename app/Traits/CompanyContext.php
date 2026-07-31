<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\User;

/**
 * Wrapper estático para acessar o `company_id` ativo da request atual
 * ou do contexto console (seeds, jobs, commands).
 *
 * Permite que models que usam {@see HasCompanyScope} atribuam
 * automaticamente o contexto de tenant em eventos `creating`.
 */
final class CompanyContext
{
    private static ?int $fallbackId = null;

    public static function id(): ?int
    {
        /** @var User|null $user */
        $user = auth()->user();
        if ($user !== null && $user->active_company_id !== null) {
            return (int) $user->active_company_id;
        }

        if (app()->runningInConsole() && self::$fallbackId === null) {
            self::$fallbackId = (int) (config('catalog.console_fallback_company_id') ?? 0) ?: null;
        }

        return self::$fallbackId;
    }

    public static function setFallback(?int $id): void
    {
        self::$fallbackId = $id;
    }
}
