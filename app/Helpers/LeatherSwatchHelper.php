<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Str;

final class LeatherSwatchHelper
{
    /** @var array<string, array<string, array{file: string, hex: string}>> */
    private static array $swatchMap = [
        'latego' => [
            'pinhao' => ['file' => 'images/swatches/latego/pinhao.webp', 'hex' => '#4a2810'],
            'chocolate' => ['file' => 'images/swatches/latego/chocolate.webp', 'hex' => '#3b2114'],
            'palha' => ['file' => 'images/swatches/latego/palha.webp', 'hex' => '#dfd2b5'],
            'preto' => ['file' => 'images/swatches/latego/preto.webp', 'hex' => '#1f1e1c'],
        ],
        'nobuck' => [
            'cafe' => ['file' => 'images/swatches/nobuck/cafe.webp', 'hex' => '#432c1e'],
            'camel' => ['file' => 'images/swatches/nobuck/camel.webp', 'hex' => '#b88144'],
            'camelo' => ['file' => 'images/swatches/nobuck/camel.webp', 'hex' => '#b88144'],
            'ferrugem' => ['file' => 'images/swatches/nobuck/ferrugem.webp', 'hex' => '#9e4a24'],
            'castor' => ['file' => 'images/swatches/nobuck/castor.webp', 'hex' => '#875b36'],
        ],
        'vaqueta' => [
            'preto' => ['file' => 'images/swatches/vaqueta/relax.webp', 'hex' => '#1a1918'],
            'relax' => ['file' => 'images/swatches/vaqueta/relax.webp', 'hex' => '#1a1918'],
            'lisa' => ['file' => 'images/swatches/vaqueta/lisa.webp', 'hex' => '#1a1918'],
        ],
        'sintetico' => [
            'island-caramelo' => ['file' => '', 'hex' => '#b87333'],
        ],
    ];

    /**
     * Resolve os dados visuais (foto da tira, hex fallback) para uma cor e couro.
     *
     * @return array{name: string, leather: ?string, image_url: ?string, hex: string}
     */
    public static function getSwatchInfo(string $color, ?string $leather = null): array
    {
        $normalizedColor = Str::slug($color);
        $normalizedLeather = $leather ? Str::slug($leather) : '';

        // Tenta encontrar por couro específico primeiro
        $leatherCategory = 'latego';
        if (str_contains($normalizedLeather, 'nobuck')) {
            $leatherCategory = 'nobuck';
        } elseif (str_contains($normalizedLeather, 'vaqueta')) {
            $leatherCategory = 'vaqueta';
        } elseif (str_contains($normalizedLeather, 'sintetico')) {
            $leatherCategory = 'sintetico';
        }

        // Busca no mapa do couro
        $file = null;
        $hex = '#736a5b';

        if (isset(self::$swatchMap[$leatherCategory][$normalizedColor])) {
            $entry = self::$swatchMap[$leatherCategory][$normalizedColor];
            $file = $entry['file'];
            $hex = $entry['hex'];
        } else {
            // Busca em qualquer categoria
            foreach (self::$swatchMap as $colors) {
                if (isset($colors[$normalizedColor])) {
                    $file = $colors[$normalizedColor]['file'];
                    $hex = $colors[$normalizedColor]['hex'];

                    break;
                }
            }
        }

        // Se for vaqueta lisa ou relax especificamente
        if ($leatherCategory === 'vaqueta') {
            if (str_contains($normalizedLeather, 'lisa')) {
                $file = 'images/swatches/vaqueta/lisa.webp';
            } else {
                $file = 'images/swatches/vaqueta/relax.webp';
            }
            $hex = '#1a1918';
        }

        $imageUrl = ($file && file_exists(public_path($file))) ? asset($file) : null;

        return [
            'name' => $color,
            'leather' => $leather,
            'image_url' => $imageUrl,
            'hex' => $hex,
        ];
    }

    /**
     * Devolve os grupos de variações (Couro => lista de swatches) para um produto em Title Case.
     *
     * @param  array<int, string>|null  $colorsField
     * @return array<string, list<array{name: string, leather: string, image_url: ?string, hex: string}>>
     */
    public static function getGroupedSwatches(?string $leatherField, ?array $colorsField): array
    {
        if (empty($leatherField) && empty($colorsField)) {
            return [];
        }

        $leatherTypes = array_map('trim', explode('/', (string) $leatherField));
        $grouped = [];

        foreach ($leatherTypes as $lType) {
            $normalized = Str::slug($lType);

            // Nome formatado em Title Case
            $lName = match (true) {
                str_contains($normalized, 'vaqueta-relax') || str_contains($normalized, 'relax') => 'Vaqueta Relax',
                str_contains($normalized, 'vaqueta-lisa') || str_contains($normalized, 'lisa') => 'Vaqueta Lisa',
                str_contains($normalized, 'vaqueta-e-bidin') => 'Vaqueta e Bidin',
                str_contains($normalized, 'vaqueta') => 'Vaqueta',
                str_contains($normalized, 'nobuck') => 'Nobuck',
                str_contains($normalized, 'sintetico') => 'Sintético',
                default => 'Látego',
            };

            if (str_contains($normalized, 'latego')) {
                $colors = ['Pinhão', 'Chocolate', 'Palha', 'Preto'];
            } elseif (str_contains($normalized, 'nobuck')) {
                $colors = ['Café', 'Camel', 'Ferrugem', 'Castor'];
            } elseif (str_contains($normalized, 'vaqueta')) {
                $colors = ['Preto'];
            } elseif (str_contains($normalized, 'sintetico')) {
                $colors = ['Island Caramelo'];
            } else {
                $colors = $colorsField ?? ['Preto'];
            }

            // Se o produto tiver cores customizadas específicas (ex: 4047-2 Camel)
            if (count($leatherTypes) === 1 && ! empty($colorsField) && count($colorsField) === 1) {
                $colors = $colorsField;
            }

            $swatches = [];
            foreach ($colors as $col) {
                $swatches[] = self::getSwatchInfo($col, $lName);
            }

            $grouped[$lName] = $swatches;
        }

        return $grouped;
    }
}
