<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Catalog\Actions\IngestDocxProductAction;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Collection as CollectionModel;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Support\DocxProductParser;
use App\Domains\Company\Models\Company;
use App\Domains\SEO\Support\ImageIngestor;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

final class ImportNovosCadastrosCommand extends Command
{
    protected $signature = 'catalog:import-novos-cadastros';

    protected $description = 'Importa os 7 novos produtos (Coturnos, Passeio, Linha Sintético e Infantil) com imagens e variações.';

    public function handle(
        DocxProductParser $parser,
        IngestDocxProductAction $ingestAction,
        ImageIngestor $imageIngestor,
    ): int {
        $this->info('Iniciando importação dos novos produtos...');

        $company = Company::where('slug', 'cj-calcados')->first() ?? Company::first();
        if ($company === null) {
            $this->error('Empresa padrão não encontrada.');

            return self::FAILURE;
        }

        // Garante a categoria 'sintetico' conforme padrão de produção
        Category::withoutCompanyScope()->updateOrCreate(
            ['company_id' => $company->id, 'slug' => 'sintetico'],
            [
                'company_id' => $company->id,
                'slug' => 'sintetico',
                'name' => 'Sintético',
                'description' => 'Botinas desenvolvidas para proporcionar conforto, com um excelente acabamento, indicada para uso casual e no dia a dia.',
                'sort_order' => 10,
                'is_active' => true,
                'is_featured' => false,
            ]
        );

        $baseDir = base_path('storage/novos-cadastros');
        if (! is_dir($baseDir)) {
            $this->error("Diretório base não encontrado: {$baseDir}");

            return self::FAILURE;
        }

        /** @var list<array{code: string, variant: ?string, category_slug: string, collection_slug: string, docx: string, cover_image: string, gallery: list<array{path: string, alt: string, caption: string}>}> $definitions */
        $definitions = [
            [
                'code' => '7000',
                'variant' => null,
                'category_slug' => 'coturno',
                'collection_slug' => 'premium',
                'docx' => $baseDir.'/Coturnos/7000 COTURNO ADVENTURE NOBUCK.docx',
                'cover_image' => $baseDir.'/Coturnos/7000.jpg',
                'gallery' => [
                    [
                        'path' => $baseDir.'/Coturnos/nobuk café.jpg',
                        'alt' => 'Amostra Couro Nobuck Café',
                        'caption' => 'Amostra do couro: Nobuck Café',
                    ],
                ],
            ],
            [
                'code' => '7001',
                'variant' => null,
                'category_slug' => 'coturno',
                'collection_slug' => 'premium',
                'docx' => $baseDir.'/Coturnos/7001 COTURNO ADVENTURE LÁTEGO.docx',
                'cover_image' => $baseDir.'/Coturnos/7001.jpg',
                'gallery' => [
                    [
                        'path' => $baseDir.'/Coturnos/latego chocolate.jpg',
                        'alt' => 'Amostra Couro Látego Chocolate',
                        'caption' => 'Amostra do couro: Látego Chocolate',
                    ],
                ],
            ],
            [
                'code' => '7007',
                'variant' => null,
                'category_slug' => 'botina-passeio',
                'collection_slug' => 'premium',
                'docx' => $baseDir.'/LINHA PASSEIO/7007 BOTINA NOBUCK CAFE SOLA VAQUEJADA CAFE BORDADO LINHAS.docx',
                'cover_image' => $baseDir.'/LINHA PASSEIO/7007.jpg',
                'gallery' => [
                    [
                        'path' => $baseDir.'/LINHA PASSEIO/nobuk café.jpg',
                        'alt' => 'Amostra Couro Nobuck Café',
                        'caption' => 'Amostra do couro: Nobuck Café',
                    ],
                    [
                        'path' => $baseDir.'/LINHA PASSEIO/borbado padrão B.jfif',
                        'alt' => 'Detalhe Bordado Padrão Linhas B',
                        'caption' => 'Detalhe do bordado: Padrão B',
                    ],
                ],
            ],
            [
                'code' => '7006',
                'variant' => '14',
                'category_slug' => 'sintetico',
                'collection_slug' => 'classica',
                'docx' => $baseDir.'/Linha Sintetico/7006-14 SEGURANÇA BIDIN.docx',
                'cover_image' => $baseDir.'/Linha Sintetico/7006.jpg',
                'gallery' => [
                    [
                        'path' => $baseDir.'/Linha Sintetico/bidin.jpg',
                        'alt' => 'Amostra Material Sintético Bidin Preto',
                        'caption' => 'Amostra do material: Sintético Bidin Preto',
                    ],
                ],
            ],
            [
                'code' => '7010',
                'variant' => null,
                'category_slug' => 'infantil',
                'collection_slug' => 'infantil',
                'docx' => $baseDir.'/linha infatil/7010 BOTINA INFANTIL TEXANA NOBUCK SOLA RAM.docx',
                'cover_image' => $baseDir.'/linha infatil/7010.jpg',
                'gallery' => [
                    [
                        'path' => $baseDir.'/linha infatil/nobuk café.jpg',
                        'alt' => 'Amostra Couro Nobuck Café',
                        'caption' => 'Amostra do couro: Nobuck Café',
                    ],
                    [
                        'path' => $baseDir.'/linha infatil/bordado padrão a.jfif',
                        'alt' => 'Detalhe Bordado Texana Padrão A',
                        'caption' => 'Detalhe do bordado: Padrão A',
                    ],
                ],
            ],
            [
                'code' => '7011',
                'variant' => null,
                'category_slug' => 'sintetico',
                'collection_slug' => 'infantil',
                'docx' => $baseDir.'/Linha Sintetico/7011 BOTINA SINTÉTICO INFANTIL TEXANA SOLA RAM.docx',
                'cover_image' => $baseDir.'/Linha Sintetico/7011.jpg',
                'gallery' => [
                    [
                        'path' => $baseDir.'/Linha Sintetico/sintético café.png',
                        'alt' => 'Amostra Material Sintético Café',
                        'caption' => 'Amostra do material: Sintético Café',
                    ],
                    [
                        'path' => $baseDir.'/Linha Sintetico/bordado padrão a.jfif',
                        'alt' => 'Detalhe Bordado Texana Padrão A',
                        'caption' => 'Detalhe do bordado: Padrão A',
                    ],
                ],
            ],
            [
                'code' => '7012',
                'variant' => null,
                'category_slug' => 'sintetico',
                'collection_slug' => 'classica',
                'docx' => $baseDir.'/Linha Sintetico/7012 BOTINA TEXANA CAMURCA CARAMELO SOLA RAM CAFE.docx',
                'cover_image' => $baseDir.'/Linha Sintetico/7012.jpg',
                'gallery' => [
                    [
                        'path' => $baseDir.'/Linha Sintetico/cor camurça caramelo.png',
                        'alt' => 'Amostra Material Camurça Caramelo',
                        'caption' => 'Amostra do material: Camurça Caramelo',
                    ],
                    [
                        'path' => $baseDir.'/Linha Sintetico/bordado padrão a.jfif',
                        'alt' => 'Detalhe Bordado Texana Padrão A',
                        'caption' => 'Detalhe do bordado: Padrão A',
                    ],
                ],
            ],
        ];

        foreach ($definitions as $def) {
            $this->line("Processando produto [{$def['code']}]...");

            $dto = $parser->parse($def['docx'], basename($def['docx']));
            if ($dto === null) {
                $this->warn("Falha ao parsear DOCX: {$def['docx']}");

                continue;
            }

            // Ingestão do produto via IngestDocxProductAction
            $product = $ingestAction->execute($company, $dto);

            // Ajustes manuais de categoria, coleção e tags
            $category = Category::withoutCompanyScope()->where('company_id', $company->id)->where('slug', $def['category_slug'])->first();
            $collection = CollectionModel::withoutCompanyScope()->where('company_id', $company->id)->where('slug', $def['collection_slug'])->first();

            $product->update([
                'category_id' => $category?->id ?? $product->category_id,
                'collection_id' => $collection?->id ?? $product->collection_id,
                'is_new' => true,
                'is_active' => true,
            ]);

            // Limpa imagens antigas do produto antes de reanexar (idempotência)
            $product->images()->delete();

            // Ingestão da Capa Principal
            if (file_exists($def['cover_image'])) {
                $coverVariants = $imageIngestor->ingestLocalPath(
                    $def['cover_image'],
                    'products/'.$product->id,
                    $def['code'],
                    'public'
                );

                if ($coverVariants['original'] !== null) {
                    $product->images()->create([
                        'company_id' => $company->id,
                        'path' => $coverVariants['original'],
                        'thumb_path' => $coverVariants['thumb'],
                        'cover_path' => $coverVariants['cover'],
                        'disk' => 'public',
                        'alt_text' => $product->name,
                        'caption' => null,
                        'is_cover' => true,
                        'sort_order' => 0,
                    ]);
                    $this->info("  ✔ Imagem de Capa ingerida: {$def['code']}.webp");
                }
            }

            // Ingestão das Imagens da Galeria (Amostras de Couro, Sintético e Bordados)
            $sortOrder = 1;
            foreach ($def['gallery'] as $galleryItem) {
                if (! file_exists($galleryItem['path'])) {
                    continue;
                }

                $fileBase = pathinfo($galleryItem['path'], PATHINFO_FILENAME);
                $cleanBase = $def['code'].'_'.preg_replace('/[^a-zA-Z0-9]/', '_', Str::slug($fileBase));

                $variants = $imageIngestor->ingestLocalPath(
                    $galleryItem['path'],
                    'products/'.$product->id,
                    $cleanBase,
                    'public'
                );

                if ($variants['original'] !== null) {
                    $product->images()->create([
                        'company_id' => $company->id,
                        'path' => $variants['original'],
                        'thumb_path' => $variants['thumb'],
                        'cover_path' => $variants['cover'],
                        'disk' => 'public',
                        'alt_text' => $galleryItem['alt'] ?? $product->name,
                        'caption' => $galleryItem['caption'] ?? null,
                        'is_cover' => false,
                        'sort_order' => $sortOrder++,
                    ]);
                    $this->info("  ✔ Galeria [{$galleryItem['caption']}]: {$cleanBase}.webp");
                }
            }
        }

        $this->info('Todos os 7 novos produtos foram cadastrados com sucesso!');

        return self::SUCCESS;
    }
}
