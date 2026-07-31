<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Catalog\Actions\IngestDocxProductAction;
use App\Domains\Catalog\Models\Banner;
use App\Domains\Catalog\Models\Brand;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Collection as CollectionModel;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Support\DocxProductParser;
use App\Domains\Company\Models\Company;
use App\Domains\SEO\Support\ImageIngestor;
use App\Traits\CompanyContext;
use Illuminate\Database\Seeder;

/**
 * Importa produtos a partir da pasta `material/` (DOCX + imagens JPG).
 *
 *  - Cria categorias/coleções/brand padrão se ainda não existirem.
 *  - Para cada DOCX em INFORMAÇÕES TÉCNICAS/, parseia e cria um Product.
 *  - Para cada JPG solto, registra como ProductImage (capa) do produto cujo code bate o nome.
 *
 * Idempotente: `updateOrCreate` por slug/código. Pode ser reexecutado.
 */
final class CatalogSeeder extends Seeder
{
    public function __construct(
        private readonly DocxProductParser $parser,
        private readonly IngestDocxProductAction $ingest,
        private readonly ImageIngestor $ingestor,
    ) {}

    public function run(): void
    {
        $company = Company::where('slug', 'cj-calcados')->first();
        if ($company === null) {
            $this->command?->warn('BootstrapSeeder não foi executado ainda — pulando CatalogSeeder.');

            return;
        }

        CompanyContext::setFallback($company->id);

        $this->seedCategories($company);
        $this->seedCollections($company);
        $this->seedBrand($company);
        $this->seedProducts($company);
        $this->seedBanners($company);

        // Anexa imagens JPG como capa de produtos — só roda em ambiente
        // local/dev (não em testes), pois gera variantes WebP pesadas.
        if (! app()->runningUnitTests()) {
            $this->attachProductImages($company);
        }
    }

    private function seedCategories(Company $company): void
    {
        $categories = [
            ['slug' => 'botina-seguranca', 'name' => 'Botina de Segurança', 'description' => 'Botinas com CA, bico PVC e solado de borracha adventure para uso profissional intenso.'],
            ['slug' => 'botina-tradicional', 'name' => 'Botina Tradicional', 'description' => 'Botinas clássicas com sola pneu, PVC ou latex — para o trabalho do dia a dia no campo e na cidade.'],
            ['slug' => 'botina-passeio', 'name' => 'Botina Passeio', 'description' => 'Botinas com solado leve para uso casual, conforto prolongado.'],
            ['slug' => 'coturno', 'name' => 'Coturno', 'description' => 'Coturnos robustos com cadarço, bico PVC e solado adventure.'],
            ['slug' => 'texana', 'name' => 'Texana', 'description' => 'Botinas texanas — tradicional, feminina e infantil, com solado stylizado.'],
            ['slug' => 'infantil', 'name' => 'Infantil', 'description' => 'Linha infantil — botina, texana e modelos especiais com solado Bento/PVC.'],
            ['slug' => 'outros', 'name' => 'Outros', 'description' => 'Modelos especiais e variações pontuais.'],
        ];

        foreach ($categories as $row) {
            Category::withoutCompanyScope()->updateOrCreate(
                ['company_id' => $company->id, 'slug' => $row['slug']],
                $row + [
                    'company_id' => $company->id,
                    'sort_order' => 10,
                    'is_active' => true,
                    'is_featured' => in_array($row['slug'], ['botina-tradicional', 'botina-passeio', 'texana'], true),
                ],
            );
        }
    }

    private function seedCollections(Company $company): void
    {
        $rows = [
            ['slug' => 'premium', 'name' => 'Premium', 'description' => 'Nossos modelos top — couros e acabamentos selecionados.', 'accent_color' => '#7c3aed'],
            ['slug' => 'profissional', 'name' => 'Profissional', 'description' => 'Linha profissional com CA, foco em segurança.', 'accent_color' => '#0ea5e9'],
            ['slug' => 'feminina', 'name' => 'Feminina', 'description' => 'Linha feminina com costura rosa, modelos texana.', 'accent_color' => '#ec4899'],
            ['slug' => 'infantil', 'name' => 'Infantil', 'description' => 'Linha infantil para os pequenos.', 'accent_color' => '#f59e0b'],
            ['slug' => 'classica', 'name' => 'Clássica', 'description' => 'A botina clássica CJ — tradicional, sem adornos.', 'accent_color' => '#525252'],
        ];

        foreach ($rows as $row) {
            CollectionModel::withoutCompanyScope()->updateOrCreate(
                ['company_id' => $company->id, 'slug' => $row['slug']],
                $row + [
                    'company_id' => $company->id,
                    'sort_order' => 10,
                    'is_active' => true,
                    'is_featured' => false,
                ],
            );
        }
    }

    private function seedBrand(Company $company): void
    {
        Brand::withoutCompanyScope()->updateOrCreate(
            ['company_id' => $company->id, 'slug' => 'cj-calcados'],
            [
                'company_id' => $company->id,
                'name' => 'CJ Calçados',
                'description' => 'Botinas e calçados de couro com qualidade e conforto — tradição em cada passo.',
                'website_url' => 'https://cjcalcados.com.br',
                'sort_order' => 10,
                'is_active' => true,
                'is_featured' => true,
            ],
        );
    }

