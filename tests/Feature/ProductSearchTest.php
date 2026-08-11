<?php

declare(strict_types=1);

use App\Domains\Catalog\Models\Product;
use App\Domains\Company\Models\Company;
use App\Livewire\Admin\Products\Index as AdminProductsIndex;
use App\Livewire\Public\Catalog\Search as PublicCatalogSearch;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

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
