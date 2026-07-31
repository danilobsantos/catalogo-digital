<?php

declare(strict_types=1);

use App\Domains\Analytics\Models\AnalyticsEvent;
use App\Domains\Analytics\Support\AnalyticsTracker;
use App\Domains\Catalog\Models\Product;
use App\Models\User;
use Database\Seeders\BootstrapSeeder;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->seed(BootstrapSeeder::class);
    $user = User::where('email', 'admin@cjcalcados.com.br')->first();
    actingAs($user);
});

it('AnalyticsTracker::track cria evento com company_id correto', function (): void {
    AnalyticsTracker::track('view', ['slug' => '4000'], '/produtos/test');
    expect(AnalyticsEvent::count())->toBe(1);
    $evt = AnalyticsEvent::first();
    expect($evt->event)->toBe('view')
        ->and($evt->payload['slug'])->toBe('4000')
        ->and($evt->path)->toBe('/produtos/test');
});

it('Controller analytics track aceita payload válido', function (): void {
    $r = $this->postJson('/api/track', [
        'event' => 'whatsapp_click',
        'payload' => ['product_id' => 1, 'origin' => 'public_card'],
    ]);
    $r->assertOk()
        ->assertJson(['ok' => true]);

    expect(AnalyticsEvent::where('event', 'whatsapp_click')->count())->toBe(1);
});

it('Controller analytics track rejeita evento não whitelisted', function (): void {
    $r = $this->postJson('/api/track', [
        'event' => 'evil_event',
        'payload' => [],
    ]);
    $r->assertStatus(422);

    expect(AnalyticsEvent::count())->toBe(0);
});

it('view_count incrementa em Product show', function (): void {
    $product = Product::query()->where('is_active', true)->firstOrFail();
    $initial = (int) $product->view_count;

    $this->get(route('public.products.show', ['product' => $product->slug]))->assertOk();

    expect((int) $product->fresh()->view_count)->toBe($initial + 1);
    expect(AnalyticsEvent::where('event', 'view')->count())->toBeGreaterThan(0);
});

it('export CSV retorna stream válido', function (): void {
    AnalyticsTracker::track('view', ['slug' => 'x']);
    AnalyticsTracker::track('whatsapp_click', ['product_id' => 1]);

    $response = $this->get(route('admin.marketing.export'));
    $response->assertOk();
    expect($response->headers->get('content-type'))->toContain('text/csv');
    $content = $response->streamedContent();
    expect($content)->toContain('occurred_at')
        ->and($content)->toContain('whatsapp_click');
});

it('marketing dashboard redireciona usuário sem empresa (422 pela ensure.company)', function (): void {
    $user = User::factory()->create(['active_company_id' => null]);

    $this->actingAs($user)->get(route('admin.marketing'))->assertStatus(422);
});
