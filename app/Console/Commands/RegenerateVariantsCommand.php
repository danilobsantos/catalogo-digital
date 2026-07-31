<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\SEO\Support\VariantRegenerator;
use Illuminate\Console\Command;

/**
 * Regenera as variantes WebP (thumb/cover) para todas as imagens de produto.
 *
 * Uso:
 *   php artisan catalog:regenerate-variants
 *   php artisan catalog:regenerate-variants --only-empty
 */
final class RegenerateVariantsCommand extends Command
{
    protected $signature = 'catalog:regenerate-variants {--only-empty : Apenas imagens sem variantes geradas}';

    protected $description = 'Gera variantes thumb/cover WebP para product_images (Fase 9).';

    public function handle(VariantRegenerator $generator): int
    {
        $this->info('Gerando variantes de imagens...');
        $stats = $generator->regenerateAll();
        $this->info("Processadas:   {$stats['processed']}");
        $this->info("Geradas:       {$stats['generated']}");
        $this->warn("Falhas:        {$stats['failed']}");

        return self::SUCCESS;
    }
}
