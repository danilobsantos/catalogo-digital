<?php

declare(strict_types=1);

use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Product;
use App\Domains\Company\Models\Company;
use App\Livewire\Admin\Categories\Form as CategoryForm;
use App\Livewire\Admin\Products\Form as ProductForm;
use Database\Seeders\BootstrapSeeder;
use Livewire\Features\SupportTesting\TestableLivewire;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->seed(BootstrapSeeder::class);
    [, $user] = companyWithAdmin();
    actingAs($user);
});

it('cria produto via ProductForm', function (): void {
    $user = auth()->user();
    $company = Company::find($user->active_company_id);

    /** @var TestableLivewire */
    $livewire = Livewire::test(ProductForm::class)
        ->set('code', '9999')
        ->set('name', 'Botina Teste Premium')
        ->set('short_description', 'Linha top de teste')
        ->set('description', 'Descrição completa de teste')
        ->set('leather', 'Vaqueta')
        ->set('sole', 'Borracha')
        ->set('has_ca', true)
        ->set('is_active', true)
        ->call('save')
        ->assertHasNoErrors();

    $product = Product::withoutCompanyScope()
        ->where('code', '9999')
        ->where('company_id', $company->id)
        ->first();
    expect($product)->not->toBeNull()
        ->and($product->name)->toBe('Botina Teste Premium')
        ->and($product->leather)->toBe('Vaqueta')
        ->and($product->has_ca)->toBeTrue();
});

it('salva tabela de numeração por checkboxes e medidas', function (): void {
    $user = auth()->user();
    $company = Company::find($user->active_company_id);

    Livewire::test(ProductForm::class)
        ->set('code', '8888')
        ->set('name', 'Botina Tamanhos')
        ->set('sizeChecks', ['37', '38', '39'])
        ->set('sizeChartCsv', "37 - 24cm\n39 - 25cm")
        ->call('save')
        ->assertHasNoErrors();

    $product = Product::withoutCompanyScope()
        ->where('code', '8888')
        ->where('company_id', $company->id)
        ->first();
    expect($product->size_chart)->toBe([
        '37' => '24cm',
        '38' => null,
        '39' => '25cm',
    ]);
});

it('valida campos obrigatórios do ProductForm', function (): void {
    Livewire::test(ProductForm::class)
        ->set('code', '')
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['code', 'name']);
});

it('cria categoria via CategoryForm', function (): void {
    Livewire::test(CategoryForm::class)
        ->set('name', 'Coturnos')
        ->set('description', 'Coturnos premium')
        ->call('save')
        ->assertHasNoErrors();

    expect(Category::where('slug', 'coturnos')->exists())->toBeTrue();
});

it('garante slug único ao editar categoria', function (): void {
    Category::factory()->create(['slug' => 'original', 'company_id' => auth()->user()->active_company_id]);
    /** @var Category $cat */
    $cat = Category::factory()->create(['slug' => 'mudar', 'company_id' => auth()->user()->active_company_id]);
    $catId = $cat->id;

    /** @var TestableLivewire */
    $first = Livewire::test(CategoryForm::class);
    /** @var Category $loaded */
    $loaded = $first->instance()->category;
    expect($loaded)->toBeNull();
    unset($first);

    // Forçar modo edição via mount (passamos a categoria pelo bind).
    $component = Livewire::test(CategoryForm::class, ['category' => $cat]);
    $component->set('name', 'Modificada');
    $component->set('slug', 'duplicado');
    $component->call('save')->assertHasNoErrors();

    expect(Category::withoutCompanyScope()->where('slug', 'duplicado')->count())->toBe(1);

    $component = Livewire::test(CategoryForm::class, ['category' => $cat]);
    $component->set('name', 'Modificada 2');
    $component->set('slug', 'original');
    $component->call('save')->assertHasErrors(['slug']);
});
