<?php

declare(strict_types=1);

namespace App\Domains\SEO\Providers;

use Illuminate\Support\ServiceProvider;

final class CatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../../../config/catalog.php', 'catalog');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../../../config/catalog.php' => config_path('catalog.php'),
        ], 'catalog-config');
    }
}
