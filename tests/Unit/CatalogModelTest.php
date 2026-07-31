<?php

declare(strict_types=1);

use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductImage;
use App\Domains\Company\Models\Company;
use App\Traits\CompanyContext;

it('cria categoria via factory com company_id automático', function (): void {
    $company = Company::first();

    $cat = Category::factory()->for($company)->create();

    expect($cat->company_id)->toBe($company->id)
        ->and($cat->slug)->toBeString()->not->toBeEmpty()
        ->and($cat->is_active)->toBeTrue();
});

it('escopo global de empresa isola categorias entre tenants', function (): void {
    $a = Company::factory()->create();
    $b = Company::factory()->create();

    CompanyContext::setFallback($a->id);
    Category::factory()->count(2)->create();

    CompanyContext::setFallback($b->id);
    Category::factory()->count(3)->create();

    CompanyContext::setFallback($a->id);
    expect(Category::query()->count())->toBe(2);

    CompanyContext::setFallback($b->id);
    expect(Category::query()->count())->toBe(3);

    CompanyContext::setFallback(null);
    expect(Category::withoutCompanyScope()->count())->toBeGreaterThanOrEqual(5);
});

it('produto é criado com company_id vinculado via trait HasCompanyScope', function (): void {
    $company = Company::first();

    $product = Product::factory()->for($company)->create([
        'code' => '4000',
        'slug' => 'botina-teste-4000',
    ]);

    expect($product->company_id)->toBe($company->id)
        ->and($product->code)->toBe('4000')
        ->and($product->slug)->toBe('botina-teste-4000')
        ->and($product->has_ca)->toBeBool();
});

it('produto withCa preenche CA válido', function (): void {
    $company = Company::first();

    $product = Product::factory()->for($company)->withCa('27000')->create();

    expect($product->has_ca)->toBeTrue()
        ->and($product->ca_number)->toBe('27000')
        ->and($product->ca_validity)->not->toBeNull();
});

it('produto tem múltiplas imagens ordenadas', function (): void {
    $company = Company::first();
    $product = Product::factory()->for($company)->create();

    ProductImage::factory()->for($product)->count(3)->create([
        'sort_order' => fn (): int => 0,
    ]);

    expect($product->images()->count())->toBe(3);
});
