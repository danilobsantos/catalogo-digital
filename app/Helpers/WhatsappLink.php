<?php

declare(strict_types=1);

namespace App\Helpers;

final class WhatsappLink
{
    /**
     * Gera um link wa.me com mensagem pré-preenchida.
     *
     * @param  array<string,scalar|null>  $context  Mapeamento de placeholders do template.
     */
    public static function build(?string $message = null, array $context = []): string
    {
        $number = (string) config('catalog.whatsapp.number');
        $message ??= (string) config('catalog.whatsapp.message');

        $placeholders = [
            '{produto}' => (string) ($context['produto'] ?? ''),
            '{codigo}' => (string) ($context['codigo'] ?? ''),
            '{url}' => (string) ($context['url'] ?? ''),
            '{preco}' => (string) ($context['preco'] ?? ''),
        ];

        $resolved = strtr($message, $placeholders);

        return 'https://wa.me/'.$number.'?text='.rawurlencode($resolved);
    }
}
