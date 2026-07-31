<?php

declare(strict_types=1);

use App\Domains\Company\Models\Company;

it('cria empresa com factory válida', function (): void {
    $company = Company::factory()->create();

    expect($company)
        ->toBeInstanceOf(Company::class)
        ->and($company->exists)->toBeTrue()
        ->and($company->slug)->toBeString()
        ->and($company->is_active)->toBeTrue();
});

it('suporta estado inactive', function (): void {
    $company = Company::factory()->inactive()->create();

    expect($company->is_active)->toBeFalse();
});

it('vincula usuário via companies_users como owner', function (): void {
    [$company, $admin] = companyWithAdmin();

    $pivot = $company->users()->whereKey($admin->id)->first()?->pivot;

    expect($pivot)->not->toBeNull()
        ->and((bool) $pivot->is_owner)->toBeTrue();
});

it('super-admin herda papel via spatie/permission', function (): void {
    [$company, $admin] = companyWithAdmin();

    expect($admin->hasRole('super-admin'))->toBeTrue();
});

it('campos JSON são convertidos para array', function (): void {
    $company = Company::factory()->create([
        'social' => ['instagram' => '@cjcalcados'],
        'address' => ['city' => 'São Paulo'],
    ]);

    expect($company->social)->toBe(['instagram' => '@cjcalcados'])
        ->and($company->address)->toBe(['city' => 'São Paulo']);
});
