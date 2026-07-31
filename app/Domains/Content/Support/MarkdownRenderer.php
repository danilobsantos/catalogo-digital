<?php

declare(strict_types=1);

namespace App\Domains\Content\Support;

use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;

/**
 * Renderizador simples de Markdown seguro + shortcodes.
 *
 * Escapes HTML bruto para texto cru (entre {{...}} para variáveis Blade)
 * e liga hrefs a <a> com `target="_blank" rel="noopener"` quando externos.
 */
class MarkdownRenderer
{
    private CommonMarkConverter $converter;

    public function __construct()
    {
        $this->converter = new CommonMarkConverter([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
    }

    public function render(string $markdown): string
    {
        $markdown = str_replace("\n", "\n\n", $markdown);

        return $this->converter->convert($markdown)->getContent();
    }

    public function summary(?string $markdown, int $limit = 220): ?string
    {
        if ($markdown === null) {
            return null;
        }
        $plain = preg_replace('/[#*_`>\-]+/', '', $markdown) ?? $markdown;
        $plain = preg_replace('/\s+/', ' ', $plain) ?? $plain;

        return Str::limit(trim($plain), $limit, '…');
    }
}
