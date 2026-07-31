<?php

declare(strict_types=1);

namespace App\Domains\Analytics\Http\Controllers;

use App\Domains\Analytics\Support\AnalyticsTracker;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AnalyticsController
{
    /**
     * Endpoint público para Event Tracking via fetch (Alpine x-init).
     */
    public function track(Request $request): JsonResponse
    {
        $data = $request->validate([
            'event' => ['required', 'string', 'max:32'],
            'payload' => ['nullable', 'array'],
            'path' => ['nullable', 'string', 'max:191'],
        ]);

        // Whitelisted events only.
        $allowed = ['view', 'search', 'whatsapp_click', 'banner_click', 'category_click', 'collection_click'];
        if (! in_array($data['event'], $allowed, true)) {
            return response()->json(['ok' => false, 'reason' => 'event not allowed'], 422);
        }

        if ($request->user() instanceof User) {
            $payload = array_merge($data['payload'] ?? [], [
                'user_id' => $request->user()->id,
            ]);
        } else {
            $payload = $data['payload'] ?? [];
        }

        AnalyticsTracker::track($data['event'], $payload, $data['path'] ?? null);

        return response()->json(['ok' => true]);
    }
}
