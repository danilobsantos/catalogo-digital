<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Categories;

use App\Domains\Catalog\Models\Category as CategoryModel;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Lista simples de categorias (tenant ativo).
 */
#[Title('Categorias · Admin')]
#[Layout('components.layouts.admin')]
final class Index extends Component
{
    public function delete(int $id): void
    {
        $cat = CategoryModel::findOrFail($id);
        $cat->delete();
        session()->flash('flash.success', 'Categoria removida.');
    }

    public function render(): View
    {
        $categories = CategoryModel::query()
            ->with('parent')
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.categories.index', compact('categories'));
    }
}
