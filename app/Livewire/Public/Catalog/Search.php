<?php

declare(strict_types=1);

namespace App\Livewire\Public\Catalog;

use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Collection as CollectionModel;
use App\Domains\Catalog\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Catálogo público com busca + faceted filtering reativos.
 *
 *  - QueryString persiste `?q=...&categoria=...` na URL — indexável por SEO.
 *  - Facetas pré-renderizadas com base em FacetSelector (couro, solado, variantes).
 *  - Usa `WithPagination` para SEO-friendly prev/next.
 */
#[Title('Catálogo · CJ Calçados')]
#[Layout('components.layouts.public')]
final class Search extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'livewire.public.catalog.pagination';
    }

    /** URL-bound (impacta SEO): termo de busca livre. */
    #[Url(as: 'q')]
    public string $q = '';

    /** URL-bound: categoria por slug. */
    #[Url(as: 'categoria')]
    public string $category = '';

    /** URL-bound: coleção por slug. */
    #[Url(as: 'colecao')]
    public string $collection = '';

    /** URL-bound: couro. */
    #[Url(as: 'couro')]
    public string $leather = '';

    /** URL-bound: solado. */
    #[Url(as: 'solado')]
    public string $sole = '';

    /** URL-bound: somente produtos com C.A. */
    #[Url(as: 'ca')]
    public bool $hasCa = false;

    /** URL-bound: somente novidades. */
    #[Url(as: 'novo')]
    public bool $onlyNew = false;

    /** URL-bound: somente destaques. */
    #[Url(as: 'destaque')]
    public bool $onlyFeatured = false;

    /** URL-bound: ordenação: relevance / newest / oldest / views. */
    #[Url(as: 'ordem')]
    public string $sort = 'relevance';

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedCollection(): void
    {
        $this->resetPage();
    }

    public function updatedLeather(): void
    {
        $this->resetPage();
    }

    public function updatedSole(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['q', 'category', 'collection', 'leather', 'sole', 'hasCa', 'onlyNew', 'onlyFeatured']);
        $this->resetPage();
    }

    /** @return LengthAwarePaginator<Product> */
    public function render(): View
    {
        $products = Product::query()
            ->with('images')
            ->when($this->q !== '', fn ($qBuilder) => $qBuilder->where(function ($qq) {
                $qq->where('name', 'ILIKE', '%'.$this->q.'%')
                    ->orWhere('short_description', 'ILIKE', '%'.$this->q.'%')
                    ->orWhere('description', 'ILIKE', '%'.$this->q.'%')
                    ->orWhere('code', 'ILIKE', '%'.$this->q.'%');
            }))
            ->when($this->category !== '', function ($qq) {
                $qq->whereHas('category', fn ($q) => $q->where('slug', $this->category));
            })
            ->when($this->collection !== '', function ($qq) {
                $qq->whereHas('collection', fn ($q) => $q->where('slug', $this->collection));
            })
            ->when($this->leather !== '', fn ($qq) => $qq->where('leather', 'ILIKE', $this->leather))
            ->when($this->sole !== '', fn ($qq) => $qq->where('sole', 'ILIKE', $this->sole))
            ->when($this->hasCa, fn ($qq) => $qq->where('has_ca', true))
            ->when($this->onlyNew, fn ($qq) => $qq->where('is_new', true))
            ->when($this->onlyFeatured, fn ($qq) => $qq->where('is_featured', true))
            ->where('is_active', true)
            ->when($this->sort === 'newest', fn ($qq) => $qq->orderByDesc('created_at'))
            ->when($this->sort === 'oldest', fn ($qq) => $qq->orderBy('created_at'))
            ->when($this->sort === 'views', fn ($qq) => $qq->orderByDesc('view_count'))
            ->when(! in_array($this->sort, ['newest', 'oldest', 'views'], true),
                fn ($qq) => $qq->orderBy('sort_order')->orderByDesc('created_at'))
            ->paginate((int) config('catalog.ui.items_per_page', 12))
            ->withQueryString();

        // Facetas: agregados a partir do scope já filtrado (com mesma base).
        $counts = Product::query()
            ->where('is_active', true)
            ->selectRaw('trim(leather) as leather, count(*) as c')
            ->whereNotNull('leather')
            ->whereRaw("trim(leather) <> ''")
            ->groupBy(DB::raw('trim(leather)'))
            ->orderBy('c', 'desc')
            ->limit(10)
            ->get();

        $soles = Product::query()
            ->where('is_active', true)
            ->selectRaw('trim(sole) as sole, count(*) as c')
            ->whereNotNull('sole')
            ->whereRaw("trim(sole) <> ''")
            ->groupBy(DB::raw('trim(sole)'))
            ->orderBy('c', 'desc')
            ->limit(10)
            ->get();

        $categories = Category::query()->where('is_active', true)->orderBy('name')->get(['slug', 'name']);
        $collections = CollectionModel::query()->where('is_active', true)->orderBy('name')->get(['slug', 'name']);

        return view('livewire.public.catalog.search', [
            'products' => $products,
            'leathers' => $counts,
            'soles' => $soles,
            'categories' => $categories,
            'collections' => $collections,
        ]);
    }
}
