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

        // Garante os swatches de materiais sintéticos na pasta pública
        $this->ensureSyntheticSwatches($baseDir);

        /** @var list<array{code: string, variant: ?string, category_slug: string, collection_slug: string, docx: string, cover_image: string}> $definitions */
        $definitions = [
            [
                'code' => '7000',
                'variant' => null,
                'category_slug' => 'coturno',
                'collection_slug' => 'premium',
                'docx' => $baseDir.'/Coturnos/7000 COTURNO ADVENTURE NOBUCK.docx',
                'cover_image' => $baseDir.'/Coturnos/7000.jpg',
            ],
            [
                'code' => '7001',
                'variant' => null,
                'category_slug' => 'coturno',
                'collection_slug' => 'premium',
                'docx' => $baseDir.'/Coturnos/7001 COTURNO ADVENTURE LÁTEGO.docx',
                'cover_image' => $baseDir.'/Coturnos/7001.jpg',
            ],
            [
                'code' => '7007',
                'variant' => null,
                'category_slug' => 'botina-passeio',
                'collection_slug' => 'premium',
                'docx' => $baseDir.'/LINHA PASSEIO/7007 BOTINA NOBUCK CAFE SOLA VAQUEJADA CAFE BORDADO LINHAS.docx',
                'cover_image' => $baseDir.'/LINHA PASSEIO/7007.jpg',
            ],
            [
                'code' => '7006',
                'variant' => null,
                'category_slug' => 'sintetico',
                'collection_slug' => 'classica',
                'docx' => file_exists($baseDir.'/Linha Sintetico/7006/7006-14 SEGURANÇA BIDIN.docx')
                    ? $baseDir.'/Linha Sintetico/7006/7006-14 SEGURANÇA BIDIN.docx'
                    : $baseDir.'/Linha Sintetico/7006-14 SEGURANÇA BIDIN.docx',
                'cover_image' => file_exists($baseDir.'/Linha Sintetico/7006/7006.jpg')
                    ? $baseDir.'/Linha Sintetico/7006/7006.jpg'
                    : $baseDir.'/Linha Sintetico/7006.jpg',
            ],
            [
                'code' => '7010',
                'variant' => null,
                'category_slug' => 'infantil',
                'collection_slug' => 'infantil',
                'docx' => $baseDir.'/linha infatil/7010 BOTINA INFANTIL TEXANA NOBUCK SOLA RAM.docx',
                'cover_image' => $baseDir.'/linha infatil/7010.jpg',
            ],
            [
                'code' => '7011',
                'variant' => null,
                'category_slug' => 'sintetico',
                'collection_slug' => 'infantil',
                'docx' => file_exists($baseDir.'/Linha Sintetico/7011/7011 BOTINA SINTÉTICO INFANTIL TEXANA SOLA RAM.docx')
                    ? $baseDir.'/Linha Sintetico/7011/7011 BOTINA SINTÉTICO INFANTIL TEXANA SOLA RAM.docx'
                    : $baseDir.'/Linha Sintetico/7011 BOTINA SINTÉTICO INFANTIL TEXANA SOLA RAM.docx',
                'cover_image' => file_exists($baseDir.'/Linha Sintetico/7011/7011.jpg')
                    ? $baseDir.'/Linha Sintetico/7011/7011.jpg'
                    : $baseDir.'/Linha Sintetico/7011.jpg',
            ],
            [
                'code' => '7012',
                'variant' => null,
                'category_slug' => 'sintetico',
                'collection_slug' => 'classica',
                'docx' => file_exists($baseDir.'/Linha Sintetico/7012/7012 BOTINA TEXANA CAMURCA CARAMELO SOLA RAM CAFE.docx')
                    ? $baseDir.'/Linha Sintetico/7012/7012 BOTINA TEXANA CAMURCA CARAMELO SOLA RAM CAFE.docx'
                    : $baseDir.'/Linha Sintetico/7012 BOTINA TEXANA CAMURCA CARAMELO SOLA RAM CAFE.docx',
                'cover_image' => file_exists($baseDir.'/Linha Sintetico/7012/7012.jpg')
                    ? $baseDir.'/Linha Sintetico/7012/7012.jpg'
                    : $baseDir.'/Linha Sintetico/7012.jpg',
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

            // Limpa imagens antigas do produto antes de reanexar (garante idempotência e remove swatches/bordados da galeria do produto)
            $product->images()->delete();

            // Ingestão da Capa Principal do Calçado
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
        }

        $this->newLine();
        $this->info('Sincronizando variações oficiais, couros, cores e bordados padronizados...');
        $this->call('catalog:sync-variations');

        $this->info('Todos os novos produtos foram importados e padronizados com sucesso!');

        return self::SUCCESS;
    }

    private function ensureSyntheticSwatches(string $baseDir): void
    {
        $targetDir = public_path('images/swatches/sintetico');
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Bidin / Preto (Prioriza textura oficial do 7006 se presente)
        $bidinCustomSrc = $baseDir.'/Linha Sintetico/7006/bidin.jpg';
        $bidinSrc = file_exists($bidinCustomSrc) ? $bidinCustomSrc : $baseDir.'/Linha Sintetico/bidin.jpg';
        if (file_exists($bidinSrc)) {
            $im = imagecreatefromjpeg($bidinSrc);
            if ($im !== false) {
                imagewebp($im, $targetDir.'/bidin.webp', 90);
                imagewebp($im, $targetDir.'/preto.webp', 90);
            }
        }

        // Sintético Café (Prioriza textura oficial do 7011 se presente)
        $cafeCustomSrc = $baseDir.'/Linha Sintetico/7011 - couro sintético cor café.png';
        $cafeSrc = file_exists($cafeCustomSrc) ? $cafeCustomSrc : $baseDir.'/Linha Sintetico/sintético café.png';
        if (file_exists($cafeSrc)) {
            $im = imagecreatefrompng($cafeSrc);
            if ($im !== false) {
                imagewebp($im, $targetDir.'/cafe.webp', 90);
            }
        }

        // Camurça Caramelo (Prioriza textura oficial do 7012 se presente)
        $carameloCustomSrc = $baseDir.'/Linha Sintetico/7012 - couro camurça cor caramelo.png';
        $carameloSrc = file_exists($carameloCustomSrc) ? $carameloCustomSrc : $baseDir.'/Linha Sintetico/cor camurça caramelo.png';
        if (file_exists($carameloSrc)) {
            $im = imagecreatefrompng($carameloSrc);
            if ($im !== false) {
                imagewebp($im, $targetDir.'/caramelo.webp', 90);
                imagewebp($im, $targetDir.'/camurca-caramelo.webp', 90);
                imagewebp($im, $targetDir.'/island-caramelo.webp', 90);
            }
        }

        // Bordado Padrão A exclusivo para modelo 7011
        $embTargetDir = public_path('images/embroidery');
        if (! is_dir($embTargetDir)) {
            mkdir($embTargetDir, 0755, true);
        }
        $embTarget = $embTargetDir.'/7011-bordado-a.webp';
        $embSrc = $baseDir.'/Linha Sintetico/bordado padrão a.jfif';
        if (file_exists($embSrc) && ! file_exists($embTarget)) {
            $im = imagecreatefromjpeg($embSrc);
            if ($im !== false) {
                imagewebp($im, $embTarget, 90);
            }
        }
    }
}