    private function seedProducts(Company $company): void
    {
        $materialDir = (string) config('catalog.material_path');
        $docxDir = $materialDir.'/INFORMAÇÕES TECNICAS';
        if (! is_dir($docxDir)) {
            $this->command?->warn("Diretório não existe: {$docxDir}");

            return;
        }

        $files = glob($docxDir.'/*.docx') ?: [];
        sort($files);

        $bar = $this->command?->getOutput();
        $bar?->progressStart(count($files));

        foreach ($files as $file) {
            $fileName = basename($file);
            $dto = $this->parser->parse($file, $fileName);
            if ($dto === null) {
                $bar?->progressAdvance();

                continue;
            }

            $this->ingest->execute($company, $dto);

            $bar?->progressAdvance();
        }
        $bar?->progressFinish();
    }

    private function attachProductImages(Company $company): void
    {
        $materialDir = (string) config('catalog.material_path');
        $imgDir = $materialDir;
        if (! is_dir($imgDir)) {
            return;
        }

        $images = collect(glob($imgDir.'/*.{jpg,jpeg,JPG,JPEG}', GLOB_BRACE) ?: [])
            ->reject(fn (string $p): bool => str_contains($p, '/INFORMAÇÕES'));

        foreach ($images as $image) {
            $code = pathinfo($image, PATHINFO_FILENAME);
            $code = preg_split('/\s+/', $code)[0] ?? $code;

            if (! preg_match('/^(\d+)(?:-(\d+))?/', $code, $matches)) {
                continue;
            }
            $rawCode = $matches[1];
            $variant = $matches[2] ?? null;

            $product = Product::query()
                ->where('code', $rawCode)
                ->when($variant !== null, fn ($q) => $q->where('variant_code', $variant))
                ->when($variant === null, fn ($q) => $q->whereNull('variant_code'))
                ->first();

            if ($product === null && $variant === null) {
                $product = Product::query()
                    ->where('code', $rawCode)
                    ->orderByRaw('COALESCE(variant_code, \'0\') ASC')
                    ->first();
            }

            if ($product === null) {
                continue;
            }

            $variants = $this->ingestor->ingestLocalPath(
                $image,
                'products/'.$product->id,
                'seed_'.preg_replace('/[^a-zA-Z0-9]/', '_', pathinfo($image, PATHINFO_FILENAME)),
                'public',
            );

            $stored = $variants['original'];
            if ($stored === null) {
                continue;
            }

            $existing = $product->images()->where('path', $stored)->first();
            if ($existing !== null) {
                continue;
            }

            $cover = ! $product->images()->where('is_cover', true)->exists();

            $product->images()->create([
                'company_id' => $company->id,
                'path' => $stored,
                'thumb_path' => $variants['thumb'],
                'cover_path' => $variants['cover'],
                'disk' => 'public',
                'alt_text' => $product->name,
                'caption' => null,
                'is_cover' => $cover,
                'sort_order' => $product->images()->count(),
            ]);
        }
    }

    private function seedBanners(Company $company): void
    {
        $bannerData = [
            [
                'slug' => 'hero-cj-calcados',
                'title' => 'Tradição em cada passo.',
                'subtitle' => 'Botinas e calçados de couro com qualidade e conforto, fabricados para durar.',
                'description' => 'Há anos vestindo o trabalhador brasileiro com couro selecionado, solado robusto e acabamento premium.',
                'cta_label' => 'Ver Catálogo',
                'cta_route_name' => 'public.products.index',
                'position' => 'hero',
                'sort_order' => 1,
            ],
            [
                'slug' => 'linha-seguranca',
                'title' => 'Linha Profissional com C.A.',
                'subtitle' => 'Botinas com bico PVC, solado antiderrapante e Certificado de Aprovação.',
                'description' => 'Projetadas para indústria, logística, construção civil e serviços gerais.',
                'cta_label' => 'Conhecer Linha',
                'cta_route_name' => 'public.products.index',
                'position' => 'mid-1',
                'sort_order' => 1,
            ],
            [
                'slug' => 'linha-tradicional',
                'title' => 'Botinas Tradicionais',
                'subtitle' => 'O clássico em couro látego, nobuck e vaqueta relax — para o campo e a cidade.',
                'description' => 'Solados em pneu, PVC, latex e adventure para todos os terrenos.',
                'cta_label' => 'Ver Botinas',
                'cta_route_name' => 'public.products.index',
                'position' => 'mid-2',
                'sort_order' => 2,
            ],
        ];

        foreach ($bannerData as $row) {
            Banner::withoutCompanyScope()->updateOrCreate(
                ['company_id' => $company->id, 'slug' => $row['slug']],
                $row + [
                    'company_id' => $company->id,
                    'image_alt' => $row['title'],
                    'is_active' => true,
                ],
            );
        }
    }
}
