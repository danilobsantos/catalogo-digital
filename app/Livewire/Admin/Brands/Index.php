<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Brands;

use App\Domains\Catalog\Models\Brand as BrandModel;
use App\Traits\CompanyContext;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Marcas · Admin')]
#[Layout('components.layouts.admin')]
final class Index extends Component
{
    public function delete(int $id): void
    {
        $brand = BrandModel::withoutCompanyScope()
            ->where('company_id', CompanyContext::id())
            ->findOrFail($id);
        $brand->delete();
        session()->flash('flash.success', 'Marca removida.');
    }

    public function render(): View
    {
        $brands = BrandModel::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.brands.index', compact('brands'));
    }
}
