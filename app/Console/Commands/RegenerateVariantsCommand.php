<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\SEO\Support\VariantRegenerator;
use Illuminate\Console\Command;

final class RegenerateVariantsCommand extends Command
{
    protected $signature = 'catalog:regenerate-variants';

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
