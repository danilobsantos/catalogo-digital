<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Products;

use App\Domains\Catalog\Models\Brand as BrandModel;
use App\Domains\Catalog\Models\Category as CategoryModel;
use App\Domains\Catalog\Models\Collection as CollectionModel;
use App\Domains\Catalog\Models\Product;
use App\Domains\SEO\Support\ImageIngestor;
use App\Traits\CompanyContext;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Formulário de criação/edição de produtos.
 *
 *  - Suporta `?product={id}` para abrir em modo de edição; null = criar.
 *  - Upload múltiplo: cada nova imagem é movida para `storage/public/products/{id}/...`.
 *  - Marca `is_cover=true` automaticamente para a primeira imagem existente.
 *  - Auto-slug a partir de `name` + code se slug ficar vazio.
 */
#[Title('Produto · Admin')]
#[Layout('components.layouts.admin')]
final class Form extends Component
{
    use WithFileUploads;

    public ?Product $product = null;

    /** Campos editáveis principais (espelham a migration) */
    public string $code = '';

    public string $variant_code = '';

    public string $name = '';

    public string $slug = '';

    public string $subtitle = '';

    public string $short_description = '';

    public string $description = '';

    public ?int $category_id = null;

    public ?int $collection_id = null;

    public ?int $brand_id = null;

    public string $sole = '';

    public string $leather = '';

    public string $closure = '';

    public string $toe_cap = '';

    public string $approvals = '';

    public string $weight_grams = '';

    public bool $has_ca = false;

    public bool $is_featured = false;

    public bool $is_new = false;

    public bool $is_bestseller = false;

    public bool $is_active = true;

    /** Arrays */
    public string $materialsCsv = '';

    public string $careCsv = '';

    public string $colorsCsv = '';

    /** Field textuais extras */
    public string $manufacturing = '';

    public string $ca_number = '';

    /** Uploads */
    /** @var array<int, UploadedFile> */
    public array $newImages = [];

