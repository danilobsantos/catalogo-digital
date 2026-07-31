<?php

declare(strict_types=1);

namespace App\Domains\Analytics\Http\Controllers;

use App\Domains\Analytics\Models\AnalyticsEvent;
use App\Traits\CompanyContext;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class AnalyticsExportController
{
    public function csv(Request $request): StreamedResponse
    {
        $this->authorize();

        $window = (string) $request->query('window', '7d');
        $since = match ($window) {
            '30d' => now()->subDays(30),
            '24h' => now()->subDay(),
            'all' => now()->subYears(10),
            default => now()->subDays(7),
        };

        $filename = "analytics-{$window}-".now()->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($since): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['occurred_at', 'event', 'session_id', 'product_id', 'slug', 'code', 'q', 'origin', 'path', 'ip']);

            AnalyticsEvent::query()
                ->where('occurred_at', '>=', $since)
                ->orderBy('id')
                ->chunkById(200, function ($events) use ($out): void {
                    foreach ($events as $e) {
                        $payload = $e->payload ?? [];
                        fputcsv($out, [
                            $e->occurred_at->toIso8601String(),
                            $e->event,
                            $e->session_id,
                            $payload['product_id'] ?? null,
                            $payload['slug'] ?? null,
                            $payload['code'] ?? null,
                            $payload['q'] ?? null,
                            $payload['origin'] ?? null,
                            $e->path,
                            $e->ip,
                        ]);
                    }
                });
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    private function authorize(): void
    {
        if (CompanyContext::id() === null) {
            abort(403);
        }
    }
}
