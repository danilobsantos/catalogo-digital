<?php

declare(strict_types=1);

use App\Domains\Company\Models\Company;
use App\Models\User;

it('rota admin redireciona anônimos para login', function (): void {
    $response = $this->get('/admin');
    $response->assertRedirect(route('login'));
});

it('rota admin responde 200 para usuário autenticado e vinculado', function (): void {
    [$company, $admin] = companyWithAdmin();

    $response = $this->actingAs($admin)->get('/admin');
    $response->assertOk();
});

it('rota admin retorna 422 se usuário não tem empresa vinculada', function (): void {
    $user = User::factory()->create(['active_company_id' => null]);

    $response = $this->actingAs($user)->get('/admin');
    $response->assertStatus(422);
});

it('middleware ensure.company auto-pickup se há apenas uma company', function (): void {
    $company = Company::factory()->create();
    $user = User::factory()->create(['active_company_id' => null]);
    $user->companies()->attach($company->id, ['is_owner' => true]);
    $user->assignRole('super-admin');

    $this->actingAs($user)->get('/admin')->assertOk();
    expect($user->fresh()->active_company_id)->toBe($company->id);
});
