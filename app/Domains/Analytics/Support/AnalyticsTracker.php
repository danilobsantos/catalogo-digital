<?php

declare(strict_types=1);

namespace App\Domains\Analytics\Support;

use App\Domains\Analytics\Models\AnalyticsEvent;
use App\Traits\CompanyContext;
use Illuminate\Http\Request;

/**
 * Helper central para registrar eventos analíticos.
 *
 * Chamado a partir de Livewire actions / Controllers públicos.
 * Garante company_id correto e payload consistente.
 */
final class AnalyticsTracker
{
    /** @param array<string, mixed> $payload */
    public static function track(string $event, array $payload = [], ?string $path = null): void
    {
        $companyId = CompanyContext::id();
        if ($companyId === null) {
            return;
        }

        /** @var Request|null $request */
        $request = request();

        $sessionId = null;
        if ($request instanceof Request && $request->hasSession() && $request->session()->isStarted()) {
            $sessionId = $request->session()->getId();
        }

        AnalyticsEvent::create([
            'company_id' => $companyId,
            'event' => $event,
            'payload' => $payload,
            'session_id' => $sessionId,
            'path' => $path ?? $request?->path(),
            'referrer' => $request?->header('referer'),
            'ip' => $request?->ip(),
            'user_agent' => substr((string) $request?->userAgent(), 0, 1024),
            'occurred_at' => now(),
        ]);
    }
}
