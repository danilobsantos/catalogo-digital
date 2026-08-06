<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\Catalog\Models\Banner;
use App\Domains\Catalog\Models\Brand;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Collection;
use App\Domains\Catalog\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            'user' => User::class,
            'product' => Product::class,
            'category' => Category::class,
            'brand' => Brand::class,
            'collection' => Collection::class,
            'banner' => Banner::class,
            // Fallback para mapeamentos legados ou limpos pelo MySQL (sem barra invertida)
            'AppModelsUser' => User::class,
            'App\Models\User' => User::class,
            'AppDomainsCatalogModelsProduct' => Product::class,
            'App\Domains\Catalog\Models\Product' => Product::class,
            'AppDomainsCatalogModelsCategory' => Category::class,
            'App\Domains\Catalog\Models\Category' => Category::class,
            'AppDomainsCatalogModelsBrand' => Brand::class,
            'App\Domains\Catalog\Models\Brand' => Brand::class,
            'AppDomainsCatalogModelsCollection' => Collection::class,
            'App\Domains\Catalog\Models\Collection' => Collection::class,
            'AppDomainsCatalogModelsBanner' => Banner::class,
            'App\Domains\Catalog\Models\Banner' => Banner::class,
        ]);

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
