<?php

declare(strict_types=1);

namespace App\Domains\Content\Http\Controllers;

use App\Domains\Content\Models\Page;
use App\Domains\Content\Support\MarkdownRenderer;
use Illuminate\Contracts\View\View;

final class PageController
{
    public function show(Page $page, MarkdownRenderer $renderer): View
    {
        abort_unless($page->is_active, 404);

        return view('public.content.show', [
            'page' => $page,
            'content' => $renderer->render($page->content ?? ''),
        ]);
    }
}
