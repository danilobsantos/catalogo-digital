<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Catalog\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

final class SyncProductVariations extends Command
{
    protected $signature = 'catalog:sync-variations 
                            {--dry-run : Executa em modo de simulação sem salvar alterações}
                            {--purge-inactive : Exclui permanentemente os itens desativados antes de sincronizar}';

    protected $description = 'Sincroniza couros, cores, bordados e especificações em Title Case para todos os modelos do catálogo.';

    /**
     * Mapeamento por Código Base do Modelo (Title Case).
     *
     * @var array<string|int, array{leather: string, colors: list<string>}>
     */
    private static array $modelMap = [
        // BOTINA DE SEGURANÇA
        '4000' => [
            'leather' => 'Vaqueta Relax / Látego / Nobuck / Vaqueta',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4008' => [
            'leather' => 'Vaqueta Relax / Látego / Nobuck / Vaqueta Lisa',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4003' => [
            'leather' => 'Látego / Nobuck / Vaqueta',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4011' => [
            'leather' => 'Látego / Nobuck / Vaqueta Lisa',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4027' => [
            'leather' => 'Vaqueta Relax / Látego',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto'],
        ],
        '4028' => [
            'leather' => 'Vaqueta Relax / Látego',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto'],
        ],
        '4029' => [
            'leather' => 'Vaqueta e Bidin Relax / Vaqueta e Bidin Lisa',
            'colors' => ['Preto'],
        ],
        '4043' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4039' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4044' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4045' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4032' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '106' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],

        // BOTINA TRADICIONAL
        '4001' => [
            'leather' => 'Látego',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto'],
        ],
        '4002' => [
            'leather' => 'Látego',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto'],
        ],
        '4004' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4020' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4021' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4031' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],

