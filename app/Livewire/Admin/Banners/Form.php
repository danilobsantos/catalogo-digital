<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Banners;

use App\Domains\Catalog\Models\Banner;
use App\Domains\SEO\Support\ImageIngestor;
use App\Traits\CompanyContext;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Banner · Admin')]
#[Layout('components.layouts.admin')]
final class Form extends Component
{
    use WithFileUploads;

    public ?Banner $banner = null;

    public string $slug = '';

    public string $title = '';

    public string $subtitle = '';

    public string $description = '';

    public string $image_alt = '';

    public string $cta_label = '';

    public string $cta_url = '';

    public string $cta_route_name = '';

    public string $position = 'hero';

    public int $sort_order = 0;

    public bool $is_active = true;

    public ?string $starts_at = null;

    public ?string $ends_at = null;

    /** @var mixed */
    public $newImage = null;

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $cid = CompanyContext::id();

        return [
            'slug' => ['nullable', 'string', 'max:64', Rule::unique('banners')->ignore($this->banner?->id)->where('company_id', $cid)],
            'title' => ['required', 'string', 'max:160'],
            'subtitle' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'image_alt' => ['nullable', 'string', 'max:160'],
            'cta_label' => ['nullable', 'string', 'max:64'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'cta_route_name' => ['nullable', 'string', 'max:160'],
            'position' => ['required', 'string', 'max:32'],
            'sort_order' => ['integer'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'newImage' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
        ];
    }

    public function mount(?Banner $banner = null): void
    {
        if ($banner !== null && $banner->exists) {
            abort_unless($banner->company_id === CompanyContext::id(), 404);
            $this->banner = $banner;
            $this->fillBanner();
        }
    }

    private function fillBanner(): void
    {
        $b = $this->banner;
        $this->slug = $b->slug;
        $this->title = $b->title;
        $this->subtitle = $b->subtitle ?? '';
        $this->description = $b->description ?? '';
        $this->image_alt = $b->image_alt ?? '';
        $this->cta_label = $b->cta_label ?? '';
        $this->cta_url = $b->cta_url ?? '';
        $this->cta_route_name = $b->cta_route_name ?? '';
        $this->position = $b->position ?? 'hero';
        $this->sort_order = (int) $b->sort_order;
        $this->is_active = (bool) $b->is_active;
        $this->starts_at = $b->starts_at?->format('Y-m-d\TH:i');
        $this->ends_at = $b->ends_at?->format('Y-m-d\TH:i');
    }

    public function madeSlug(): void
    {
        $this->slug = Str::slug($this->title);
    }

    public function save(): void
    {
        $v = $this->validate();
        $payload = [
            'company_id' => CompanyContext::id(),
            'slug' => $v['slug'] ?: Str::slug($this->title),
            'title' => $v['title'],
            'subtitle' => $v['subtitle'] ?: null,
            'description' => $v['description'] ?: null,
            'image_alt' => $v['image_alt'] ?: null,
            'cta_label' => $v['cta_label'] ?: null,
            'cta_url' => $v['cta_url'] ?: null,
            'cta_route_name' => $v['cta_route_name'] ?: null,
            'position' => $v['position'],
            'sort_order' => $v['sort_order'],
            'is_active' => $v['is_active'],
            'starts_at' => $v['starts_at'] ?: null,
            'ends_at' => $v['ends_at'] ?: null,
        ];

        if ($this->newImage !== null) {
            $ingestor = app(ImageIngestor::class);
            $variants = $ingestor->ingestUploaded(
                $this->newImage,
                'banners',
                'banner_'.Str::slug($this->title ?: 'banner')
            );
            if (! empty($variants['original'])) {
                $payload['image_path'] = $variants['original'];
            }
        }

        if ($this->banner?->exists) {
            $this->banner->update($payload);
        } else {
            $this->banner = Banner::create($payload);
        }

        $this->newImage = null;

        session()->flash('flash.success', 'Banner salvo.');
        $this->redirectRoute('admin.banners.edit', $this->banner, navigate: true);
    }

    public function removeImage(): void
    {
        if ($this->banner?->exists && $this->banner->image_path !== null) {
            $this->banner->update(['image_path' => null]);
            $this->banner->refresh();
        }
        $this->newImage = null;
    }

    public function render(): View
    {
        return view('livewire.admin.banners.form');
    }
}
