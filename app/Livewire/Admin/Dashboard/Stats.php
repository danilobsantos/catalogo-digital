<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dashboard;

use App\Domains\Catalog\Models\Banner;
use App\Domains\Catalog\Models\Brand;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Collection as CollectionModel;
use App\Domains\Catalog\Models\Product;
use App\Traits\CompanyContext;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

/**
 * Snapshot de métricas e atividade recente para o painel administrativo.
 */
#[Title('Dashboard · Admin')]
#[Layout('components.layouts.admin')]
final class Stats extends Component
{
    /** @return array<string, int> */
    public function kpi(): array
    {
        $companyId = CompanyContext::id();

        return [
            'products' => Product::query()->count(),
            'active_products' => Product::query()->where('is_active', true)->count(),
            'ca_products' => Product::query()->where('has_ca', true)->count(),
            'new_products' => Product::query()->where('is_new', true)->count(),
            'featured_products' => Product::query()->where('is_featured', true)->count(),
            'categories' => Category::query()->count(),
            'collections' => CollectionModel::query()->count(),
            'brands' => Brand::query()->count(),
            'banners' => Banner::query()->count(),
            'active_banners' => Banner::query()->where('is_active', true)->count(),
            'images' => DB::table('product_images')->count(),
            'covers' => DB::table('product_images')->where('is_cover', true)->count(),
        ];
    }

    public function render(): View
    {
        $recent = Product::query()
            ->with('images')
            ->latest('updated_at')
            ->take(6)
            ->get();

        $topViewed = Product::query()
            ->orderByDesc('view_count')
            ->take(5)
            ->get();

        $activities = Activity::query()
            ->with('subject', 'causer')
            ->latest()
            ->take(12)
            ->get();

        return view('livewire.admin.dashboard.stats', [
            'kpi' => $this->kpi(),
            'recent' => $recent,
            'topViewed' => $topViewed,
            'activities' => $activities,
        ]);
    }
}
