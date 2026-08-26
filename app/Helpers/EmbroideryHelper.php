<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Domains\Catalog\Models\Product;

final class EmbroideryHelper
{
    /** @var list<string> */
    private static array $texanaModels = [
        '4017',
        '4018',
        '4037',
        '4040',
        '4041',
        '4042',
    ];

    /** @var list<string> */
    private static array $infantilTexanaModels = [
        '6002',
        '6003',
    ];

    /** @var list<string> */
    private static array $geralModels = [
        '4010',
        '4031',
        '4032',
        '4036',
        '4047',
        '4048',
        '5002',
        '5003',
        '7005',
    ];

    /**
     * Verifica se o produto possui opção de bordado disponível.
     */
    public static function hasEmbroidery(Product $product): bool
    {
        $code = (string) $product->code;

        if (in_array($code, self::$texanaModels, true)
            || in_array($code, self::$infantilTexanaModels, true)
            || in_array($code, self::$geralModels, true)) {
            return true;
        }

        if (str_contains(mb_strtolower((string) $product->name), 'bordado')
            || str_contains(mb_strtolower((string) $product->subtitle), 'bordado')) {
            return true;
        }

        if (is_array($product->materials)) {
            foreach ($product->materials as $mat) {
                if (str_contains(mb_strtolower((string) $mat), 'bordado')) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Retorna a lista de bordados disponíveis para o produto com imagem e título.
     *
     * @return list<array{code: string, title: string, subtitle: string, image_url: string}>
     */
    public static function getOptionsForProduct(Product $product): array
    {
        $code = (string) $product->code;

        // Modelos infantis texanos (6002 e 6003)
        if (in_array($code, self::$infantilTexanaModels, true)) {
            return [
                [
                    'code' => 'A',
                    'title' => 'Bordado Padrão A',
                    'subtitle' => 'Desenho decorativo no cano do calçado',
                    'image_url' => asset('images/embroidery/infantil-texana-a.webp'),
                ],
            ];
        }

        // Modelo com bordado único específico
        if ($code === '7005') {
            return [
                [
                    'code' => 'A',
                    'title' => 'Bordado Padrão A',
                    'subtitle' => 'Desenho decorativo tradicional no cano',
                    'image_url' => asset('images/embroidery/bordado-a.webp'),
                ],
            ];
        }

        if (in_array($code, self::$texanaModels, true)) {
            return [
                [
                    'code' => 'TEXANA-A',
                    'title' => 'Bordado Texana (Padrão A)',
                    'subtitle' => 'Bordado exclusivo da linha Texana no cano do calçado',
                    'image_url' => asset('images/embroidery/texana-a.webp'),
                ],
            ];
        }

        if (in_array($code, self::$geralModels, true) || self::hasEmbroidery($product)) {
            return [
                [
                    'code' => 'A',
                    'title' => 'Bordado Padrão A',
                    'subtitle' => 'Desenho decorativo tradicional no cano',
                    'image_url' => asset('images/embroidery/bordado-a.webp'),
                ],
                [
                    'code' => 'B',
                    'title' => 'Bordado Padrão B',
                    'subtitle' => 'Desenho estilizado geométrico no cano',
                    'image_url' => asset('images/embroidery/bordado-b.webp'),
                ],
                [
                    'code' => 'C',
                    'title' => 'Bordado Padrão C',
                    'subtitle' => 'Pesponto trabalhado em curvas no cano',
                    'image_url' => asset('images/embroidery/bordado-c.webp'),
                ],
                [
                    'code' => 'D',
                    'title' => 'Bordado Padrão D',
                    'subtitle' => 'Desenho decorativo clássico no cano',
                    'image_url' => asset('images/embroidery/bordado-d.webp'),
                ],
                [
                    'code' => 'F',
                    'title' => 'Bordado Padrão F',
                    'subtitle' => 'Desenho com detalhes estilizados no cano',
                    'image_url' => asset('images/embroidery/bordado-f.webp'),
                ],
                [
                    'code' => 'G',
                    'title' => 'Bordado Padrão G',
                    'subtitle' => 'Desenho harmônico completo no cano',
                    'image_url' => asset('images/embroidery/bordado-g.webp'),
                ],
            ];
        }

        return [];
    }
}
