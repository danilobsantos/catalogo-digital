<?php

declare(strict_types=1);

use App\Domains\Analytics\Http\Controllers\AnalyticsController;
use App\Domains\Catalog\Http\Controllers\CatalogController;
use App\Domains\Content\Http\Controllers\PageController;
use App\Domains\SEO\Http\Controllers\SeoController;
use App\Http\Controllers\HomeController;
use App\Livewire\Public\Catalog\Search;
use App\Livewire\Public\Content\ContactForm;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas — Catálogo Digital Premium (precisa VIR antes do catch-all)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/produtos', Search::class)->name('public.products.index');
Route::get('/produtos/{product:slug}', [CatalogController::class, 'show'])->name('public.products.show');
Route::get('/categorias', [CatalogController::class, 'categories'])->name('public.categories.index');
Route::get('/categorias/{category:slug}', [CatalogController::class, 'categoryShow'])->name('public.categories.show');
Route::get('/marcas', [CatalogController::class, 'brands'])->name('public.brands.index');
Route::get('/marcas/{brand:slug}', [CatalogController::class, 'brandShow'])->name('public.brands.show');

/*
|--------------------------------------------------------------------------
| Página de Contato (formulário Livewire)
|--------------------------------------------------------------------------
*/
Route::get('/contato', ContactForm::class)->name('public.contact');

/*
|--------------------------------------------------------------------------
| SEO público
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');

/*
|--------------------------------------------------------------------------
| Analytics público (event tracking)
|--------------------------------------------------------------------------
*/
Route::post('/api/track', [AnalyticsController::class, 'track'])->name('public.analytics.track');

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Rotas do usuário autenticado (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/profile.php';

/*
|--------------------------------------------------------------------------
| Rotas Admin — vem ANTES do catch-all de pages
|--------------------------------------------------------------------------
*/
require __DIR__.'/admin.php';

/*
|--------------------------------------------------------------------------
| Páginas Institucionais (catch-all slug) — sempre POR ÚLTIMO
|--------------------------------------------------------------------------
|  Garante que /login, /register, /admin/*, /produtos/*, etc., sejam
|  resolvidos pelas rotas específicas antes de cair aqui.
*/
Route::get('/{page:slug}', [PageController::class, 'show'])
    ->name('public.page.show');
