<?php

declare(strict_types=1);

use App\Domains\Analytics\Http\Controllers\AnalyticsExportController;
use App\Livewire\Admin\Banners\Form as BannerForm;
use App\Livewire\Admin\Banners\Index as BannersIndex;
use App\Livewire\Admin\Brands\Form as BrandForm;
use App\Livewire\Admin\Brands\Index as BrandsIndex;
use App\Livewire\Admin\Categories\Form as CategoryForm;
use App\Livewire\Admin\Categories\Index as CategoriesIndex;
use App\Livewire\Admin\Collections\Form as CollectionForm;
use App\Livewire\Admin\Collections\Index as CollectionsIndex;
use App\Livewire\Admin\Dashboard\Stats;
use App\Livewire\Admin\Marketing\Dashboard as MarketingDashboard;
use App\Livewire\Admin\Products\Form as ProductForm;
use App\Livewire\Admin\Products\Index as ProductsIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Painel Administrativo (autenticado + empresa-ativa + permissão por papel)
|--------------------------------------------------------------------------
|
|  - Dashboard/stats:  super-admin | company-admin | editor
|  - Products/Categories/Brands/Collections:  super-admin | company-admin | editor
|  - Banners:           super-admin | company-admin  (editor não gerencia)
*/
Route::middleware(['auth', 'ensure.company'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/marketing', MarketingDashboard::class)->middleware('role:super-admin|company-admin')->name('marketing');
    Route::get('/analytics/export', [AnalyticsExportController::class, 'csv'])
        ->middleware('role:super-admin|company-admin')->name('marketing.export');

    Route::get('/', Stats::class)->middleware('role:super-admin|company-admin|editor')->name('dashboard');

    Route::prefix('produtos')->name('products.')->middleware('role:super-admin|company-admin|editor')->group(function (): void {
        Route::get('/', ProductsIndex::class)->name('index');
        Route::get('/novo', ProductForm::class)->name('create');
        Route::get('/{product}/editar', ProductForm::class)->name('edit');
    });

    Route::prefix('categorias')->name('categories.')->middleware('role:super-admin|company-admin|editor')->group(function (): void {
        Route::get('/', CategoriesIndex::class)->name('index');
        Route::get('/nova', CategoryForm::class)->name('create');
        Route::get('/{category}/editar', CategoryForm::class)->name('edit');
    });

    Route::prefix('marcas')->name('brands.')->middleware('role:super-admin|company-admin|editor')->group(function (): void {
        Route::get('/', BrandsIndex::class)->name('index');
        Route::get('/nova', BrandForm::class)->name('create');
        Route::get('/{brand}/editar', BrandForm::class)->name('edit');
    });

    Route::prefix('colecoes')->name('collections.')->middleware('role:super-admin|company-admin|editor')->group(function (): void {
        Route::get('/', CollectionsIndex::class)->name('index');
        Route::get('/nova', CollectionForm::class)->name('create');
        Route::get('/{collection}/editar', CollectionForm::class)->name('edit');
    });

    Route::prefix('banners')->name('banners.')->middleware('role:super-admin|company-admin')->group(function (): void {
        Route::get('/', BannersIndex::class)->name('index');
        Route::get('/novo', BannerForm::class)->name('create');
        Route::get('/{banner}/editar', BannerForm::class)->name('edit');
    });
});

/*
|--------------------------------------------------------------------------
| Dashboard alias (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'ensure.company'])->get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');
