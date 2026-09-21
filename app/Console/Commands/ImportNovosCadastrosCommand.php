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

        /** @var list<array{code: string, name_fallback: string, category_slug: string, collection_slug: string}> $definitions */
        $definitions = [
            [
                'code' => '7000',
                'name_fallback' => '7000 COTURNO ADVENTURE NOBUCK',
                'category_slug' => 'coturno',
                'collection_slug' => 'premium',
            ],
            [
                'code' => '7001',
                'name_fallback' => '7001 COTURNO ADVENTURE LÁTEGO',
                'category_slug' => 'coturno',
                'collection_slug' => 'premium',
            ],
            [
                'code' => '7007',
                'name_fallback' => '7007 BOTINA NOBUCK CAFE SOLA VAQUEJADA CAFE BORDADO LINHAS',
                'category_slug' => 'botina-passeio',
                'collection_slug' => 'premium',
            ],
            [
                'code' => '7006',
                'name_fallback' => '7006 BOTINA BIDIN RELAX SOLA DE BORRACHA',
                'category_slug' => 'sintetico',
                'collection_slug' => 'classica',
            ],
            [
                'code' => '7010',
                'name_fallback' => '7010 BOTINA INFANTIL TEXANA NOBUCK SOLA RAM',
                'category_slug' => 'infantil',
                'collection_slug' => 'infantil',
            ],
            [
                'code' => '7011',
                'name_fallback' => '7011 BOTINA SINTÉTICO INFANTIL TEXANA SOLA RAM',
                'category_slug' => 'sintetico',
                'collection_slug' => 'infantil',
            ],
            [
                'code' => '7012',
                'name_fallback' => '7012 BOTINA TEXANA CAMURCA CARAMELO SOLA RAM CAFE',
                'category_slug' => 'sintetico',
                'collection_slug' => 'classica',
            ],
        ];

        foreach ($definitions as $def) {
            $this->line("Processando produto [{$def['code']}]...");

            $docxFile = $this->findFile($baseDir, $def['code'], ['docx']);
            $dto = $docxFile ? $parser->parse($docxFile, basename($docxFile)) : null;

            if ($dto !== null) {
                // Ingestão do produto via IngestDocxProductAction
                $product = $ingestAction->execute($company, $dto);
            } else {
                $this->warn("  Aviso: DOCX não encontrado/parseado para [{$def['code']}]. Criando/atualizando via fallback estruturado...");
                $product = $this->createOrUpdateFallback($company, $def);
            }

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

            // Ingestão da Capa Principal do Calçado via busca dinâmica por imagem
            $coverImage = $this->findFile($baseDir, $def['code'], ['jpg', 'png', 'jpeg', 'webp']);
            if ($coverImage && file_exists($coverImage)) {
                $coverVariants = $imageIngestor->ingestLocalPath(
                    $coverImage,
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
            } else {
                $this->warn("  Aviso: Imagem de capa não encontrada para [{$def['code']}].");
            }
        }

        $this->newLine();
        $this->info('Sincronizando variações oficiais, couros, cores e bordados padronizados...');
        $this->call('catalog:sync-variations');

        $this->info('Todos os novos produtos foram importados e padronizados com sucesso!');

        return self::SUCCESS;
    }

    /**
     * Busca arquivos pelo prefixo do código de forma resiliente ao filesystem (sem sensibilidade a encoding NFD/NFC).
     *
     * @param  list<string>  $extensions
     */
    private function findFile(string $baseDir, string $code, array $extensions): ?string
    {
        foreach ($extensions as $ext) {
            $patterns = [
                "{$baseDir}/*/{$code}*.{$ext}",
                "{$baseDir}/*/*/{$code}*.{$ext}",
                "{$baseDir}/{$code}*.{$ext}",
            ];

            foreach ($patterns as $pattern) {
                $matches = glob($pattern);
                if (! empty($matches)) {
                    return $matches[0];
                }
            }
        }

        return null;
    }

    /**
     * Fallback de criação caso o documento Word não possa ser lido.
     *
     * @param  array{code: string, name_fallback: string, category_slug: string, collection_slug: string}  $def
     */
    private function createOrUpdateFallback(Company $company, array $def): Product
    {
        $baseSlug = Str::slug($def['name_fallback']).'-'.$def['code'];
        $slug = $baseSlug;

        $slugConflict = Product::withoutCompanyScope()
            ->where('company_id', $company->id)
            ->where('slug', $slug)
            ->where('code', '!=', $def['code'])
            ->exists();
        if ($slugConflict) {
            $slug = $baseSlug.'-'.Str::lower(Str::random(4));
        }

        return Product::withoutCompanyScope()->updateOrCreate(
            [
                'company_id' => $company->id,
                'code' => $def['code'],
            ],
            [
                'company_id' => $company->id,
                'code' => $def['code'],
                'variant_code' => null,
                'slug' => $slug,
                'name' => $def['name_fallback'],
                'is_new' => true,
                'is_active' => true,
                'sort_order' => (int) $def['code'],
                'published_at' => now(),
            ]
        );
    }

    private function findPattern(string $dir, array $patterns): ?string
    {
        foreach ($patterns as $pattern) {
            $matches = glob("{$dir}/{$pattern}");
            if (! empty($matches)) {
                return $matches[0];
            }
        }

        return null;
    }

    private function ensureSyntheticSwatches(string $baseDir): void
    {
        $targetDir = public_path('images/swatches/sintetico');
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $synthDir = "{$baseDir}/Linha Sintetico";

        // Bidin / Preto (Prioriza textura oficial do 7006 se presente)
        $bidinSrc = $this->findPattern($synthDir, ['7006/bidin.jpg', '*bidin*.jpg']);
        if ($bidinSrc && file_exists($bidinSrc)) {
            $im = imagecreatefromjpeg($bidinSrc);
            if ($im !== false) {
                imagewebp($im, $targetDir.'/bidin.webp', 90);
                imagewebp($im, $targetDir.'/preto.webp', 90);
            }
        }

        // Sintético Café (Prioriza textura oficial do 7011 se presente)
        $cafeSrc = $this->findPattern($synthDir, ['*7011*.png', '*sint*caf*.png', '*caf*.png']);
        if ($cafeSrc && file_exists($cafeSrc)) {
            $im = imagecreatefrompng($cafeSrc);
            if ($im !== false) {
                imagewebp($im, $targetDir.'/cafe.webp', 90);
            }
        }

        // Camurça Caramelo (Prioriza textura oficial do 7012 se presente)
        $carameloSrc = $this->findPattern($synthDir, ['*7012*.png', '*camur*caramelo*.png', '*caramelo*.png']);
        if ($carameloSrc && file_exists($carameloSrc)) {
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
        $embSrc = $this->findPattern($synthDir, ['*7011*.jfif', '*padr*a*.jfif', '*bordado*.jfif']);
        if ($embSrc && file_exists($embSrc) && ! file_exists($embTarget)) {
            $im = imagecreatefromjpeg($embSrc);
            if ($im !== false) {
                imagewebp($im, $embTarget, 90);
            }
        }
    }
}
