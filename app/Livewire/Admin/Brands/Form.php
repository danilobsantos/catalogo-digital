<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Brands;

use App\Domains\Catalog\Models\Brand as BrandModel;
use App\Traits\CompanyContext;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Marca · Admin')]
#[Layout('components.layouts.admin')]
final class Form extends Component
{
    public ?BrandModel $brand = null;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public string $website_url = '';

    public int $sort_order = 0;

    public bool $is_active = true;

    public bool $is_featured = false;

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $cid = CompanyContext::id();

        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:160', Rule::unique('brands')->ignore($this->brand?->id)->where('company_id', $cid)],
            'description' => ['nullable', 'string'],
            'website_url' => ['nullable', 'url'],
            'sort_order' => ['integer'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ];
    }

    public function mount(?BrandModel $brand = null): void
    {
        if ($brand !== null && $brand->exists) {
            abort_unless($brand->company_id === CompanyContext::id(), 404);
            $this->brand = $brand;
            $this->fillBrand();
        }
    }

    private function fillBrand(): void
    {
        $b = $this->brand;
        $this->name = $b->name;
        $this->slug = $b->slug;
        $this->description = $b->description ?? '';
        $this->website_url = $b->website_url ?? '';
        $this->sort_order = (int) $b->sort_order;
        $this->is_active = (bool) $b->is_active;
        $this->is_featured = (bool) $b->is_featured;
    }

    public function madeSlug(): void
    {
        $this->slug = Str::slug($this->name);
    }

    public function save(): void
    {
        $v = $this->validate();
        $payload = [
            'company_id' => CompanyContext::id(),
            'name' => $v['name'],
            'slug' => $v['slug'] ?: Str::slug($this->name),
            'description' => $v['description'] ?: null,
            'website_url' => $v['website_url'] ?: null,
            'sort_order' => $v['sort_order'],
            'is_active' => $v['is_active'],
            'is_featured' => $v['is_featured'],
        ];
        if ($this->brand?->exists) {
            $this->brand->update($payload);
        } else {
            $this->brand = BrandModel::create($payload);
        }
        session()->flash('flash.success', 'Marca salva.');
        $this->redirectRoute('admin.brands.edit', $this->brand, navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.brands.form');
    }
}
