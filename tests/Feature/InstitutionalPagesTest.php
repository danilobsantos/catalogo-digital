<?php

declare(strict_types=1);

use App\Domains\Company\Models\Company;
use App\Domains\Content\Models\Page;
use App\Livewire\Public\Content\ContactForm;
use Database\Seeders\BootstrapSeeder;
use Database\Seeders\PageSeeder;
use Illuminate\Support\Facades\Log;

beforeEach(function (): void {
    $this->seed(BootstrapSeeder::class);
    $this->seed(PageSeeder::class);
});

it('página /sobre renderiza Markdown convertido', function (): void {
    $company = Company::where('slug', 'cj-calcados')->first();
    expect($company)->not->toBeNull();

    $r = $this->get('/sobre');
    $r->assertOk();
    expect($r->getContent())->toContain('Sobre a CJ Calçados')
        ->and($r->getContent())->toContain('<h2'); // Markdown -> <h2>
});

it('página /politica-privacidade e /termos funcionam', function (): void {
    $this->get('/politica-privacidade')->assertOk()
        ->assertSee('Política de Privacidade');
    $this->get('/termos')->assertOk()
        ->assertSee('Termos de Uso');
});

it('rota de página inexistente retorna 404', function (): void {
    $this->get('/pagina-nao-existe')->assertNotFound();
});

it('ContactForm: valida campos obrigatórios', function (): void {
    Livewire::test(ContactForm::class)
        ->set('name', '')
        ->set('email', '')
        ->set('message', '')
        ->call('submit')
        ->assertHasErrors(['name', 'email', 'message']);
});

it('ContactForm: envia mensagem válido', function (): void {
    Log::spy();

    Livewire::test(ContactForm::class)
        ->set('name', 'Maria')
        ->set('email', 'maria@example.com')
        ->set('phone', '+55 35 99999-0000')
        ->set('subject', 'Orçamento')
        ->set('message', 'Olá! Gostaria de um orçamento para 50 pares da linha 4000 CA. Pode me passar valores e prazos?')
        ->call('submit')
        ->assertHasNoErrors();

    Log::shouldHaveReceived('info')->withArgs(fn ($msg, $ctx) => $msg === 'contact.form.received');
});

it('PageSeeder cria slugs esperados', function (): void {
    $this->seed(PageSeeder::class);
    expect(Page::where('slug', 'sobre')->exists())->toBeTrue()
        ->and(Page::where('slug', 'politica-privacidade')->exists())->toBeTrue()
        ->and(Page::where('slug', 'termos')->exists())->toBeTrue();
});
