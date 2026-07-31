<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Banners;

use App\Domains\Catalog\Models\Banner;
use App\Traits\CompanyContext;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Banners · Admin')]
#[Layout('components.layouts.admin')]
final class Index extends Component
{
    public function delete(int $id): void
    {
        Banner::withoutCompanyScope()
            ->where('company_id', CompanyContext::id())
            ->findOrFail($id)->delete();
        session()->flash('flash.success', 'Banner removido.');
    }

    public function toggle(int $id): void
    {
        $b = Banner::withoutCompanyScope()
            ->where('company_id', CompanyContext::id())
            ->findOrFail($id);
        $b->update(['is_active' => ! $b->is_active]);
    }

    public function render(): View
    {
        $banners = Banner::query()
            ->orderBy('position')
            ->orderBy('sort_order')
            ->get();

        return view('livewire.admin.banners.index', compact('banners'));
    }
}
