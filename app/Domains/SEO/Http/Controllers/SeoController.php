<?php

declare(strict_types=1);

namespace App\Domains\SEO\Http\Controllers;

use App\Domains\Catalog\Models\Brand;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Collection as CollectionModel;
use App\Domains\Catalog\Models\Product;
use Carbon\CarbonInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

/**
 * sitemap.xml + robots.txt para o catálogo público.
 *
 * Multi-tenant-aware: filtra apenas registros do tenant ativo.
 */
final class SeoController
{
    public function sitemap(): Response
    {
        $now = now();

        $urls = [];
        // Home (no images only — sitemap standard variant)
        $urls[] = $this->url(URL::route('home'), $now, 'daily', '1.0');

        // Categorias
        Category::query()->where('is_active', true)
            ->with('parent')
            ->get(['id', 'slug', 'updated_at'])
            ->each(function (Category $c) use ($now) {
                $urls[] = $this->url(
                    URL::route('public.categories.show', $c->slug),
                    $c->updated_at ?? $now,
                    'weekly',
                    '0.8',
                );
            });

        // Brand
        Brand::query()->where('is_active', true)
            ->get(['slug', 'updated_at'])
            ->each(function (Brand $b) use ($now) {
                $urls[] = $this->url(
                    URL::route('public.brands.show', $b->slug),
                    $b->updated_at ?? $now,
                    'monthly',
                    '0.6',
                );
            });

        // Collections (não expostas em URL pública, mas ajudar SEO se a rota existir)
        CollectionModel::query()->where('is_active', true)
            ->get(['slug', 'updated_at'])
            ->each(function (CollectionModel $col) use ($now) {
                $urls[] = $this->url(
                    URL::to('/c/'.$col->slug),
                    $col->updated_at ?? $now,
                    'monthly',
                    '0.5',
                );
            });

        // Produtos
        Product::query()
            ->where('is_active', true)
            ->with(['images' => fn ($q) => $q->where('is_cover', true)])
            ->get(['id', 'slug', 'updated_at', 'has_ca', 'name', 'short_description'])
            ->each(function (Product $p) use (&$urls) {
                $entry = $this->url(
                    URL::route('public.products.show', $p->slug),
                    $p->updated_at ?? now(),
                    'weekly',
                    $p->has_ca ? '0.9' : '0.7',
                    [
                        'image:image' => [
                            'caption' => $p->name,
                        ],
                    ],
                );
                // Use the product's cover image as url:image (image tag inside <url>).
                $cover = $p->images->first();
                if ($cover !== null) {
                    $entry['children'][] = $this->imageNode(URL::to('storage/'.$cover->path));
                }
                $urls[] = $entry;
            });

        $xml = view('seo.sitemap', ['urls' => $urls])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600, s-maxage=7200',
        ]);
    }

    public function robots(): Response
    {
        $env = app()->environment();
        $host = parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'cjcalcados.com.br';

        $body = $env === 'production'
            ? "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /login\nDisallow: /logout\nDisallow: /api/\nSitemap: ".URL::to('/sitemap.xml')."\n"
            : "User-agent: *\nDisallow: /\n";

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * @param  array<string, mixed>  $namespaces
     * @return array<string, mixed>
     */
    private function url(string $loc, CarbonInterface $lastmod, string $changefreq = 'weekly', string $priority = '0.5', array $namespaces = []): array
    {
        return [
            'loc' => $loc,
            'lastmod' => $lastmod->toAtomString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
            'children' => [],
            'namespaces' => $namespaces,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function imageNode(string $urlLoc): array
    {
        return [
            'tag' => 'image:image',
            'image_loc' => $urlLoc,
            'image_caption' => '',
        ];
    }
}
