<?php

declare(strict_types=1);

use App\Domains\Catalog\Models\Product;
use App\Domains\Company\Models\Company;
use App\Livewire\Admin\Products\Index as AdminProductsIndex;
use App\Livewire\Public\Catalog\Search as PublicCatalogSearch;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('pesquisa produto por codigo no admin', function (): void {
    [, $user] = companyWithAdmin();
    actingAs($user);
    $company = Company::find($user->active_company_id);

    Product::create([
        'company_id' => $company->id,
        'code' => '4027',
        'slug' => 'botina-4027',
        'name' => 'Botina 4027 Especial',
        'is_active' => true,
        'published_at' => now(),
    ]);

    Livewire::test(AdminProductsIndex::class)
        ->set('search', '4027')
        ->assertSee('Botina 4027 Especial')
        ->assertSee('4027');
});

it('pesquisa produto por codigo no catalogo publico', function (): void {
    [, $user] = companyWithAdmin();
    actingAs($user);
    $company = Company::find($user->active_company_id);

    Product::create([
        'company_id' => $company->id,
        'code' => '4027',
        'slug' => 'botina-4027',
        'name' => 'Botina 4027 Especial',
        'is_active' => true,
        'published_at' => now(),
    ]);

    Livewire::test(PublicCatalogSearch::class)
        ->set('q', '4027')
        ->assertSee('Botina 4027 Especial');
});

it('permite pré-visualização de produto inativo para usuario autenticado', function (): void {
    [, $user] = companyWithAdmin();
    $company = Company::find($user->active_company_id);

    $product = Product::create([
        'company_id' => $company->id,
        'code' => '5001',
        'slug' => 'botina-inativa-5001',
        'name' => 'Botina Inativa Teste',
        'is_active' => false,
        'published_at' => now(),
    ]);

    // Não autenticado -> 404
    get("/produtos/{$product->slug}")->assertStatus(404);

    // Autenticado -> 200
    actingAs($user)->get("/produtos/{$product->slug}")->assertStatus(200);
});

it('alterna status ativo/inativo via toggleActive no admin', function (): void {
    [, $user] = companyWithAdmin();
    actingAs($user);
    $company = Company::find($user->active_company_id);

    $product = Product::create([
        'company_id' => $company->id,
        'code' => '5002',
        'slug' => 'botina-toggle-5002',
        'name' => 'Botina Toggle Teste',
        'is_active' => false,
        'published_at' => now(),
    ]);

    Livewire::test(AdminProductsIndex::class)
        ->call('toggleActive', $product->id);

    expect($product->fresh()->is_active)->toBeTrue();

    Livewire::test(AdminProductsIndex::class)
        ->call('toggleActive', $product->id);

    expect($product->fresh()->is_active)->toBeFalse();
});
