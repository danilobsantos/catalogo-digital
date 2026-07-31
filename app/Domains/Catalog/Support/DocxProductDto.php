<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Support;

/**
 * Representação estruturada do DOCX extraído de `INFORMAÇÕES TÉCNICAS/`.
 *
 * Extraída por {@see DocxProductParser}.
 */
final class DocxProductDto
{
    /**
     * @param  array<int, string>  $materials
     * @param  array<int, string>  $care
     * @param  array<string, string>  $sizeChart
     * @param  array<int, string>  $colors
     * @param  array<int, string>  $features
     */
    public function __construct(
        public string $rawCode,
        public ?string $variantCode,
        public string $fileName,
        public string $title,
        public string $subtitle,
        public ?string $shortDescription,
        public ?string $description,
        public array $materials = [],
        public ?string $sole = null,
        public ?string $leather = null,
        public ?string $closure = null,
        public ?string $toeCap = null,
        public ?string $approval = null,
        public ?string $weight = null,
        public array $care = [],
        public array $sizeChart = [],
        public array $colors = [],
        public array $features = [],
        public bool $hasCa = false,
        public ?string $manufacturing = null,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'raw_code' => $this->rawCode,
            'variant_code' => $this->variantCode,
            'file_name' => $this->fileName,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'short_description' => $this->shortDescription,
            'description' => $this->description,
            'materials' => $this->materials,
            'sole' => $this->sole,
            'leather' => $this->leather,
            'closure' => $this->closure,
            'toe_cap' => $this->toeCap,
            'approvals' => $this->approval,
            'weight' => $this->weight,
            'care' => $this->care,
            'size_chart' => $this->sizeChart,
            'colors' => $this->colors,
            'features' => $this->features,
            'has_ca' => $this->hasCa,
            'manufacturing' => $this->manufacturing,
        ];
    }
}