    /** @return array<string, array<int, mixed>>|array<string, string> */
    public function rules(): array
    {
        $companyId = CompanyContext::id();

        return [
            'code' => ['required', 'string', 'max:32'],
            'variant_code' => ['nullable', 'string', 'max:32'],
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('products')->ignore($this->product?->id)
                ->where('company_id', $companyId)],
            'subtitle' => ['nullable', 'string', 'max:160'],
            'short_description' => ['nullable', 'string', 'max:300'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')->where('company_id', $companyId)],
            'collection_id' => ['nullable', 'integer', Rule::exists('collections', 'id')->where('company_id', $companyId)],
            'brand_id' => ['nullable', 'integer', Rule::exists('brands', 'id')->where('company_id', $companyId)],
            'sole' => ['nullable', 'string', 'max:80'],
            'leather' => ['nullable', 'string', 'max:80'],
            'closure' => ['nullable', 'string', 'max:80'],
            'toe_cap' => ['nullable', 'string', 'max:80'],
            'approvals' => ['nullable', 'string', 'max:160'],
            'weight_grams' => ['nullable', 'string', 'max:16'],
            'has_ca' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_new' => ['boolean'],
            'is_bestseller' => ['boolean'],
            'is_active' => ['boolean'],
            'newImages' => ['array', 'max:8'],
            'newImages.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function mount(?Product $product = null): void
    {
        if ($product !== null && $product->exists) {
            abort_unless($product->company_id === CompanyContext::id(), 404);
            $product->loadMissing('images');

            $this->product = $product;
            $this->fillFromProduct();
        }
    }

    private function fillFromProduct(): void
    {
        if (! $this->product) {
            return;
        }

        $p = $this->product;

        $this->code = (string) ($p->code ?? '');
        $this->variant_code = (string) ($p->variant_code ?? '');
        $this->name = (string) ($p->name ?? '');
        $this->slug = (string) ($p->slug ?? '');
        $this->subtitle = (string) ($p->subtitle ?? '');
        $this->short_description = (string) ($p->short_description ?? '');
        $this->description = (string) ($p->description ?? '');
        $this->category_id = $p->category_id;
        $this->collection_id = $p->collection_id;
        $this->brand_id = $p->brand_id;

        $this->sole = (string) ($p->sole ?? '');
        $this->leather = (string) ($p->leather ?? '');
        $this->closure = (string) ($p->closure ?? '');
        $this->toe_cap = (string) ($p->toe_cap ?? '');
        $this->approvals = (string) ($p->approvals ?? '');
        $this->weight_grams = (string) ($p->weight_grams ?? '');

        $this->has_ca = (bool) $p->has_ca;
        $this->is_featured = (bool) $p->is_featured;
        $this->is_new = (bool) $p->is_new;
        $this->is_bestseller = (bool) $p->is_bestseller;
        $this->is_active = (bool) $p->is_active;

        $this->materialsCsv = is_array($p->materials) ? implode(', ', $p->materials) : '';
        $this->careCsv = is_array($p->care_instructions) ? implode("\n", $p->care_instructions) : '';
        $this->colorsCsv = is_array($p->colors) ? implode(', ', $p->colors) : '';

        $specs = is_array($p->specs) ? $p->specs : [];
        $this->manufacturing = (string) ($specs['processo_fabricacao'] ?? '');

        $this->ca_number = (string) ($p->ca_number ?? '');
    }

    public function updatedNewImages(): void
    {
        $this->validate([
            'newImages' => ['array', 'max:8'],
            'newImages.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }

    public function madeSlug(): void
    {
        $base = Str::slug($this->name);
        $this->slug = $base.'-'.Str::slug($this->code).($this->variant_code ? '-'.Str::slug($this->variant_code) : '');
    }

    public function save(): void
    {
        $validated = $this->validate();

        $payload = [
            'company_id' => CompanyContext::id(),
            'code' => $validated['code'],
            'variant_code' => $validated['variant_code'] ?: null,
            'slug' => $validated['slug'] ?: $this->autoSlug(),
            'name' => $validated['name'],
            'subtitle' => $validated['subtitle'] ?: null,
            'short_description' => $validated['short_description'] ?: null,
            'description' => $validated['description'] ?: null,
            'category_id' => $validated['category_id'],
            'collection_id' => $validated['collection_id'],
            'brand_id' => $validated['brand_id'],
            'sole' => $validated['sole'] ?: null,
            'leather' => $validated['leather'] ?: null,
            'closure' => $validated['closure'] ?: null,
            'toe_cap' => $validated['toe_cap'] ?: null,
            'approvals' => $validated['approvals'] ?: null,
            'weight_grams' => $validated['weight_grams'] ?: null,
            'has_ca' => $validated['has_ca'],
            'ca_number' => $this->ca_number ?: null,
            'specs' => $this->manufacturing !== '' ? ['processo_fabricacao' => $this->manufacturing] : null,
            'is_featured' => $validated['is_featured'],
            'is_new' => $validated['is_new'],
            'is_bestseller' => $validated['is_bestseller'],
            'is_active' => $validated['is_active'],
            'materials' => $this->splitCsv($this->materialsCsv),
            'care_instructions' => $this->splitLines($this->careCsv, 8),
            'colors' => $this->splitCsv($this->colorsCsv),
            'published_at' => now(),
        ];

        if ($this->product?->exists) {
            $this->product->update($payload);
        } else {
            $this->product = Product::create($payload);
        }

        $this->ingestUploads();

        session()->flash('flash.success', 'Produto salvo com sucesso.');

        $this->redirectRoute('admin.products.edit', $this->product, navigate: true);
    }

    private function autoSlug(): string
    {
        $base = Str::slug($this->name);

        return $base.'-'.Str::slug($this->code).($this->variant_code ? '-'.Str::slug($this->variant_code) : '');
    }

    /** @return array<int, string>|null */
    private function splitCsv(string $input): ?array
    {
        $values = array_filter(array_map('trim', explode(',', $input)), fn (string $v): bool => $v !== '');
        if ($values === []) {
            return null;
        }

        return array_values($values);
    }

    /** @return array<int, string>|null */
    private function splitLines(string $input, int $maxLines): ?array
    {
        $values = array_filter(
            array_map('trim', preg_split('/\r?\n/u', $input) ?: []),
            fn (string $v): bool => $v !== '',
        );
        if ($values === []) {
            return null;
        }

        return array_slice(array_values($values), 0, $maxLines);
    }

    private function ingestUploads(): void
    {
        if (! $this->product?->exists || empty($this->newImages)) {
            return;
        }

        $ingestor = app(ImageIngestor::class);
        $hasCover = $this->product->images()->where('is_cover', true)->exists();

        foreach ($this->newImages as $i => $upload) {
            $baseName = 'p'.$this->product->id.'_'.($i + 1).'_'.now()->timestamp;
            $variants = $ingestor->ingestUploaded(
                $upload,
                "products/{$this->product->id}",
                $baseName,
                'public',
            );

            $original = $variants['original'];
            if ($original === null) {
                continue;
            }

            $this->product->images()->create([
                'company_id' => $this->product->company_id,
                'path' => $original,
                'thumb_path' => $variants['thumb'],
                'cover_path' => $variants['cover'],
                'disk' => 'public',
                'alt_text' => $this->product->name,
                'is_cover' => ! $hasCover,
                'sort_order' => $this->product->images()->count(),
            ]);

            $hasCover = true;
        }

        $this->newImages = [];
    }

    public function deleteImage(int $id): void
    {
        if ($this->product === null) {
            return;
        }
        $img = $this->product->images()->where('id', $id)->first();
        if ($img !== null) {
            $img->delete();
        }
    }

    public function setAsCover(int $id): void
    {
        if ($this->product === null) {
            return;
        }
        $this->product->images()->update(['is_cover' => false]);
        $this->product->images()->where('id', $id)->update(['is_cover' => true]);
    }

    public function render(): View
    {
        return view('livewire.admin.products.form', [
            'categories' => CategoryModel::query()->orderBy('name')->get(['id', 'name']),
            'collections' => CollectionModel::query()->orderBy('name')->get(['id', 'name']),
            'brands' => BrandModel::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
