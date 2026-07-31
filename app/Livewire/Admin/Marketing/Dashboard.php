<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Marketing;

use App\Domains\Analytics\Models\AnalyticsEvent;
use App\Domains\Catalog\Models\Product;
use App\Traits\CompanyContext;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Painel de Marketing — eventos analíticos + exportação CSV.
 *
 * Métricas:
 *  - views_pagina (event=view nos produtos)
 *  - whatsapp_clicks (event=whatsapp_click)
 *  - search_count e top_terms
 *  - top produtos por view count
 *  - podem exportar evento → CSV
 */
#[Title('Marketing · Admin')]
#[Layout('components.layouts.admin')]
final class Dashboard extends Component
{
    public string $window = '7d';

    public function updatedWindow(): void
    {
        $this->dispatch('refresh');
    }

    private function since(): Carbon
    {
        return match ($this->window) {
            '30d' => now()->subDays(30),
            'all' => now()->subYears(10),
            default => now()->subDays(7),
        };
    }

    /** @return array<string, int|float> */
    public function kpi(): array
    {
        $since = $this->since();
        $companyId = CompanyContext::id();
        if ($companyId === null) {
            return [];
        }

        return [
            'views' => AnalyticsEvent::query()->where('event', 'view')->where('occurred_at', '>=', $since)->count(),
            'whatsapp_clicks' => AnalyticsEvent::query()->where('event', 'whatsapp_click')->where('occurred_at', '>=', $since)->count(),
            'searches' => AnalyticsEvent::query()->where('event', 'search')->where('occurred_at', '>=', $since)->count(),
            'banner_clicks' => AnalyticsEvent::query()->where('event', 'banner_click')
                ->where('occurred_at', '>=', $since)->count(),
        ];
    }

    public function render(): View
    {
        $since = $this->since();
        $companyId = CompanyContext::id();

        $topViewed = Product::query()
            ->orderByDesc('view_count')
            ->take(15)
            ->get();

        // Top termos de busca (do payload.q)
        $topSearches = AnalyticsEvent::query()
            ->where('event', 'search')
            ->where('occurred_at', '>=', $since)
            ->whereNotNull(DB::raw("payload->>'q'"))
            ->selectRaw("payload->>'q' as term, count(*) as c")
            ->groupBy('term')
            ->orderByDesc('c')
            ->take(15)
            ->get();

        // Eventos por dia
        $byDay = AnalyticsEvent::query()
            ->where('occurred_at', '>=', $since)
            ->selectRaw("date(occurred_at) as day, count(*) as c, sum(case when event='view' then 1 else 0 end) as views, sum(case when event='whatsapp_click' then 1 else 0 end) as clicks")
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('livewire.admin.marketing.dashboard', [
            'kpi' => $this->kpi(),
            'topViewed' => $topViewed,
            'topSearches' => $topSearches,
            'byDay' => $byDay,
            'window' => $this->window,
        ]);
    }
}