        // BOTINA PASSEIO
        '4006' => [
            'leather' => 'Látego / Nobuck / Vaqueta Lisa',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4007' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4016' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4017' => [
            'leather' => 'Látego / Nobuck / Vaqueta Lisa',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4009' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4010' => [
            'leather' => 'Látego / Nobuck / Vaqueta Lisa',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4018' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4019' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4012' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4013' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4033' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4023' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4014' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4015' => [
            'leather' => 'Látego / Nobuck / Vaqueta Lisa',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4034' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4025' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4035' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4036' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4037' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4026' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4038' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4041' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4042' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4046' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4040' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],

        // ADICIONAIS / INFANTIL
        '5003' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '5002' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '6000' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '6001' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '6002' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '6003' => [
            'leather' => 'Látego / Nobuck',
            'colors' => ['Pinhão', 'Chocolate', 'Palha', 'Preto', 'Café', 'Camel', 'Ferrugem', 'Castor'],
        ],

        // PRODUTOS COM VARIAÇÃO ESPECÍFICA NO BANCO
        '4047' => [
            'leather' => 'Nobuck',
            'colors' => ['Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '4048' => [
            'leather' => 'Nobuck',
            'colors' => ['Café', 'Camel', 'Ferrugem', 'Castor'],
        ],
        '7003' => [
            'leather' => 'Sintético',
            'colors' => ['Island Caramelo'],
        ],
        '7005' => [
            'leather' => 'Sintético',
            'colors' => ['Island Caramelo'],
        ],
    ];

    /** @var list<string> */
    private static array $embroideryCodes = [
        '4017', '4018', '6002', '6003', '4037', '4040', '4041', '4042',
        '5002', '5003', '4010', '4031', '4032', '4036', '4047', '4048', '7005',
    ];

    /**
     * Mapeamento de normalização de Solados para Title Case correto.
     *
     * @var array<string, string>
     */
    private static array $soleMap = [
        'SOLA DE PVC NATURAL' => 'Sola de PVC Natural',
        'CARAJAS' => 'Carajás',
        'TERRA BRAVA' => 'Terra Brava',
        'PNEU ORIGINAL' => 'Pneu Original',
        'NELORE/SENNA' => 'Nelore / Senna',
        'SOLA BENTO' => 'Sola Bento',
        'BORRACHA' => 'Borracha',
        'SOLA DE SEGURANÇA' => 'Sola de Segurança',
        'PVC' => 'PVC',
        'TEXANA' => 'Texana',
        'SOLA DE PVC CAFE' => 'Sola de PVC Café',
        'SOLA DE SEGURANÇA PVC NATURAL' => 'Sola de Segurança PVC Natural',
        'TEXAS' => 'Texas',
        'ADVENTURE' => 'Adventure',
        'BENTO' => 'Bento',
        'PVC CAFÉ' => 'PVC Café',
        'PVC CAFE' => 'PVC Café',
        'PNEU ECOLOGICO' => 'Pneu Ecológico',
        'LATEX' => 'Látex',
        'PVC NATURAL' => 'PVC Natural',
        'PVC PRETO' => 'PVC Preto',
        'NELORE' => 'Nelore',
        'LATEX BICO REDONDO' => 'Látex Bico Redondo',
        'HUGO AMARELA' => 'Hugo Amarela',
        'BOOTENIS' => 'Bootênis',
        'DOM DIEGO' => 'Dom Diego',
    ];

    /**
     * Mapeamento de normalização de Fechamento para Title Case.
     *
     * @var array<string, string>
     */
    private static array $closureMap = [
        'ELASTICO COBERTO' => 'Elástico Coberto',
        'Elástico Coberto' => 'Elástico Coberto',
        'ELASTICO ABERTO' => 'Elástico Aberto',
        'Elástico Aberto' => 'Elástico Aberto',
        'COM CADARÇO' => 'Com Cadarço',
        'ELASTICO COLMEIA OURO' => 'Elástico Colmeia Ouro',
        'Elástico Lateral' => 'Elástico Lateral',
    ];

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $purgeInactive = (bool) $this->option('purge-inactive');

        if ($isDryRun) {
            $this->warn('--- MODO SIMULAÇÃO (DRY-RUN) ATIVADO --- Nenhuma alteração será gravada.');
        }

        // 1. Excluir inativos se solicitado ou padrão
        if ($purgeInactive) {
            $inactiveCount = Product::where('is_active', false)->count();
            if ($inactiveCount > 0) {
                if (! $isDryRun) {
                    Product::where('is_active', false)->forceDelete();
                    $this->info("{$inactiveCount} produtos inativos foram excluídos com sucesso.");
                } else {
                    $this->info("Simulação: {$inactiveCount} produtos inativos seriam excluídos.");
                }
            }
        }

        $activeProducts = Product::where('is_active', true)->get();
        $this->info("Total de produtos ativos encontrados: {$activeProducts->count()}");

        $updatedCount = 0;
        $skippedCount = 0;
        $rows = [];

        foreach ($activeProducts as $product) {
            $baseCode = preg_replace('/-\d+$/', '', (string) $product->code);

            if (isset(self::$modelMap[$baseCode])) {
                $target = self::$modelMap[$baseCode];
                $targetLeather = $target['leather'];
                $targetColors = $target['colors'];

                // Normaliza Solado
                $currentSole = trim((string) $product->sole);
                $normalizedSole = self::$soleMap[strtoupper($currentSole)] ?? (empty($currentSole) ? null : self::formatTitleCase($currentSole));

                // Normaliza Fechamento
                $currentClosure = trim((string) $product->closure);
                $normalizedClosure = self::$closureMap[$currentClosure] ?? self::$closureMap[strtoupper($currentClosure)] ?? (empty($currentClosure) ? null : self::formatTitleCase($currentClosure));

                // Normaliza Biqueira
                $currentToeCap = trim((string) $product->toe_cap);
                $normalizedToeCap = strtoupper($currentToeCap) === 'PVC' ? 'PVC' : (empty($currentToeCap) ? null : self::formatTitleCase($currentToeCap));

                // Normaliza Materiais (array)
                $normalizedMaterials = [];
                if (! empty($product->materials) && is_array($product->materials)) {
                    $normalizedMaterials = array_map(function ($mat) {
                        return self::$soleMap[strtoupper((string) $mat)] ?? self::$closureMap[strtoupper((string) $mat)] ?? self::formatTitleCase((string) $mat);
                    }, $product->materials);
                }

                // Normaliza Bordados
                $subtitle = $product->subtitle;
                if (in_array($baseCode, self::$embroideryCodes, true)) {
                    $subtitle = 'Opção de Bordado Disponível';
                    if (! in_array('Opção de Bordado Personalizado', $normalizedMaterials, true)) {
                        $normalizedMaterials[] = 'Opção de Bordado Personalizado';
                    }
                }

                // Novo slug sem código de variante
                $newSlug = Str::slug($product->name).'-'.$baseCode;

                $rows[] = [
                    (string) $product->id,
                    $baseCode,
                    $targetLeather,
                    $normalizedSole ?? '-',
                    $normalizedClosure ?? '-',
                    in_array($baseCode, self::$embroideryCodes, true) ? 'Sim' : 'Não',
                ];

                if (! $isDryRun) {
                    $product->update([
                        'code' => $baseCode,
                        'variant_code' => null,
                        'slug' => $newSlug,
                        'leather' => $targetLeather,
                        'colors' => $targetColors,
                        'sole' => $normalizedSole,
                        'closure' => $normalizedClosure,
                        'toe_cap' => $normalizedToeCap,
                        'materials' => $normalizedMaterials,
                        'subtitle' => $subtitle,
                    ]);
                }

                $updatedCount++;
            } else {
                $this->warn("Produto ativo sem mapeamento do código base [{$baseCode}]: ID #{$product->id} - {$product->name}");
                $skippedCount++;
            }
        }

        $this->table(
            ['ID', 'Código Base', 'Couro (Title Case)', 'Solado', 'Fechamento', 'Bordado'],
            $rows
        );

        if ($isDryRun) {
            $this->info("Simulação concluída: {$updatedCount} produtos seriam atualizados. {$skippedCount} ignorados.");
        } else {
            $this->info("Sucesso! {$updatedCount} produtos ativos sincronizados e padronizados em Title Case no banco de dados.");
        }

        return self::SUCCESS;
    }

    private static function formatTitleCase(string $text): string
    {
        $lowercaseWords = ['de', 'da', 'do', 'das', 'dos', 'e', 'em', 'com', 'c/', 'para', 'por', 'a', 'o', 'as', 'os', 'na', 'no'];
        $uppercaseWords = ['pvc', 'c.a.', 'ca', 'eva', 'pu', 'tpu', 'nbr', 'abnt', 'iso', 'nr'];

        $parts = explode('/', $text);
        $formattedParts = [];

        foreach ($parts as $part) {
            $words = preg_split('/\s+/', trim($part));
            if (! $words) {
                continue;
            }

            $formattedWords = [];
            foreach ($words as $i => $word) {
                $clean = Str::lower((string) preg_replace('/[^\w\.]/u', '', $word));

                if (in_array($clean, $uppercaseWords, true)) {
                    $formattedWords[] = Str::upper($clean);
                } elseif ($i > 0 && in_array($clean, $lowercaseWords, true)) {
                    $formattedWords[] = Str::lower($clean);
                } else {
                    $formattedWords[] = Str::ucfirst(Str::lower($word));
                }
            }

            $formattedParts[] = implode(' ', $formattedWords);
        }

        return implode(' / ', $formattedParts);
    }
}
