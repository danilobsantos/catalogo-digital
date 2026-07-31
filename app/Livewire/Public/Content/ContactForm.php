<?php

declare(strict_types=1);

namespace App\Livewire\Public\Content;

use App\Domains\Company\Models\Company;
use App\Traits\CompanyContext;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Formulário de contato institucional.
 *
 *  - Email para `whatsapp_number` recebe as notificações (ou fallback no .env MAIL_TO).
 *  - Validation standards: name (required), email (required), phone (nullable), subject (in),
 *    message (required, 30..3000).
 */
#[Title('Contato · CJ Calçados')]
#[Layout('components.layouts.public')]
final class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $subject = 'Catálogo';

    public string $message = '';

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:32'],
            'subject' => ['required', 'string', 'in:Catálogo,Orçamento,Parceria,Outros'],
            'message' => ['required', 'string', 'min:30', 'max:3000'],
        ];
    }

    /** @return array<string,string> */
    public function messages(): array
    {
        return [
            'message.min' => 'Sua mensagem deve ter ao menos 30 caracteres.',
            'subject.in' => 'Selecione um assunto válido.',
        ];
    }

    public function submit(): void
    {
        $v = $this->validate();

        $companyId = CompanyContext::id();
        $company = $companyId ? Company::find($companyId) : null;

        $payload = [
            'company' => $company?->name ?? config('catalog.company.name'),
            'name' => $v['name'],
            'email' => $v['email'],
            'phone' => $v['phone'],
            'subject' => $v['subject'],
            'message' => $v['message'],
            'ip' => request()->ip(),
        ];

        // Em produção, despachar um job com Mailable. Em dev/staging só Log.
        try {
            Log::info('contact.form.received', $payload);
            // Como não há sistema de mail configurado, garante audiência.
            Mail::html(
                '<pre>'.htmlspecialchars(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)).'</pre>',
                function ($m) use ($payload) {
                    $m->to(config('catalog.contact.recipient', 'admin@cjcalcados.com.br'))
                        ->subject('['.$payload['subject'].'] Contato via site');
                },
            );
        } catch (\Throwable $e) {
            Log::warning('contact.form.mail-failed', ['error' => $e->getMessage()]);
        }

        session()->flash('flash.success', 'Recebemos sua mensagem! Retornaremos em breve.');
        $this->reset(['name', 'email', 'phone', 'message']);
        $this->subject = 'Catálogo';
    }

    public function render(): View
    {
        return view('livewire.public.content.contact-form');
    }
}
