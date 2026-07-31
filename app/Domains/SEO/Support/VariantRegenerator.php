<?php

declare(strict_types=1);

namespace App\Domains\SEO\Support;

use App\Domains\Catalog\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

/**
 * Regenera variantes thumb/cover WebP para todas as product_images existentes.
 *
 * Chamada por:
 *   - `php artisan catalog:regenerate-variants` (comando console — futuro)
 *   - Migration seeder opcional
 *
 * Repete até que 100% das imagens tenham variant. Idempotente.
 */
final class VariantRegenerator
{
    public function __construct(private readonly ImageIngestor $ingestor) {}

    /** @return array<string, int>  Quantas imagens processadas/criadas/falhas. */
    public function regenerateAll(): array
    {
        $stats = ['processed' => 0, 'generated' => 0, 'failed' => 0];
        $disk = (string) config('catalog.media.disk', 'public');

        ProductImage::query()
            ->orderBy('id')
            ->chunkById(50, function ($images) use (&$stats, $disk): void {
                foreach ($images as $image) {
                    $stats['processed']++;
                    $absolute = Storage::disk($disk)->path($image->path);
                    if (! is_readable($absolute)) {
                        $stats['failed']++;

                        continue;
                    }

                    $baseName = pathinfo($image->path, PATHINFO_FILENAME);
                    $dir = trim(dirname($image->path), '/.');
                    $variants = $this->ingestor->ingestLocalPath($absolute, $dir, $baseName, $disk);

                    $thumb = $variants['thumb'];
                    $cover = $variants['cover'];

                    if ($thumb !== null || $cover !== null) {
                        $stats['generated']++;
                        $image->update([
                            'thumb_path' => $thumb ?? $image->thumb_path,
                            'cover_path' => $cover ?? $image->cover_path,
                        ]);
                    } else {
                        $stats['failed']++;
                    }
                }
            });

        return $stats;
    }
}
