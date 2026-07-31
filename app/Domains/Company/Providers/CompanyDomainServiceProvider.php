<?php

declare(strict_types=1);

namespace App\Domains\Company\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

final class CompanyDomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Mapeia Models em App\Domains\*\Models\* para o namespace de factories
        // único em Database\Factories\{ModelBasename}Factory.
        Factory::guessFactoryNamesUsing(function (string $modelName): string {
            if (! str_starts_with($modelName, 'App\\Domains\\')) {
                return 'Database\\Factories\\'.class_basename($modelName).'Factory';
            }

            return 'Database\\Factories\\'.Str::afterLast($modelName, '\\').'Factory';
        });
    }
}
