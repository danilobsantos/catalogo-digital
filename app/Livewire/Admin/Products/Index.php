<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Products;

use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Collection as CollectionModel;
use App\Domains\Catalog\Models\Product;
use App\Traits\CompanyContext;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Lista paginada de produtos do tenant ativo.
 *
 * Filtros suportados:
 *  - search (nome / código / slug)
 *  - category_id
 *  - collection_id
 *  - only_ca (true/false/null)
 *  - only_active (true/false/null)
 */
#[Title('Produtos · Admin')]
#[Layout('components.layouts.admin')]
final class Index extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'livewire.public.catalog.pagination';
    }

    public string $search = '';

    public ?int $categoryId = null;

    public ?int $collectionId = null;

    public ?bool $onlyCa = null;

    public ?bool $onlyActive = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatedCollectionId(): void
    {
        $this->resetPage();
    }

    public function updatedOnlyCa(): void
    {
        $this->resetPage();
    }

    public function updatedOnlyActive(): void
    {
        $this->resetPage();
    }

    public function delete(int $productId): void
    {
        $product = Product::withoutCompanyScope()
            ->where('id', $productId)
            ->where('company_id', CompanyContext::id())
            ->firstOrFail();

        $product->delete();
        session()->flash('flash.success', 'Produto removido.');
    }

    public function render(): View
    {
        $products = Product::query()
            ->with(['images', 'category', 'collection'])
            ->when($this->search !== '', fn ($q) => $q->where(function ($qq): void {
                $qq->where('name', 'ILIKE', '%'.$this->search.'%')
                    ->orWhere('code', 'ILIKE', '%'.$this->search.'%')
                    ->orWhere('slug', 'ILIKE', '%'.$this->search.'%');
            }))
            ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
            ->when($this->collectionId, fn ($q) => $q->where('collection_id', $this->collectionId))
            ->when($this->onlyCa === true, fn ($q) => $q->where('has_ca', true))
            ->when($this->onlyCa === false, fn ($q) => $q->where('has_ca', false))
            ->when($this->onlyActive === true, fn ($q) => $q->where('is_active', true))
            ->when($this->onlyActive === false, fn ($q) => $q->where('is_active', false))
            ->orderBy('code')
            ->orderBy('variant_code')
            ->paginate((int) config('catalog.ui.items_per_page', 12));

        $categories = Category::query()->orderBy('name')->get(['id', 'name']);
        $collections = CollectionModel::query()->orderBy('name')->get(['id', 'name']);

        return view('livewire.admin.products.index', [
            'products' => $products,
            'categories' => $categories,
            'collections' => $collections,
        ]);
    }
}
