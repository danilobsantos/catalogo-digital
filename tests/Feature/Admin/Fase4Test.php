<?php

declare(strict_types=1);

use App\Domains\Catalog\Models\Product;
use App\Domains\Company\Models\Company;
use App\Models\User;
use Database\Seeders\BootstrapSeeder;

use function Pest\Laravel\actingAs;

use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    $this->seed(BootstrapSeeder::class);
});

/** Revoga `super-admin` de um usuário admin e retorna o usuário. */
function makeUser(string $role, ?int $companyId = null): User
{
    [, $user] = companyWithAdmin();
    $user->removeRole('super-admin');
    Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
    $user->assignRole($role);
    if ($companyId !== null) {
        $user->update(['active_company_id' => $companyId]);
    }

    return $user;
}

it('rota /admin/banners exige permissão (403 para editor)', function (): void {
    $editor = makeUser('editor');

    $response = actingAs($editor)->get('/admin/banners');
    $response->assertForbidden();
});

it('editor pode acessar /admin/produtos (200)', function (): void {
    $editor = makeUser('editor');

    actingAs($editor)->get('/admin/produtos')->assertOk();
});

it('company-admin pode acessar /admin/banners (200)', function (): void {
    $manager = makeUser('company-admin');

    actingAs($manager)->get('/admin/banners')->assertOk();
});

it('criar produto gera atividade no log', function (): void {
    $company = Company::first();
    [, $admin] = companyWithAdmin();

    Product::create([
        'company_id' => $company->id,
        'code' => '9100',
        'slug' => 'botina-audit-9100',
        'name' => 'Botina Auditoria',
        'is_active' => true,
        'published_at' => now(),
    ]);

    $count = Activity::query()
        ->where('log_name', 'catalog:product')
        ->where('subject_type', Product::class)
        ->count();
    expect($count)->toBeGreaterThanOrEqual(1);
});

it('rotas públicas filtradas via querystring aceitam prettier params', function (): void {
    $company = Company::first();
    Product::create([
        'company_id' => $company->id,
        'code' => '9101',
        'slug' => 'botina-publicada-9101',
        'name' => 'Botina Publicada',
        'has_ca' => true,
        'is_active' => true,
        'leather' => 'Couro',
        'published_at' => now(),
    ]);

    $response = $this->get('/produtos?q=Botina&ca=1');
    $response->assertOk();
    $response->assertSee('Botina Publicada');
});
