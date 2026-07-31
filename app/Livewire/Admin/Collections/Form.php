<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Collections;

use App\Domains\Catalog\Models\Collection as CollectionModel;
use App\Traits\CompanyContext;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Coleção · Admin')]
#[Layout('components.layouts.admin')]
final class Form extends Component
{
    public ?CollectionModel $collection = null;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public string $accent_color = '';

    public int $sort_order = 0;

    public bool $is_active = true;

    public bool $is_featured = false;

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $cid = CompanyContext::id();

        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:160', Rule::unique('collections')->ignore($this->collection?->id)->where('company_id', $cid)],
            'description' => ['nullable', 'string'],
            'accent_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'sort_order' => ['integer'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ];
    }

    public function mount(?CollectionModel $collection = null): void
    {
        if ($collection !== null && $collection->exists) {
            abort_unless($collection->company_id === CompanyContext::id(), 404);
            $this->collection = $collection;

            $this->name = $this->collection->name;
            $this->slug = $this->collection->slug;
            $this->description = $this->collection->description ?? '';
            $this->accent_color = $this->collection->accent_color ?? '';
            $this->sort_order = (int) $this->collection->sort_order;
            $this->is_active = (bool) $this->collection->is_active;
            $this->is_featured = (bool) $this->collection->is_featured;
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
            'accent_color' => $v['accent_color'] ?: null,
            'sort_order' => $v['sort_order'],
            'is_active' => $v['is_active'],
            'is_featured' => $v['is_featured'],
        ];
        if ($this->collection?->exists) {
            $this->collection->update($payload);
        } else {
            $this->collection = CollectionModel::create($payload);
        }
        session()->flash('flash.success', 'Coleção salva.');
        $this->redirectRoute('admin.collections.edit', $this->collection, navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.collections.form');
    }
}
