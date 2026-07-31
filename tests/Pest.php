<?php

declare(strict_types=1);

use App\Domains\Company\Models\Company;
use App\Models\User;
use Database\Seeders\BootstrapSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function (): void {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->seed(BootstrapSeeder::class);
    })
    ->in('Feature', 'Unit');

/**
 * Cria uma empresa + super-admin dono e retorna [company, user].
 */
function companyWithAdmin(): array
{
    $company = Company::factory()->create();
    $admin = User::factory()->create([
        'active_company_id' => $company->id,
    ]);
    $admin->companies()->attach($company->id, ['is_owner' => true]);
    $admin->assignRole('super-admin');

    return [$company, $admin];
}
