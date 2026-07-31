<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Actions;

use App\Domains\Catalog\Models\Brand;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Collection as CollectionModel;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Support\DocxProductDto;
use App\Domains\Company\Models\Company;
use Illuminate\Support\Str;

/**
 * Cria/atualiza um Product a partir de um DocxProductDto.
 *
 * Regras:
 *  - Slug único por (company_id) — gerado a partir de name + code se não houver
 *  - Auto-categorização heurística pelo título (Coturno/Texana/Segurança/Passeio/…)
 *  - Auto-collection: feminina quando contém `FEM` no slug do arquivo.
 *  - Brand padrão: cj-calcados (única cadastrada).
 *  - Cuidado: textos com encoding Windows-1252 (ç/ã em chaves weird) são normalizados.
 */
final class IngestDocxProductAction
{
    public function execute(Company $company, DocxProductDto $dto): Product
    {
        return Product::withoutEvents(function () use ($company, $dto): Product {
            $categoryId = $this->resolveCategory($company, $dto);
            $collectionId = $this->resolveCollection($company, $dto);
            $brandId = $this->resolveBrandId($company);

            $baseSlug = Str::slug(Str::limit($dto->title, 80, ''))
                .'-'.Str::slug($dto->rawCode)
                .($dto->variantCode ? '-'.Str::slug($dto->variantCode) : '');

            $payload = [
                'company_id' => $company->id,
                'category_id' => $categoryId,
                'collection_id' => $collectionId,
                'brand_id' => $brandId,
                'code' => $dto->rawCode,
                'variant_code' => $dto->variantCode,
                'slug' => $baseSlug,
                'name' => $this->clean($dto->title),
                'subtitle' => $this->clean($dto->subtitle) ?: null,
                'short_description' => $this->clean($dto->shortDescription ?? $dto->description) ?: null,
                'description' => $this->clean($dto->description ?? $dto->shortDescription) ?: null,
                'materials' => $dto->materials ?: null,
                'care_instructions' => $dto->care ?: null,
                'size_chart' => ! empty($dto->sizeChart) ? $dto->sizeChart : null,
                'specs' => $dto->manufacturing ? ['processo_fabricacao' => $dto->manufacturing] : null,
                'features' => $dto->features ?: null,
                'colors' => $dto->colors ?: null,
                'sole' => $dto->sole,
                'leather' => $dto->leather,
                'closure' => $dto->closure,
                'toe_cap' => $dto->toeCap,
                'approvals' => $dto->approval,
                'weight_grams' => $dto->weight,
                'has_ca' => $dto->hasCa,
                'is_featured' => $dto->hasCa,
                'is_new' => str_starts_with($dto->rawCode, '4') || str_starts_with($dto->rawCode, '5'),
                'sort_order' => (int) $dto->rawCode,
                'is_active' => true,
                'published_at' => now(),
            ];

            return Product::withoutCompanyScope()->updateOrCreate(
                [
                    'company_id' => $company->id,
                    'code' => $dto->rawCode,
                    'variant_code' => $dto->variantCode,
                ],
                $payload,
            );
        });
    }

    private function resolveCategory(Company $company, DocxProductDto $dto): ?int
    {
        $haystack = mb_strtolower($dto->title.' '.$dto->subtitle.' '.($dto->shortDescription ?? ''));

        $priority = [
            'infantil' => 'infantil',
            'texana' => 'texana',
            'segurança ca' => 'botina-seguranca',
            'coturno' => 'coturno',
            'passeio' => 'botina-passeio',
            'tradicional' => 'botina-tradicional',
        ];

        foreach ($priority as $needle => $slug) {
            if (str_contains($haystack, $needle)) {
                return $this->category($company, $slug);
            }
        }

        // Default.
        return $this->category($company, 'botina-passeio');
    }

    private function category(Company $company, string $slug): ?int
    {
        $category = Category::withoutCompanyScope()
            ->where('company_id', $company->id)
            ->where('slug', $slug)
            ->first(['id']);

        return $category?->id;
    }

    private function resolveCollection(Company $company, DocxProductDto $dto): ?int
    {
        $title = mb_strtoupper($dto->title.' '.$dto->subtitle);

        if (str_contains($title, 'INFANTIL') || str_starts_with((string) $dto->rawCode, '6') || str_starts_with((string) $dto->rawCode, '5')) {
            return $this->collection($company, 'infantil');
        }
        if (str_contains($title, 'FEM') || str_contains($title, 'FEMININA')) {
            return $this->collection($company, 'feminina');
        }
        if ($dto->hasCa) {
            return $this->collection($company, 'profissional');
        }

        return $this->collection($company, 'classica');
    }

    private function collection(Company $company, string $slug): ?int
    {
        $collection = CollectionModel::withoutCompanyScope()
            ->where('company_id', $company->id)
            ->where('slug', $slug)
            ->first(['id']);

        return $collection?->id;
    }

    private function resolveBrandId(Company $company): ?int
    {
        $brand = Brand::withoutCompanyScope()
            ->where('company_id', $company->id)
            ->where('slug', 'cj-calcados')
            ->first(['id']);

        return $brand?->id;
    }

    private function clean(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;
        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
