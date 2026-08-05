<?php

declare(strict_types=1);

namespace App\Domains\SEO\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;

/**
 * Encapsula operações de ingestão de arquivos locais/public disk.
 *
 *  - `ingestLocalPath($path, $folder, $baseName)` — copia do disco host para storage e gera variantes.
 *  - `ingestUploaded($file, $folder, $prefix)` — para uploads via form.
 *
 * Retorna o conjunto de paths registrados: `{"original": "...", "thumb": "...", "cover": "..."}`.
 *
 * Spatie\Image exige que o PHP tenha `gd` ou `imagick` e suporte WebP (todas as nossas envs têm).
 */
final class ImageIngestor
{
    /**
     * Faz upload (via file path local) do arquivo e gera 3 variantes (original / thumb / cover).
     *
     * @return array{original: ?string, thumb: ?string, cover: ?string}
     */
    public function ingestLocalPath(
        string $absolutePath,
        string $folder,
        string $baseName,
        string $disk = 'public',
    ): array {
        if (! is_readable($absolutePath)) {
            return ['original' => null, 'thumb' => null, 'cover' => null];
        }

        $folder = trim($folder, '/');
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName) ?: 'image';

        $originalRel = $this->persistOriginal($absolutePath, $folder, $baseName, $disk);
        if ($originalRel === null) {
            return ['original' => null, 'thumb' => null, 'cover' => null];
        }

        $variants = $this->generateVariants($absolutePath, $folder, $baseName, $disk);

        return [
            'original' => $originalRel,
            'thumb' => $variants['thumb'],
            'cover' => $variants['cover'],
        ];
    }

    /**
     * Para UploadedFile (Livewire / form).
     *
     * @return array{original: ?string, thumb: ?string, cover: ?string}
     */
    public function ingestUploaded(
        UploadedFile $file,
        string $folder,
        string $prefix = 'img',
        string $disk = 'public',
    ): array {
        $folder = trim($folder, '/');
        $cleanPrefix = preg_replace('/[^a-zA-Z0-9_-]/', '_', $prefix) ?: 'img';
        $baseName = $cleanPrefix.'_'.now()->timestamp;

        $real = $file->getRealPath();
        if ($real === false) {
            return ['original' => null, 'thumb' => null, 'cover' => null];
        }

        $originalRel = $this->persistOriginal($real, $folder, $baseName, $disk);
        if ($originalRel === null) {
            return ['original' => null, 'thumb' => null, 'cover' => null];
        }

        $variants = $this->generateVariants($real, $folder, $baseName, $disk);

        return [
            'original' => $originalRel,
            'thumb' => $variants['thumb'],
            'cover' => $variants['cover'],
        ];
    }

    /**
     * Persiste o original sempre em WebP (resolução mantida), com fallback para o
     * formato de entrada caso a conversão falhe.
     *
     * @return string|null Caminho relativo salvo, ou null em caso de falha.
     */
    private function persistOriginal(
        string $absolute,
        string $folder,
        string $baseName,
        string $disk,
    ): ?string {
        $webpRel = $folder === '' ? "{$baseName}.webp" : "{$folder}/{$baseName}.webp";

        try {
            Image::load($absolute)
                ->format('webp')
                ->quality(85)
                ->save(Storage::disk($disk)->path($webpRel));

            return $webpRel;
        } catch (\Throwable) {
            // Fallback: mantém o formato original.
            $extension = strtolower(pathinfo($absolute, PATHINFO_EXTENSION)) ?: 'jpg';
            $originalRel = $folder === ''
                ? "{$baseName}.{$extension}"
                : "{$folder}/{$baseName}.{$extension}";

            $bytes = file_get_contents($absolute);
            if ($bytes === false) {
                return null;
            }

            Storage::disk($disk)->put($originalRel, $bytes);

            return $originalRel;
        }
    }

    /**
     * @return array{thumb: ?string, cover: ?string}
     */
    private function generateVariants(
        string $absolute,
        string $folder,
        string $baseName,
        string $disk,
    ): array {
        $thumbRel = $folder === '' ? "{$baseName}_thumb.webp" : "{$folder}/{$baseName}_thumb.webp";
        $coverRel = $folder === '' ? "{$baseName}_cover.webp" : "{$folder}/{$baseName}_cover.webp";

        try {
            Image::load($absolute)
                ->format('webp')
                ->quality(85)
                ->width(380)
                ->height(480)
                ->fit(Fit::Crop, 380, 480)
                ->save(Storage::disk($disk)->path($thumbRel));
        } catch (\Throwable) {
            $thumbRel = null;
        }

        try {
            Image::load($absolute)
                ->format('webp')
                ->quality(80)
                ->width(830)
                ->height(1024)
                ->fit(Fit::Crop, 830, 1024)
                ->save(Storage::disk($disk)->path($coverRel));
        } catch (\Throwable) {
            $coverRel = null;
        }

        return ['thumb' => $thumbRel, 'cover' => $coverRel];
    }
}
