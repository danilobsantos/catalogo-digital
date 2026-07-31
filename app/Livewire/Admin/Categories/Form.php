<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Categories;

use App\Domains\Catalog\Models\Category as CategoryModel;
use App\Traits\CompanyContext;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Categoria · Admin')]
#[Layout('components.layouts.admin')]
final class Form extends Component
{
    public ?CategoryModel $category = null;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public ?int $parent_id = null;

    public int $sort_order = 0;

    public bool $is_active = true;

    public bool $is_featured = false;

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $cid = CompanyContext::id();

        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:160', Rule::unique('categories')->ignore($this->category?->id)->where('company_id', $cid)],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', Rule::exists('categories', 'id')->where('company_id', $cid)],
            'sort_order' => ['integer'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ];
    }

    public function mount(?CategoryModel $category = null): void
    {
        if ($category !== null && $category->exists) {
            abort_unless($category->company_id === CompanyContext::id(), 404);

            $this->category = $category;

            $this->name = $this->category->name;
            $this->slug = $this->category->slug;
            $this->description = $this->category->description ?? '';
            $this->parent_id = $this->category->parent_id;
            $this->sort_order = $this->category->sort_order;
            $this->is_active = $this->category->is_active;
            $this->is_featured = $this->category->is_featured;
        }
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
            'parent_id' => $v['parent_id'],
            'sort_order' => $v['sort_order'],
            'is_active' => $v['is_active'],
            'is_featured' => $v['is_featured'],
        ];

        if ($this->category?->exists) {
            $this->category->update($payload);
        } else {
            $this->category = CategoryModel::create($payload);
        }

        session()->flash('flash.success', 'Categoria salva.');
        $this->redirectRoute('admin.categories.edit', $this->category, navigate: true);
    }

    public function render(): View
    {
        $parents = CategoryModel::query()
            ->whereNull('parent_id')
            ->when($this->category?->exists, fn ($q) => $q->where('id', '!=', $this->category->id))
            ->orderBy('name')
            ->get();

        return view('livewire.admin.categories.form', ['parents' => $parents]);
    }
}
