<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Catalog\Models\Banner;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Product;
use Illuminate\Contracts\View\View;

final class HomeController
{
    public function index(): View
    {
        $banners = Banner::withoutCompanyScope()
            ->where('is_active', true)
            ->orderBy('position')
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (Banner $b): bool => $b->isVisible())
            ->values();

        $featuredCategories = Category::query()
            ->where('is_featured', true)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $featuredProducts = Product::query()
            ->where('is_featured', true)
            ->where('is_active', true)
            ->with('images')
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        $newArrivals = Product::query()
            ->where('is_new', true)
            ->where('is_active', true)
            ->with('images')
            ->latest('created_at')
            ->take(8)
            ->get();

        return view('public.home', compact('banners', 'featuredCategories', 'featuredProducts', 'newArrivals'));
    }
}
