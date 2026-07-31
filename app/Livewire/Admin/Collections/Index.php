<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Collections;

use App\Domains\Catalog\Models\Collection as CollectionModel;
use App\Traits\CompanyContext;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Coleções · Admin')]
#[Layout('components.layouts.admin')]
final class Index extends Component
{
    public function delete(int $id): void
    {
        CollectionModel::withoutCompanyScope()
            ->where('company_id', CompanyContext::id())
            ->findOrFail($id)->delete();
        session()->flash('flash.success', 'Coleção removida.');
    }

    public function render(): View
    {
        $collections = CollectionModel::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.collections.index', compact('collections'));
    }
}
