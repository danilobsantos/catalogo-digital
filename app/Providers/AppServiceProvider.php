<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\Catalog\Models\Banner;
use App\Domains\Catalog\Models\Brand;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Collection;
use App\Domains\Catalog\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        $this->registerTenantAwareBindings();
    }

    private function registerTenantAwareBindings(): void
    {
        $resolve = function (string $modelClass): \Closure {
            return function (string $value) use ($modelClass) {
                $query = $modelClass::withoutGlobalScopes();

                if (ctype_digit($value)) {
                    $query->where((new $modelClass)->getKeyName(), (int) $value);
                } else {
                    $query->where('slug', $value);
                }

                return $query->firstOrFail();
            };
        };

        Route::bind('product', $resolve(Product::class));
        Route::bind('category', $resolve(Category::class));
        Route::bind('brand', $resolve(Brand::class));
        Route::bind('collection', $resolve(Collection::class));
        Route::bind('banner', $resolve(Banner::class));
    }
}
