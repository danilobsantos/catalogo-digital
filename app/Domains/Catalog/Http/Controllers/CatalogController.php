<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Http\Controllers;

use App\Domains\Analytics\Support\AnalyticsTracker;
use App\Domains\Catalog\Models\Brand;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Product;
use Illuminate\Contracts\View\View;

final class CatalogController
{
    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->loadMissing('images', 'category', 'brand', 'collection');

        // Incrementa view_count + registra evento analítico (best-effort).
        $product->increment('view_count');

        AnalyticsTracker::track('view', [
            'product_id' => $product->id,
            'slug' => $product->slug,
            'code' => $product->code.($product->variant_code ? '-'.$product->variant_code : null),
        ]);

        return view('public.catalog.show', compact('product'));
    }

    public function categories(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('public.catalog.categories', compact('categories'));
    }

    public function categoryShow(Category $category): View
    {
        abort_unless($category->is_active, 404);

        $products = Product::query()
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->with('images')
            ->paginate((int) config('catalog.ui.items_per_page', 12))
            ->withQueryString();

        return view('public.catalog.category', compact('category', 'products'));
    }

    public function brands(): View
    {
        $brands = Brand::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('public.catalog.brands', compact('brands'));
    }

    public function brandShow(Brand $brand): View
    {
        abort_unless($brand->is_active, 404);

        $products = Product::query()
            ->where('brand_id', $brand->id)
            ->where('is_active', true)
            ->with('images')
            ->paginate((int) config('catalog.ui.items_per_page', 12))
            ->withQueryString();

        return view('public.catalog.brand', compact('brand', 'products'));
    }
}
