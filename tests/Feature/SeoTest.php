<?php

declare(strict_types=1);

use App\Domains\Catalog\Models\Product;
use App\Domains\Company\Models\Company;
use App\Models\User;

it('GET /sitemap.xml retorna xml válido com URLs', function (): void {
    $r = $this->get('/sitemap.xml');
    $r->assertOk();
    $r->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

    $body = $r->getContent();
    expect($body)->toContain('<?xml')->toContain('<urlset');
    expect(substr_count($body, '<loc>'))->toBeGreaterThan(5);
});

it('GET /robots.txt retorna robots correto', function (): void {
    $r = $this->get('/robots.txt');
    $r->assertOk();
    expect($r->getContent())->toContain('User-agent:');
});

it('GET /produtos/{slug} inclui JSON-LD Product', function (): void {
    $product = Product::query()->where('is_active', true)->firstOrFail();
    $r = $this->get('/produtos/'.$product->slug);
    $r->assertOk();
    expect($r->getContent())->toContain('application/ld+json')
        ->and($r->getContent())->toContain('"Product"');
});

it('GET rota inexistente retorna página 404 custom', function (): void {
    $r = $this->get('/inexistente-para-teste');
    $r->assertNotFound();
    expect($r->getContent())->toContain('Página não encontrada')
        ->and($r->getContent())->toContain('404');
});

it('inclui OG, Twitter, canonical no <head>', function (): void {
    $r = $this->get('/produtos');
    $r->assertOk();
    $content = $r->getContent();
    expect($content)->toContain('property="og:title"')
        ->and($content)->toContain('property="og:description"')
        ->and($content)->toContain('name="twitter:card"')
        ->and($content)->toContain('rel="canonical"');
});

it('rota 403 renderiza página custom (sem role)', function (): void {
    $company = Company::first();
    $user = User::factory()->create(['active_company_id' => $company->id]);
    $user->save();

    $r = $this->actingAs($user)->get('/admin');
    $r->assertStatus(403);
    expect($r->getContent())->toContain('Acesso negado');
});
